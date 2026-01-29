<?php

namespace src\controllers;

use ClanCats\Hydrahon\Query\Sql\Func;
use \core\Controller;
use \src\models\Login;
use src\models\Caixa;


class CaixaController extends Controller
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
            $data_inicio = filter_input(INPUT_GET, 'data_inicio') ?? date('Y-m-01');
            $data_fim = filter_input(INPUT_GET, 'data_fim') ?? date('Y-m-d');
            $tipo_filtro = filter_input(INPUT_GET, 'tipo') ?? '';
            $mensagem = filter_input(INPUT_GET, 'msg') ?? '';

            if (!empty($tipo_filtro)) {

                $movimentacoes = Caixa::select()
                    ->where('data_movimentacao', '>=', $data_inicio)
                    ->where('data_movimentacao', '<=', $data_fim)
                    ->where('tipo', $tipo_filtro)
                    ->orderBy('data_movimentacao', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $movimentacoes = Caixa::select()
                    ->where('data_movimentacao', '>=', $data_inicio)
                    ->where('data_movimentacao', '<=', $data_fim)
                    ->orderBy('data_movimentacao', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->get();
            }

            $total_entradas = Caixa::select()
                ->addField(new Func('sum', 'valor'), 'valor')
                ->where('data_movimentacao', '>=', $data_inicio)
                ->where('data_movimentacao', '<=', $data_fim)
                ->where('tipo', 'Entrada')->get();
            $total_entradas = $total_entradas[0]['valor'] ?? 0.00;

            $total_saidas = Caixa::select()
                ->addField(new Func('sum', 'valor'), 'valor')
                ->where('data_movimentacao', '>=', $data_inicio)
                ->where('data_movimentacao', '<=', $data_fim)
                ->where('tipo', 'Saida')->get();
            $total_saidas = $total_saidas[0]['valor'] ?? 0.00;

            $saldo = $total_entradas - $total_saidas;


            $this->render('caixa', [
                'movimentacoes' => $movimentacoes,
                'tipo_filtro' => $tipo_filtro,
                'data_inicio' => $data_inicio,
                'data_fim' => $data_fim,
                'total_entradas' => $total_entradas,
                'total_saidas' => $total_saidas,
                'saldo' => $saldo,
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
            $tipo = filter_input(INPUT_POST, 'tipo');
            $categoria = filter_input(INPUT_POST, 'categoria');
            $descricao = filter_input(INPUT_POST, 'descricao');
            $valor = filter_input(INPUT_POST, 'valor');
            $forma_pagamento = filter_input(INPUT_POST, 'forma_pagamento');
            $data_movimentacao = filter_input(INPUT_POST, 'data_movimentacao');
            $id = filter_input(INPUT_POST, 'id');

            if ($acao === 'lancar') {

                Caixa::insert([
                    'tipo' => $tipo,
                    'categoria' => $categoria,
                    'descricao' => $descricao,
                    'valor' => $valor,
                    'forma_pagamento' => $forma_pagamento,
                    'data_movimentacao' => $data_movimentacao,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();

                $mensagem = "Lançamento registrado com sucesso!";
                $this->redirect("/caixa?msg={$mensagem}");
                exit;
            } elseif ($acao === 'excluir') {
                Caixa::delete()->where('id', $id)->execute();
                $mensagem = "Lançamento excluído!";
                $this->redirect("/caixa?msg={$mensagem}");
            }
        }
    }
}
