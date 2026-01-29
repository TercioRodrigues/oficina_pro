<?php

namespace src\controllers;

use \core\Controller;
use \src\models\Login;
use src\models\Ordens_servico;
use src\models\Empresas;

class PainelController extends Controller
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
        $ordens = Ordens_servico::select([
            'ordens_servico.id',
            'ordens_servico.status',
            'ordens_servico.data_abertura',
            'clientes.nome as cliente_nome',
            'veiculos.placa',
            'veiculos.marca',
            'veiculos.modelo'
        ])
            ->join('clientes', 'ordens_servico.cliente_id', '=', 'clientes.id')
            ->join('veiculos', 'ordens_servico.veiculo_id', '=', 'veiculos.id')
            ->where('ordens_servico.status', 'in', ['Aberta', 'Em_Andamento', 'Aguardando_Pecas'])
            ->where('ordens_servico.empresa_id', $_SESSION['empresa_id'])->get();

        $empresa = Empresas::select(['nome_fantasia'])->where('id', $_SESSION['empresa_id'])->get();

        $this->render('painel', [
            'ordens' => $ordens,
            'empresa' => $empresa[0]
        ]);
    }
}
