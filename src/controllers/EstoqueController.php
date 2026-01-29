<?php

namespace src\controllers;

use \core\Controller;

use \src\models\Login;
use src\models\Estoque;
use src\models\Categorias;


class EstoqueController extends Controller
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

            $estoque = Estoque::select([
                'estoque.id',
                'estoque.codigo',
                'estoque.descricao',
                'estoque.quantidade',
                'estoque.preco_custo',
                'estoque.preco_venda',
                'estoque.estoque_minimo',
                'categorias.nome as categoria',
                'categorias.id as categoria_id'
            ])
                ->join('categorias', 'estoque.categoria', '=', 'categorias.id')
                ->where('estoque.empresa_id', $_SESSION['empresa_id'])->get();

            $categorias = Categorias::select()->where('empresa_id', $_SESSION['empresa_id'])->get();

            $this->render('estoque', [
                'produtos' => $estoque,
                'mensagem' => $mensagem,
                'categorias' => $categorias
            ]);
        } else {
            $this->render('acesso_negado', []);
        }
    }

    public function categorias()
    {
        $mensagem = filter_input(INPUT_GET, 'msg') ?? '';
        $categorias = Categorias::select()->where('empresa_id', $_SESSION['empresa_id'])->get();
        $this->render('estoque_categoria', [
            'categorias' => $categorias,
            'mensagem' => $mensagem
        ]);
    }

    public function processarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $acao = filter_input(INPUT_POST, 'acao');

            $codigo = filter_input(INPUT_POST, 'codigo');
            $descricao = filter_input(INPUT_POST, 'descricao');
            $categoria = filter_input(INPUT_POST, 'categoria');
            $quantidade = filter_input(INPUT_POST, 'quantidade');
            $preco_custo = filter_input(INPUT_POST, 'preco_custo');
            $preco_venda = filter_input(INPUT_POST, 'preco_venda');
            $estoque_minimo = filter_input(INPUT_POST, 'estoque_minimo') ?? null;
            $id = filter_input(INPUT_POST, 'id');
            $categoria_nome = filter_input(INPUT_POST, 'categoria_nome');
            $categoria_id = filter_input(INPUT_POST, 'categoria_id');


            if ($acao === 'cadastrar') {
                Estoque::insert([
                    'codigo' => $codigo,
                    'descricao' => $descricao,
                    'categoria' => $categoria,
                    'quantidade' => $quantidade,
                    'preco_custo' => $preco_custo,
                    'preco_venda' => $preco_venda,
                    'estoque_minimo' => $estoque_minimo,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();
                $mensagem = "Produto cadastrado com sucesso!";
                $this->redirect("/estoque?msg={$mensagem}");
                exit;
            } elseif ($acao === 'editar') {
                Estoque::update([
                    'codigo' => $codigo,
                    'descricao' => $descricao,
                    'categoria' => $categoria,
                    'quantidade' => $quantidade,
                    'preco_custo' => $preco_custo,
                    'preco_venda' => $preco_venda,
                    'estoque_minimo' => $estoque_minimo,
                ])
                    ->where('id', $id)
                    ->execute();
                $mensagem = "Produto atualizado com sucesso!";
                $this->redirect("/estoque?msg={$mensagem}");
                exit;
            } elseif ($acao === 'excluir') {

                Estoque::delete()->where('id', $id)->execute();
                $mensagem = "Produto excluído com sucesso!";
                $this->redirect("/estoque?msg={$mensagem}");
                exit;
            } elseif ($acao === 'cadastrar_categoria') {



                Categorias::insert([
                    'nome' => $categoria_nome,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();
                $mensagem = "Categoria adicionada com sucesso!";
                $this->redirect("/estoque/categorias?msg={$mensagem}");
                exit;
            } elseif ($acao === 'editar_categoria') {

                Categorias::update([
                    'nome' => $categoria_nome
                ])
                    ->where('id', $categoria_id)->execute();
                $mensagem = "Categoria editada com sucesso!";
                $this->redirect("/estoque/categorias?msg={$mensagem}");
                exit;
            } elseif ($acao === 'excluir_categoria') {

                Categorias::delete()->where('id', $categoria_id)->execute();
                $mensagem = "Categoria excluída com sucesso!";
                $this->redirect("/estoque/categorias?msg={$mensagem}");
                exit;
            }
        }
    }
}
