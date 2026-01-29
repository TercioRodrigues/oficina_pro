<?php

namespace src\controllers;

use \core\Controller;
use \src\models\Login;
use src\models\Clientes;

class ClientesController extends Controller
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

        $clientes = Clientes::select()->where('empresa_id', $_SESSION['empresa_id'])->get();


        $this->render('clientes', [
            'clientes' => $clientes,
            'mensagem' => $mensagem
        ]);
    }

    public function ProcessarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $acao = filter_input(INPUT_POST, 'acao') ?? '';
            $nome = filter_input(INPUT_POST, 'nome') ?? '';
            $cpf = filter_input(INPUT_POST, 'cpf') ?? '';
            $email = filter_input(INPUT_POST, 'email') ?? '';
            $telefone = filter_input(INPUT_POST, 'telefone') ?? '';
            $endereco = filter_input(INPUT_POST, 'endereco') ?? '';
            $cliente_id = filter_input(INPUT_POST, 'id');

            if ($acao === 'cadastrar') {

                Clientes::insert([
                    'nome' => $nome,
                    'cpf' => $cpf,
                    'telefone' => $telefone,
                    'email' => $email,
                    'endereco' => $endereco,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();
                $mensagem = "Cliente cadastrado com sucesso!";
                $this->redirect("/clientes?msg={$mensagem}");
                exit;
            } elseif ($acao === 'editar') {
                Clientes::update()
                    ->set('nome', $nome)
                    ->set('cpf', $cpf)
                    ->set('telefone', $telefone)
                    ->set('email', $email)
                    ->set('endereco', $endereco)->where('id', $cliente_id)->execute();
                $mensagem = "Cliente atualizado com sucesso!";
                $this->redirect("/clientes?msg={$mensagem}");
                exit;
            } elseif ($acao === 'excluir') {
                Clientes::delete()->where('id', $cliente_id)->execute();
                $mensagem = "Cliente excluído com sucesso!";
                $this->redirect("/clientes?msg={$mensagem}");
                exit;
            }
        }
    }
}
