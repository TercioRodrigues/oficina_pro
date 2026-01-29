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
            ->where('data_abertura', '>=', $mes_atual)
            ->where('data_abertura', '<=', $hoje)
            ->where('empresa_id', $_SESSION['empresa_id'])->count();

        $os_andamento = Ordens_servico::select()
            ->where('status', 'in', ['Aberta', 'Em_Andamento', 'Aguardando_Pecas'])
            ->where('empresa_id', $_SESSION['empresa_id'])->count();

        $faturamento_mes = Ordens_servico::select()
            ->addField(new Func('sum', 'valor_total'), 'faturamento_mes')
            ->where('status', 'Concluido')
            ->where('pago', 'Sim')
            ->where('data_abertura', '>=', $mes_atual)
            ->where('data_abertura', '<=', $hoje)
            ->where('empresa_id', $_SESSION['empresa_id'])->get();
        $faturamento_mes = $faturamento_mes[0]['faturamento_mes'] ?? 0;

        $estoque_baixo = Estoque::estoque_minimo();

        $agendamentos_hoje = Agendamentos::select()
            ->where('data_agendamento', '>=', date('Y-m-d 00:00:00'))
            ->where('data_agendamento', '<=', date('Y-m-d 23:59:59'))
            ->where('status', 'in', ['Agendado', 'Confirmado'])
            ->where('empresa_id', $_SESSION['empresa_id'])->count();

        $os_por_status = Ordens_servico::select(['status'])
            ->addField(new Func('count', 'id'), 'total')
            ->where('data_abertura', '>=', $mes_atual)
            ->where('data_abertura', '<=', $hoje)
            ->where('empresa_id', $_SESSION['empresa_id'])
            ->groupBy('status')->get();


        $faturamento_meses = [];
        $faturamento = Ordens_servico::select('data_fechamento as mes')
            ->addField(new Func('sum', 'valor_total'), 'total')
            ->where('status', 'Concluido')
            ->where('data_fechamento', '>=', date('Y-m-d', strtotime('-6 Month')))
            ->where('empresa_id', $_SESSION['empresa_id'])->get();

        foreach ($faturamento as $mes) {
            $m = $mes['mes'] ?? date('Y-m-d');
            $faturamento_meses[] = [
                'mes' => date('m/Y', strtotime($m)),
                'total' => $mes['total']
            ];
        }

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
            ->where('ordens_servico.empresa_id', $_SESSION['empresa_id'])
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
            ->where('agendamentos.empresa_id', $_SESSION['empresa_id'])
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
