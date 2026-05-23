<?php

namespace src\controllers;

use \core\Controller;
use src\models\Login;
use src\models\Servicos;


class ServicosController extends Controller
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
        $servicos = servicos::select()->where('empresa_id', $_SESSION['empresa_id'])->get();
        $this->render('servicos', [
            'servicos' => $servicos
        ]);
    }

    public function ProcessarAcoes()
    {
        $acao = filter_input(INPUT_POST, 'acao');
        $nome = filter_input(INPUT_POST, 'nome');
        $descricao = filter_input(INPUT_POST, 'descricao');
        $valor = filter_input(INPUT_POST, 'valor');
        $tempo_estimado = filter_input(INPUT_POST, 'tempo_estimado');
        $id = filter_input(INPUT_POST, 'id');


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $acao = $_POST['acao'] ?? '';

            if ($acao === 'cadastrar') {

                Servicos::insert([
                    'nome' => $nome,
                    'descricao' => $descricao,
                    'valor' => $valor,
                    'tempo_estimado' => $tempo_estimado,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();

                $_SESSION['mensagem'] = "Serviço cadastrado com sucesso!";
                $this->redirect("/servicos");
                exit;
            } elseif ($acao === 'editar') {
                Servicos::update([
                    'nome' => $nome,
                    'descricao' => $descricao,
                    'valor' => $valor,
                    'tempo_estimado' => $tempo_estimado,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->where('id', $id)->execute();
                $_SESSION['mensagem'] = "Serviço atualizado com sucesso!";
                $this->redirect("/servicos");
                exit;
            } elseif ($acao === 'excluir') {
                Servicos::delete()->where('id', $id)->execute();
                $_SESSION['mensagem'] = "Serviço excluído com sucesso!";
                $this->redirect("/servicos");
                exit;
            }
        }
    }
}
