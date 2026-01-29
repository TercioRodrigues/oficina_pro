<?php

namespace src\controllers;

use \core\Controller;
use \src\models\Login;
use src\models\Garantias;

class GarantiasController extends Controller
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
        $filtro = filter_input(INPUT_GET, 'filtro') ?? 'ativa';

        if ($filtro == 'ativa') {
            $garantias = Garantias::select([
                'garantias.id',
                'garantias.data_inicio',
                'garantias.data_fim',
                'garantias.tipo',
                'garantias.descricao',
                'garantias.status',
                'ordens_servico.id as os_numero',
                'clientes.nome as cliente_nome',
                'veiculos.placa'
            ])
                ->join('ordens_servico', 'garantias.os_id', '=', 'ordens_servico.id')
                ->join('clientes', 'ordens_servico.cliente_id', '=', 'clientes.id')
                ->join('veiculos', 'ordens_servico.veiculo_id', '=', 'veiculos.id')
                ->where('garantias.status', 'Ativa')
                ->where('garantias.data_fim', '>=', date('Y-m-d'))
                ->where('garantias.empresa_id', $_SESSION['empresa_id'])
                ->orderBy('garantias.data_fim')->get();
        } elseif ($filtro == 'todas') {
            $garantias = Garantias::select([
                'garantias.id',
                'garantias.data_inicio',
                'garantias.data_fim',
                'garantias.tipo',
                'garantias.descricao',
                'garantias.status',
                'ordens_servico.id as os_numero',
                'clientes.nome as cliente_nome',
                'veiculos.placa'
            ])
                ->join('ordens_servico', 'garantias.os_id', '=', 'ordens_servico.id')
                ->join('clientes', 'ordens_servico.cliente_id', '=', 'clientes.id')
                ->join('veiculos', 'ordens_servico.veiculo_id', '=', 'veiculos.id')
                ->where('garantias.empresa_id', $_SESSION['empresa_id'])
                ->orderBy('garantias.data_fim')->get();
        }
        $this->render('garantias', [
            'garantias' => $garantias,
            'filtro' => $filtro
        ]);
    }
}
