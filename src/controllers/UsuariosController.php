<?php

namespace src\controllers;


use \core\Controller;
use \src\models\Login;
use src\models\Usuarios;



class UsuariosController extends Controller
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

            $usuarios = Usuarios::select()
                ->where('empresa_id', $_SESSION['empresa_id'])->orderBy('nome')->get();

            $this->render('usuarios', [
                'usuarios' => $usuarios,
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
            $nome = filter_input(INPUT_POST, 'nome');
            $email = filter_input(INPUT_POST, 'email');
            $senha = filter_input(INPUT_POST, 'senha');
            $nivel = filter_input(INPUT_POST, 'nivel');
            $telefone = filter_input(INPUT_POST, 'telefone');
            $ativo = filter_input(INPUT_POST, 'ativo');
            $id = filter_input(INPUT_POST, 'id');

            if ($acao === 'cadastrar') {

                $verificarEmail = Usuarios::select()->where('email', $email)->where('empresa_id', $_SESSION['empresa_id'])->get();
                if (count($verificarEmail) > 0) {
                    $erro = "Este email já está cadastrado!";
                } else {

                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    Usuarios::insert([
                        'nome' => $nome,
                        'email' => $email,
                        'senha' => $senha_hash,
                        'nivel' => $nivel,
                        'telefone' => $telefone,
                        'ativo' => $ativo,
                        'empresa_id' => $_SESSION['empresa_id']
                    ])->execute();
                    $mensagem = "Usuário cadastrado com sucesso!";
                    $this->redirect("/usuarios?msg={$mensagem}");
                    exit;
                }
            } elseif ($acao === 'editar') {
                if (!empty($senha)) {

                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    Usuarios::update([
                        'nome' => $nome,
                        'email' => $email,
                        'senha' => $senha_hash,
                        'nivel' => $nivel,
                        'telefone' => $telefone,
                        'ativo' => $ativo
                    ])->where('id', $id)->execute();
                } else {

                    Usuarios::update([
                        'nome' => $nome,
                        'email' => $email,
                        'nivel' => $nivel,
                        'telefone' => $telefone,
                        'ativo' => $ativo
                    ])->where('id', $id)->execute();
                }
                $mensagem = "Usuário atualizado!";
                $this->redirect("/usuarios?msg={$mensagem}");
                exit;
            } elseif ($acao === 'excluir') {
                if ($id == $_SESSION['usuario_id']) {
                    $erro = "Você não pode excluir seu próprio usuário!";
                } else {
                    Usuarios::delete()->where('id', $id)->execute();
                    $mensagem = "Usuário excluído!";
                    $this->redirect("/usuarios?msg={$mensagem}");
                    exit;
                }
            } elseif ($acao === 'resetar_senha') {
                $nova_senha = $this->gerarSenhaProvisoria();
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                Usuarios::update()->set('senha', $senha_hash)->where('id', $id)->execute();
                $mensagem = "Senha resetada para: {$nova_senha}";
                $this->redirect("/usuarios?msg={$mensagem}");
                exit;
            }
        }
    }

    public function gerarSenhaProvisoria($tamanho = 8)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$,.;';
        $senha = '';
        $max = strlen($caracteres) - 1;

        for ($i = 0; $i < $tamanho; $i++) {
            $senha .= $caracteres[random_int(0, $max)];
        }

        return $senha;
    }
}
