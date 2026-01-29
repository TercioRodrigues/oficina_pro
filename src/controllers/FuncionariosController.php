<?php

namespace src\controllers;

use \core\Controller;
use \src\models\Login;
use src\models\Funcionarios;


class FuncionariosController extends Controller
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
            $filtro_status = filter_input(INPUT_GET, 'status') ?? 'Ativo';

            if ($filtro_status == 'Todos') {

                $funcionarios = Funcionarios::select()
                    ->where('empresa_id', $_SESSION['empresa_id'])->get();
            } else {

                $funcionarios = Funcionarios::select()
                    ->where('status', $filtro_status)
                    ->where('empresa_id', $_SESSION['empresa_id'])->get();
            }

            $this->render('funcionarios', [
                'mensagem' => $mensagem,
                'filtro_status' => $filtro_status,
                'funcionarios' => $funcionarios
            ]);
        } else {
            $this->render('acesso_negado', []);
        }
    }

    public function ProcessarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $acao = filter_input(INPUT_POST, 'acao');

            $nome = filter_input(INPUT_POST, 'nome');
            $cpf = filter_input(INPUT_POST, 'cpf');
            $rg = filter_input(INPUT_POST, 'rg');
            $data_nascimento = filter_input(INPUT_POST, 'data_nascimento');
            $telefone = filter_input(INPUT_POST, 'telefone');
            $email = filter_input(INPUT_POST, 'email');
            $cargo = filter_input(INPUT_POST, 'cargo');
            $especialidade = filter_input(INPUT_POST, 'especialidade');
            $salario = filter_input(INPUT_POST, 'salario');
            $data_admissao = filter_input(INPUT_POST, 'data_admissao');
            $status = filter_input(INPUT_POST, 'status');
            $endereco = filter_input(INPUT_POST, 'endereco');
            $observacoes = filter_input(INPUT_POST, 'observacoes');
            $id = filter_input(INPUT_POST, 'id');

            if ($acao === 'cadastrar') {

                Funcionarios::insert([
                    'nome' => $nome,
                    'cpf' => $cpf,
                    'rg' => $rg,
                    'data_nascimento' => $data_nascimento,
                    'telefone' => $telefone,
                    'email' => $email,
                    'cargo' => $cargo,
                    'especialidade' => $especialidade,
                    'salario' => $salario,
                    'data_admissao' => $data_admissao,
                    'status' => $status,
                    'endereco' => $endereco,
                    'observacoes' => $observacoes,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();

                $mensagem = "Funcionário cadastrado com sucesso!";
                $this->redirect("/funcionarios?msg={$mensagem}");
                exit;
            } elseif ($acao === 'editar') {

                Funcionarios::update([
                    'nome' => $nome,
                    'cpf' => $cpf,
                    'rg' => $rg,
                    'data_nascimento' => $data_nascimento,
                    'telefone' => $telefone,
                    'email' => $email,
                    'cargo' => $cargo,
                    'especialidade' => $especialidade,
                    'salario' => $salario,
                    'data_admissao' => $data_admissao,
                    'status' => $status,
                    'endereco' => $endereco,
                    'observacoes' => $observacoes
                ])->where('id', $id)->execute();
                $mensagem = "Funcionário atualizado!";
                $this->redirect("/funcionarios?msg={$mensagem}");
                exit;
            } elseif ($acao === 'excluir') {
                Funcionarios::delete()->where('id', $id)->execute();
                $mensagem = "Funcionário excluído!";
                $this->redirect("/funcionarios?msg={$mensagem}");
                exit;
            }
        }
    }
}
