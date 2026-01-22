<?php

namespace src\controllers;

use ClanCats\Hydrahon\Query\Sql\Func;
use \core\Controller;
use src\models\Login;
use src\models\Ordens_servico;
use src\models\Estoque;
use src\models\Agendamentos;

class DashboardController extends Controller
{

    private $UsuarioLogado;
    public function __construct()
    {
        $this->UsuarioLogado = Login::verificarLogin();
        if (!$this->UsuarioLogado) {
            $this->redirect('/login');
            exit;
        }
    }

    public function index()
    {
        $mes_atual = date('Y-m-01');
        $hoje = date('Y-m-d');
        $total_os_mes = Ordens_servico::select()
            ->addField(new Func('count', 'id'), 'total')
            ->where('data_abertura', '>=', $mes_atual)
            ->where('data_abertura', '<=', $hoje)->get();
        $total_os_mes = $total_os_mes[0]['total'];

        $os_andamento = Ordens_servico::select()
            ->addField(new Func('count', 'id'), 'Em_andamento')
            ->where('status', 'in', ['Aberta', 'Em Andamento', 'Aguardando Peças'])->get();
        $os_andamento = $os_andamento[0]['Em_andamento'];

        $faturamento_mes = Ordens_servico::select()
            ->addField(new Func('sum', 'valor_total'), 'faturamento_mes')
            ->where('status', 'Concluída')->where('data_abertura', '>=', $mes_atual)
            ->where('data_abertura', '<=', $hoje)->get();
        $faturamento_mes = $faturamento_mes[0]['faturamento_mes'];


        $estoque_baixo = Estoque::select()
            ->addField(new Func('count', 'id'), 'estoque_baixo')
            ->where('quantidade', '<=', 'estoque_minimo')->get();
        $estoque_baixo = $estoque_baixo[0]['estoque_baixo'];

        $agendamentos_hoje = Agendamentos::select()
            ->addField(new Func('count', 'id'), 'agendamentos_hoje')
            ->where('data_agendamento', date('Y-m-d'))
            ->where('status', 'in', ['Agendado', 'Confirmado'])->get();
        $agendamentos_hoje = $agendamentos_hoje[0]['agendamentos_hoje'];

        $os_por_status = Ordens_servico::select(['status'])
            ->addField(new Func('count', 'id'), 'total')
            ->where('data_abertura', '>=', $mes_atual)
            ->where('data_abertura', '<=', $hoje)
            ->groupBy('status')->get();


        $faturamento_meses = Ordens_servico::select(['data_fechamento'])
            ->addField(new Func('sum', 'valor_total'), 'total')
            ->where('status', 'Concluída')
            ->where('data_fechamento', '>=', date('Y-m-d', strtotime('-6 Month')))->get();

        $ultimas_os = Ordens_servico::select([
            'ordens_servico.id',
            'ordens_servico.valor_total',
            'ordens_servico.data_abertura',
            'ordens_servico.status',
            'clientes.nome as cliente_nome',
            'veiculos.placa'
        ])
            ->join('clientes', 'ordens_servico.cliente_id', '=', 'clientes.id')
            ->join('veiculos', 'ordens_servico.veiculo_id', '=', 'veiculos.id')
            ->orderBy(['ordens_servico.data_abertura' => 'DESC'])->limit(5)->get();

        $proximos_agendamentos = Agendamentos::select([
            'agendamentos.id',
            'agendamentos.status',
            'agendamentos.data_agendamento',
            'agendamentos.servico_solicitado',
            'clientes.nome as cliente_nome',
            'veiculos.placa',
            'veiculos.marca',
            'veiculos.modelo'
        ])
            ->join('clientes', 'agendamentos.cliente_id', '=', 'clientes.id')
            ->join('veiculos', 'agendamentos.veiculo_id', '=', 'veiculos.id')
            ->where('agendamentos.data_agendamento', '>=', date('Y-m-d'))
            ->where('agendamentos.status', 'in', ['Agendado', 'Confirmado'])
            ->orderBy('agendamentos.data_agendamento')->limit(5)->get();


        $this->render('dashboard', [
            'total_os_mes' => $total_os_mes,
            'os_andamento' => $os_andamento,
            'faturamento_mes' => $faturamento_mes,
            'estoque_baixo' => $estoque_baixo,
            'agendamentos_hoje' => $agendamentos_hoje,
            'os_por_status' => $os_por_status,
            'faturamento_meses' => $faturamento_meses,
            'ultimas_os' => $ultimas_os,
            'proximos_agendamentos' => $proximos_agendamentos
        ]);
    }
}
