<?php

namespace src\controllers;

use \core\Controller;

use \src\models\Login;
use src\models\Fornecedores;


class FornecedoresController extends Controller
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
        if ($_SESSION['usuario_nivel'] == 'Admin' || $_SESSION['usuario_nivel'] == 'Gerente') {
            $mensagem = filter_input(INPUT_GET, 'msg') ?? '';
            $fornecedor = Fornecedores::select()->where('empresa_id', $_SESSION['empresa_id'])->get();
            $this->render('fornecedores', [
                'fornecedores' => $fornecedor,
                'mensagem' => $mensagem
            ]);
        } else {
            $this->render('acesso_negado', []);
        }
    }

    public function ProcessarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $acao = filter_input(INPUT_POST, 'acao');
            $nome_empresa = filter_input(INPUT_POST, 'nome_empresa');
            $cnpj = filter_input(INPUT_POST, 'cnpj');
            $contato = filter_input(INPUT_POST, 'contato');
            $telefone = filter_input(INPUT_POST, 'telefone');
            $email = filter_input(INPUT_POST, 'email');
            $endereco = filter_input(INPUT_POST, 'endereco');
            $produtos_fornecidos = filter_input(INPUT_POST, 'produtos_fornecidos');
            $id = filter_input(INPUT_POST, 'id');

            if ($acao === 'cadastrar') {

                Fornecedores::insert([
                    'nome_empresa' => $nome_empresa,
                    'cnpj' => $cnpj,
                    'contato' => $contato,
                    'telefone' => $telefone,
                    'email' => $email,
                    'endereco' => $endereco,
                    'produtos_fornecidos' => $produtos_fornecidos,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();
                $mensagem = "Fornecedor cadastrado com sucesso!";
                $this->redirect("/fornecedores?msg=$mensagem");
                exit;
            } elseif ($acao === 'editar') {
                Fornecedores::update([
                    'nome_empresa' => $nome_empresa,
                    'cnpj' => $cnpj,
                    'contato' => $contato,
                    'telefone' => $telefone,
                    'email' => $email,
                    'endereco' => $endereco,
                    'produtos_fornecidos' => $produtos_fornecidos,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();
                $mensagem = "Fornecedor atualizado com sucesso!";
                $this->redirect("/fornecedores?msg=$mensagem");
                exit;
            } elseif ($acao === 'excluir') {

                Fornecedores::delete()->where('id', $id)->execute();
                $mensagem = "Fornecedor excluído com sucesso!";
                $this->redirect("/fornecedores?msg=$mensagem");
                exit;
            }
        }
    }
}
