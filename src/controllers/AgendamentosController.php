<?php

namespace src\controllers;

use \core\Controller;
use src\models\Login;
use src\models\Agendamentos;
use src\models\Clientes;
use src\models\Veiculos;

class AgendamentosController extends Controller
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
        $filtro = filter_input(INPUT_GET, 'filtro') ?? 'Agendado';
        $mensagem = filter_input(INPUT_GET, 'msg') ?? '';

        $agendamentos = Agendamentos::select([
            'agendamentos.id',
            'agendamentos.cliente_id',
            'agendamentos.veiculo_id',
            'agendamentos.data_agendamento',
            'agendamentos.servico_solicitado',
            'agendamentos.status',
            'agendamentos.observacoes',
            'clientes.nome as cliente_nome',
            'clientes.telefone',
            'veiculos.placa',
            'veiculos.marca',
            'veiculos.modelo',
            'veiculos.ano',
        ])
            ->join('clientes', 'agendamentos.cliente_id', '=', 'clientes.id')
            ->join('veiculos', 'agendamentos.veiculo_id', '=', 'veiculos.id')
            ->where('agendamentos.status', $filtro)
            ->where('agendamentos.empresa_id', $_SESSION['empresa_id'])
            ->orderBy('agendamentos.data_agendamento')->get();


        $clientes = Clientes::select(['id', 'nome'])->where('empresa_id', $_SESSION['empresa_id'])->orderBy('nome')->get();
        $veiculos = Veiculos::select([
            'veiculos.id',
            'veiculos.placa',
            'veiculos.marca',
            'veiculos.modelo',
            'veiculos.ano',
            'veiculos.cor',
            'clientes.nome as cliente_nome'
        ])
            ->join('clientes', 'veiculos.cliente_id', '=', 'clientes.id')
            ->where('veiculos.empresa_id', $_SESSION['empresa_id'])->get();


        $this->render('agendamentos', [
            'agendamentos' => $agendamentos,
            'clientes' => $clientes,
            'veiculos' => $veiculos,
            'mensagem' => $mensagem,
            'filtro' => $filtro
        ]);
    }

    public function processarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $acao = filter_input(INPUT_POST, 'acao');
            $cliente_id = filter_input(INPUT_POST, 'cliente_id');
            $veiculo_id = filter_input(INPUT_POST, 'veiculo_id');
            $data_agendamento = filter_input(INPUT_POST, 'data_agendamento');
            $servico_solicitado = filter_input(INPUT_POST, 'servico_solicitado');
            $observacoes = filter_input(INPUT_POST, 'observacoes');
            $agendamento_id = filter_input(INPUT_POST, 'id');
            $status = filter_input(INPUT_POST, 'status');
            $filtro = filter_input(INPUT_POST, 'filtro');


            if ($acao === 'cadastrar') {
                Agendamentos::insert([
                    'cliente_id' => $cliente_id,
                    'veiculo_id' => $veiculo_id,
                    'data_agendamento' => $data_agendamento,
                    'servico_solicitado' => $servico_solicitado,
                    'observacoes' => $observacoes,
                    'usuario_id' => $_SESSION['usuario_id'],
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();
                $mensagem = "Agendamento criado com sucesso!";
                $this->redirect("/agendamentos?filtro={$filtro}&msg={$mensagem}");
                exit;
            } elseif ($acao === 'editar') {
                Agendamentos::update()
                    ->set('cliente_id', $cliente_id)
                    ->set('veiculo_id', $veiculo_id)
                    ->set('data_agendamento', $data_agendamento)
                    ->set('servico_solicitado', $servico_solicitado)
                    ->set('observacoes', $observacoes)
                    ->set('status', $status)
                    ->where('id', $agendamento_id)->execute();
                $mensagem = "Agendamento atualizado!";
                $this->redirect("/agendamentos?msg=$mensagem");
                exit;
            } elseif ($acao === 'excluir') {
                Agendamentos::delete()->where('id', $agendamento_id)->execute();
                $mensagem = "Agendamento excluído!";
                $this->redirect("/agendamentos?msg?$mensagem");
            }
        }
    }
}
