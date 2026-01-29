<?php

namespace src\controllers;

use \core\Controller;
use Exception;
use \src\models\Login;
use src\models\Veiculos;
use src\models\Clientes;

class VeiculosController extends Controller
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
        $mensagem = filter_input(INPUT_GET, 'msg');

        $veiculos = Veiculos::select([
            'veiculos.id',
            'veiculos.marca',
            'veiculos.modelo',
            'veiculos.ano',
            'veiculos.cor',
            'veiculos.placa',
            'clientes.nome as cliente_nome',
            'clientes.id as cliente_id'
        ])
            ->join('clientes', 'veiculos.cliente_id', '=', 'clientes.id')
            ->where('veiculos.empresa_id', $_SESSION['empresa_id'])
            ->orderBy('clientes.nome')->get();

        $clientes = Clientes::select()->where('empresa_id', $_SESSION['empresa_id'])->orderBy('nome')->get();


        $this->render('veiculos', [
            'veiculos' => $veiculos,
            'mensagem' => $mensagem,
            'clientes' => $clientes
        ]);
    }

    public function historico($id = [])
    {
        $this->render('historico_veiculo', []);
    }

    public function ProcessarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $acao = filter_input(INPUT_POST, 'acao');

            $cliente_id = filter_input(INPUT_POST, 'cliente_id');
            $placa = filter_input(INPUT_POST, 'placa');
            $marca = filter_input(INPUT_POST, 'marca');
            $modelo = filter_input(INPUT_POST, 'modelo');
            $ano = filter_input(INPUT_POST, 'ano');
            $cor = filter_input(INPUT_POST, 'cor');
            $veiculo_id = filter_input(INPUT_POST, 'veiculo_id');


            if ($acao === 'cadastrar') {

                Veiculos::insert([
                    'cliente_id' => $cliente_id,
                    'placa' => $placa,
                    'marca' => $marca,
                    'modelo' => $modelo,
                    'ano' => $ano,
                    'cor' => $cor,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();
                $mensagem = "Veículo cadastrado com sucesso!";
                $this->redirect("/veiculos?msg=$mensagem");
                exit;
            } elseif ($acao === 'editar') {

                try {

                    Veiculos::update()
                        ->set('cliente_id', $cliente_id)
                        ->set('placa', $placa)
                        ->set('marca', $marca)
                        ->set('modelo', $modelo)
                        ->set('ano', $ano)
                        ->set('cor', $cor)->where('id', $veiculo_id)->execute();
                    $mensagem = "Veículo atualizado com sucesso!";
                } catch (Exception $e) {
                    echo "Erro ao atualizar: " . $e->getMessage();
                }

                $this->redirect("/veiculos?msg=$mensagem");
                exit;
            } elseif ($acao === 'excluir') {

                Veiculos::delete()->where('id', $veiculo_id)->execute();
                $mensagem = "Veículo excluído com sucesso!";
                $this->redirect("/veiculos?msg=$mensagem");
                exit;
            }
        }
    }
}
