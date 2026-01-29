<?php

namespace src\controllers;

use ClanCats\Hydrahon\Query\Sql\Func;
use \core\Controller;
use \core\Model;
use src\models\Login;
use src\models\Fornecedores;
use src\models\Compras;
use src\models\Compras_itens;
use src\models\Caixa;
use src\models\Estoque;
use Exception;

class ComprasController extends Controller
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

            $compras = Compras::select([
                'fornecedores.nome_empresa',
                'compras.numero_nf',
                'compras.data_compra',
                'compras.valor_total',
                'compras.observacoes',
                'compras.id',
                'compras.status'
            ])
                ->join('fornecedores', 'compras.fornecedor_id', '=', 'fornecedores.id')
                ->where('compras.empresa_id', $_SESSION['empresa_id'])->orderBy('compras.data_compra', 'DESC')->get();

            $fornecedores = Fornecedores::select()->where('empresa_id', $_SESSION['empresa_id'])->get();
            $this->render('compras', [
                'compras' => $compras,
                'fornecedores' => $fornecedores,
                'mensagem' => $mensagem
            ]);
        } else {
            $this->render('acesso_negado', []);
        }
    }

    public function compraItens($compra_id = [])
    {
        $mensagem = filter_input(INPUT_GET, 'msg') ?? '';
        $compra = Compras::select([
            'fornecedores.nome_empresa',
            'compras.numero_nf',
            'compras.data_compra',
            'compras.valor_total',
            'compras.observacoes',
            'compras.id',
            'compras.status'
        ])
            ->join('fornecedores', 'compras.fornecedor_id', '=', 'fornecedores.id')
            ->where('compras.id', $compra_id['id'])->get();

        $itens = Compras_itens::select([
            'compras_itens.id',
            'compras_itens.produto_id',
            'compras_itens.quantidade',
            'compras_itens.valor_unitario',
            'compras_itens.valor_total',
            'estoque.descricao',
            'estoque.codigo'
        ])
            ->join('estoque', 'compras_itens.produto_id', '=', 'estoque.id')
            ->where('compras_itens.compra_id', $compra_id)
            ->get();

        $produtos = Estoque::select()->where('empresa_id', $_SESSION['empresa_id'])->get();

        $this->render('compras_itens', [
            'compra' => $compra[0],
            'produtos' => $produtos,
            'itens' => $itens,
            'compra_id' => $compra_id['id'],
            'mensagem' => $mensagem
        ]);
    }

    public function ProcessarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $acao = filter_input(INPUT_POST, 'acao');
            $fornecedor_id = filter_input(INPUT_POST, 'fornecedor_id');
            $numero_nf = filter_input(INPUT_POST, 'numero_nf');
            $data_compra = filter_input(INPUT_POST, 'data_compra');
            $valor_total = filter_input(INPUT_POST, 'valor_total') ?? 0.00;
            $observacoes = filter_input(INPUT_POST, 'observacoes');
            $forma_pagamento = filter_input(INPUT_POST, 'forma_pagamento');
            $compra_id = filter_input(INPUT_POST, 'compra_id');

            $produto_id = filter_input(INPUT_POST, 'produto_id');
            $quantidade = filter_input(INPUT_POST, 'quantidade');
            $valor_unitario = filter_input(INPUT_POST, 'valor_unitario');
            $item_id = filter_input(INPUT_POST, 'item_id');

            if ($acao === 'cadastrar') {

                Model::beginTransaction();

                try {

                    $compra_id = Compras::insert([
                        'fornecedor_id' => $fornecedor_id,
                        'numero_nf' => $numero_nf,
                        'data_compra' => $data_compra,
                        'valor_total' => $valor_total,
                        'observacoes' => $observacoes,
                        'empresa_id' => $_SESSION['empresa_id'],
                        'usuario_id' => $_SESSION['usuario_id'],
                        'forma_pagamento' => $forma_pagamento
                    ])->execute();

                    Model::commit();
                    $mensagem = "Compra registrada com sucesso!";
                    $this->redirect("/compras/itens/{$compra_id}&msg={$mensagem}");
                    exit;
                } catch (Exception $e) {
                    Model::rollBack();
                    $mensagem = "Erro ao registrar compra: " . $e->getMessage();
                }
                $this->redirect("/compras&msg={$mensagem}");
                exit;
            } elseif ($acao === 'excluir') {

                try {
                    Compras::delete()->where('id', $compra_id)->execute();
                    $mensagem = "Compra excluída!";
                } catch (Exception $e) {
                    $mensagem = "Erro ao excluir compra: Itens na lista de compras";
                }

                $this->redirect("/compras&msg={$mensagem}");
                exit;
            } elseif ($acao === 'add_item') {


                $valor_total = $quantidade * $valor_unitario;

                Compras_itens::insert([
                    'compra_id' => $compra_id,
                    'produto_id' => $produto_id,
                    'quantidade' => $quantidade,
                    'valor_unitario' => $valor_unitario,
                    'valor_total' => $valor_total
                ])->execute();

                $total = Compras_itens::select()->addField(new Func('sum', 'valor_total'), 'total')
                    ->where('compra_id', $compra_id)->get();

                Compras::update()->set('valor_total', $total[0]['total'])->where('id', $compra_id)->execute();

                $mensagem = "Item adicionado!";
                $this->redirect("/compras/itens/{$compra_id}&msg={$mensagem}");
                exit;
            } elseif ($acao === 'remover_item') {

                try {
                    Compras_itens::delete()->where('id', $item_id)->execute();
                    $mensagem = "Item removido!";

                    $total = Compras_itens::select()->addField(new Func('sum', 'valor_total'), 'total')
                        ->where('compra_id', $compra_id)->get();
                    $total = $total[0]['total'];

                    Compras::update()->set('valor_total', $total)->where('id', $compra_id)->execute();
                } catch (Exception $e) {
                    $mensagem = "Erro ao remover item: " . $e->getMessage();
                }


                $this->redirect("/compras/itens/{$compra_id}&msg={$mensagem}");
                exit;
            } elseif ($acao === 'finalizar_compra') {

                Model::beginTransaction();

                try {
                    $compra_itens = Compras_itens::select()->where('compra_id', $compra_id)->get();

                    foreach ($compra_itens as $item) {
                        $produto = Estoque::select(['quantidade'])->where('id', $item['produto_id'])->get();
                        Estoque::update()
                            ->set('quantidade', $produto[0]['quantidade'] + $item['quantidade'])
                            ->where('id', $item['produto_id'])->execute();
                    }

                    $compra = Compras::select()->where('id', $compra_id)->get();
                    $compra = $compra[0];

                    Caixa::insert([
                        'tipo' => 'Saida',
                        'categoria' => 'Compra de Produtos',
                        'descricao' => 'Compra NF: ' . $compra['descricao'],
                        'valor' => $compra['valor_total'],
                        'forma_pagamento' => $compra['forma_pagamento'],
                        'compra_id' => $compra_id,
                        'data_movimentacao' => $compra['data_compra'],
                        'empresa_id' => $_SESSION['empresa_id']
                    ])->execute();

                    Compras::update()->set('status', 'Concluido')->where('id', $compra_id)->execute();

                    Model::commit();
                    $mensagem = "Compra concluída! O estoque foi atualizado com sucesso.";
                } catch (Exception $e) {
                    Model::rollBack();
                    $mensagem = "Erro ao finalizar a compra";
                }
                $this->redirect("/compras?msg={$mensagem}");
            }
        }
    }
}
