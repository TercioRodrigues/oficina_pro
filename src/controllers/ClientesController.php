<?php

namespace src\controllers;

use \core\Controller;
use \src\models\Login;
use src\models\Clientes;

class ClientesController extends Controller
{
    private bool $UsuarioLogado;
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
        $clientes = Clientes::select()->where('empresa_id', $_SESSION['empresa_id'])->get();

        $this->render('clientes', [
            'clientes' => $clientes
        ]);
    }

    public function ProcessarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $dados = filter_input_array(INPUT_POST);

            if ($dados['acao'] === 'cadastrar') {

                Clientes::insert([
                    'nome' => $dados['nome'],
                    'cpf_cnpj' => $dados['cpf'],
                    'telefone' => $dados['telefone'],
                    'email' => $dados['email'],
                    'empresa_id' => $_SESSION['empresa_id'],
                    'endereco' => $dados['endereco'],
                    'numero' => $dados['numero'],
                    'bairro' => $dados['bairro'],
                    'cidade' => $dados['cidade'],
                    'estado' => $dados['estado'],
                    'cep' => $dados['cep']
                ])->execute();
                $_SESSION['mensagem'] = "Cliente cadastrado com sucesso!";
                $this->redirect("/clientes");
                exit;
            } elseif ($dados['acao'] === 'editar') {
                Clientes::update([
                    'nome' => $dados['nome'],
                    'cpf_cnpj' => $dados['cpf'],
                    'telefone' => $dados['telefone'],
                    'email' => $dados['email'],
                    'empresa_id' => $_SESSION['empresa_id'],
                    'endereco' => $dados['endereco'],
                    'numero' => $dados['numero'],
                    'bairro' => $dados['bairro'],
                    'cidade' => $dados['cidade'],
                    'estado' => $dados['estado'],
                    'cep' => $dados['cep']
                ])
                    ->where('id', $dados['id'])->execute();
                $_SESSION['mensagem'] = "Cliente atualizado com sucesso!";
                $this->redirect("/clientes");
                exit;
            } elseif ($dados['acao'] === 'excluir') {
                Clientes::delete()->where('id', $dados['id'])->execute();
                $_SESSION['mensagem'] = "Cliente excluído com sucesso!";
                $this->redirect("/clientes");
                exit;
            }
        }
    }
}
