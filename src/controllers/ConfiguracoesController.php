<?php

namespace src\controllers;

use \core\Controller;
use \src\models\Login;
use src\models\Empresas;


class ConfiguracoesController extends Controller
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
            $empresa = Empresas::select()->where('id', $_SESSION['empresa_id'])->get();
            $empresa = $empresa[0];
            $this->render('configuracoes', [
                'config' => $empresa,
                'mensagem' => $mensagem
            ]);
        } else {
            $this->render('acesso_negado', []);
        }
    }

    public function ProcessarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $razao_social = filter_input(INPUT_POST, 'razao_social');
            $nome_fantasia = filter_input(INPUT_POST, 'nome_fantasia');
            $cnpj = filter_input(INPUT_POST, 'cnpj');
            $inscricao_estadual = filter_input(INPUT_POST, 'inscricao_estadual');
            $telefone = filter_input(INPUT_POST, 'telefone');
            $whatsapp = filter_input(INPUT_POST, 'whatsapp');
            $email = filter_input(INPUT_POST, 'email');
            $site = filter_input(INPUT_POST, 'site');
            $cep = filter_input(INPUT_POST, 'cep');
            $endereco = filter_input(INPUT_POST, 'endereco');
            $numero = filter_input(INPUT_POST, 'numero');
            $complemento = filter_input(INPUT_POST, 'complemento');
            $bairro = filter_input(INPUT_POST, 'bairro');
            $cidade = filter_input(INPUT_POST, 'cidade');
            $estado = filter_input(INPUT_POST, 'estado');
            $horario_funcionamento = filter_input(INPUT_POST, 'horario_funcionamento');
            $observacoes = filter_input(INPUT_POST, 'observacoes');



            Empresas::update([
                'razao_social' => $razao_social,
                'nome_fantasia' => $nome_fantasia,
                'cnpj' => $cnpj,
                'inscricao_estadual' => $inscricao_estadual,
                'telefone' => $telefone,
                'whatsapp' => $whatsapp,
                'email' => $email,
                'site' => $site,
                'cep' => $cep,
                'endereco' => $endereco,
                'numero' => $numero,
                'complemento' => $complemento,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'estado' => $estado,
                'horario_funcionamento' => $horario_funcionamento,
                'observacoes' => $observacoes
            ])->where('id', $_SESSION['empresa_id'])->execute();

            $_SESSION['empresa_nome'] = $nome_fantasia;

            $mensagem = "Configurações atualizadas com sucesso!";
            $this->redirect("/configuracoes?msg={$mensagem}");
        }
    }
}
