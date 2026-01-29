<?php

namespace src\controllers;

use \core\Controller;
use \core\Model;
use src\models\Login;
use src\models\Ordens_servico;
use src\models\Veiculos;
use src\models\Clientes;
use src\models\Estoque;
use src\models\Os_itens_produtos;
use src\models\Os_itens_servicos;
use src\models\Servicos;
use ClanCats\Hydrahon\Query\Sql\Func;
use src\models\Caixa;
use src\models\Empresas;
use src\models\Garantias;
use Exception;
use src\models\Agendamentos;

class OsController extends Controller
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
        $mensagem = filter_input(INPUT_GET, 'msg') ?? '';
        $filtro = filter_input(INPUT_GET, 'status') ?? 'Aberta';
        $ordens = Ordens_servico::select([
            'ordens_servico.id',
            'ordens_servico.data_abertura',
            'ordens_servico.status',
            'ordens_servico.valor_total',
            'ordens_servico.pago',
            'clientes.nome as cliente_nome',
            'veiculos.placa',
            'veiculos.marca',
            'veiculos.modelo',
            'veiculos.ano'
        ])
            ->join('clientes', 'ordens_servico.cliente_id', '=', 'clientes.id')
            ->join('veiculos', 'ordens_servico.veiculo_id', '=', 'veiculos.id')
            ->where('status', $filtro)
            ->where('ordens_servico.empresa_id', $_SESSION['empresa_id'])
            ->orderBy('ordens_servico.data_abertura', 'DESC')
            ->get();

        $clientes = Clientes::select()->where('empresa_id', $_SESSION['empresa_id'])->orderBy('nome')->get();

        $veiculos = Veiculos::select([
            'veiculos.id',
            'veiculos.placa',
            'veiculos.marca',
            'veiculos.ano',
            'veiculos.modelo',
            'clientes.nome as cliente_nome'
        ])
            ->join('clientes', 'veiculos.cliente_id', '=', 'clientes.id')
            ->where('veiculos.empresa_id', $_SESSION['empresa_id'])
            ->orderBy('clientes.nome')->get();

        $this->render('ordens', [
            'ordens' => $ordens,
            'clientes' => $clientes,
            'veiculos' => $veiculos,
            'mensagem' => $mensagem,
            'status_filtro' => $filtro
        ]);
    }

    public function osItens()
    {
        $os_id = filter_input(INPUT_GET, 'os_id');
        $mensagem = filter_input(INPUT_GET, 'msg') ?? '';

        $os = Ordens_servico::select([
            'ordens_servico.id',
            'ordens_servico.data_abertura',
            'ordens_servico.status',
            'ordens_servico.valor_total',
            'ordens_servico.desconto',
            'ordens_servico.valor_pecas',
            'ordens_servico.valor_servicos',
            'ordens_servico.descricao_problema',
            'ordens_servico.observacoes',
            'ordens_servico.pago',
            'clientes.nome as cliente_nome',
            'veiculos.placa',
            'veiculos.marca',
            'veiculos.modelo',
            'veiculos.ano'
        ])
            ->join('clientes', 'ordens_servico.cliente_id', '=', 'clientes.id')
            ->join('veiculos', 'ordens_servico.veiculo_id', '=', 'veiculos.id')
            ->where('ordens_servico.id', $os_id)
            ->orderBy('ordens_servico.data_abertura', 'DESC')
            ->get();
        $os = $os[0];

        $itens_produtos = Os_itens_produtos::select([
            'os_itens_produtos.id',
            'os_itens_produtos.quantidade',
            'os_itens_produtos.valor_unitario',
            'os_itens_produtos.valor_total',
            'estoque.descricao',
            'estoque.codigo'
        ])
            ->join('estoque', 'os_itens_produtos.produto_id', '=', 'estoque.id')
            ->where('os_itens_produtos.os_id', $os_id)->execute();



        $itens_servicos = Os_itens_servicos::select([
            'os_itens_servicos.id',
            'os_itens_servicos.quantidade',
            'os_itens_servicos.valor_unitario',
            'os_itens_servicos.valor_total',
            'servicos.nome'
        ])
            ->join('servicos', 'os_itens_servicos.servico_id', '=', 'servicos.id')
            ->where('os_itens_servicos.os_id', $os_id)->get();

        $produtos = Estoque::select()->where('quantidade', '>', 0)->orderBy('descricao')->get();
        $servicos = Servicos::select()->orderBy('nome')->get();


        $this->render('os_itens', [
            'os' => $os,
            'produtos_os' => $itens_produtos,
            'servicos_os' => $itens_servicos,
            'produtos' => $produtos,
            'servicos' => $servicos,
            'mensagem' => $mensagem
        ]);
    }

    public function processarAcoes()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $acao = filter_input(INPUT_POST, 'acao') ?? '';
            $os_id = filter_input(INPUT_POST, 'os_id');


            $cliente_id = filter_input(INPUT_POST, 'cliente_id');
            $veiculo_id = filter_input(INPUT_POST, 'veiculo_id');
            $data_abertura = filter_input(INPUT_POST, 'data_abertura') ?? date('Y-m-d');
            $status = filter_input(INPUT_POST, 'status') ?? 'Aberta';
            $descricao_problema = filter_input(INPUT_POST, 'descricao_problema');
            $obsercacoes = filter_input(INPUT_POST, 'observacoes');
            $valor_total = filter_input(INPUT_POST, 'valor_total') ?? 0;
            $produto_id = filter_input(INPUT_POST, 'produto_id');
            $quantidade = filter_input(INPUT_POST, 'quantidade');
            $servico_id = filter_input(INPUT_POST, 'servico_id');
            $item_id = filter_input(INPUT_POST, 'item_id');
            $desconto = filter_input(INPUT_POST, 'desconto') ?? 0;
            $forma_pagamento = filter_input(INPUT_POST, 'forma_pagamento');
            $agendamento_id = filter_input(INPUT_POST, 'agendamento_id');


            if ($acao === 'cadastrar') {

                $os_id = Ordens_servico::insert([
                    'cliente_id' => $cliente_id,
                    'veiculo_id' => $veiculo_id,
                    'data_abertura' => $data_abertura,
                    'status' => $status,
                    'descricao_problema' => $descricao_problema,
                    'observacoes' => $obsercacoes,
                    'valor_total' => $valor_total,
                    'usuario_id' => $_SESSION['usuario_id'],
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();

                $mensagem = "Ordem de serviço cadastrada com sucesso!";

                if (!empty($agendamento_id)) {
                    Agendamentos::update()
                        ->set('os_id', $os_id)
                        ->set('status', 'Concluido')
                        ->where('id', $agendamento_id)->execute();
                    $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
                    exit;
                }

                $this->redirect("/Os?msg=$mensagem");
                exit;
            } elseif ($acao === 'editar') {
                Ordens_servico::update()
                    ->set('descricao_problema', $descricao_problema)
                    ->set('observacoes', $obsercacoes)->where('id', $os_id)->execute();

                $mensagem = "Ordem de serviço atualizada com sucesso!";
                $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'editar_status') {
                Ordens_servico::update()
                    ->set('status', $status)->where('id', $os_id)->execute();

                $mensagem = "Status atualizado com sucesso!";
                $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'excluir') {
                Ordens_servico::delete()->where('id', $os_id)->execute();
                $mensagem = "Ordem de serviço excluída com sucesso!";
                $this->redirect("/Os?msg=$mensagem");
            } elseif ($acao === 'add_produto') {


                $produto = Estoque::select(['preco_venda'])->where('id', $produto_id)->get();
                $valor_unitario = $produto[0]['preco_venda'];

                $valor_total = $valor_unitario * $quantidade;


                Os_itens_produtos::insert([
                    'os_id' => $os_id,
                    'produto_id' => $produto_id,
                    'quantidade' => $quantidade,
                    'valor_unitario' => $valor_unitario,
                    'valor_total' => $valor_total
                ])->execute();

                $mensagem = "Produto adicionado com sucesso!";
                $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'add_servico') {

                $servico = Servicos::select()->where('id', $servico_id)->get();
                $valor_unitario = $servico[0]['valor'];

                $valor_total = $valor_unitario * $quantidade;

                Os_itens_servicos::insert([
                    'os_id' => $os_id,
                    'servico_id' => $servico_id,
                    'quantidade' => $quantidade,
                    'valor_unitario' => $valor_unitario,
                    'valor_total' => $valor_total
                ])->execute();

                $mensagem = "Serviço adicionado com sucesso!";
                $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
            } elseif ($acao === 'remover_produto') {

                Os_itens_produtos::delete()->where('id', $item_id)->execute();
                $mensagem = "Produto removido!";
                $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'remover_servico') {

                Os_itens_servicos::delete()->where('id', $item_id)->execute();
                $mensagem = "Serviço removido!";
                $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'finalizar_os') {

                Model::beginTransaction();

                try {

                    $produtos = Os_itens_produtos::select(['produto_id', 'quantidade'])->where('os_id', $os_id)->get();
                    $servicos = Os_itens_servicos::select()->where('os_id', $os_id)->get();
                    $empresa = Empresas::select()->where('id', $_SESSION['empresa_id'])->get();
                    $dias = $empresa[0]['produtos_garantia'];
                    $data_inicio = date('Y-m-d');

                    // Baixar estoque
                    foreach ($produtos as $prod) {

                        $estoque = Estoque::select(['quantidade'])->where('id', $prod['produto_id'])->get();

                        $baixa = $estoque[0]['quantidade'] - $prod['quantidade'];

                        Estoque::update()
                            ->set('quantidade', $baixa)->where('id', $prod['produto_id'])->execute();
                    }

                    Ordens_servico::update()
                        ->set('status', 'Concluido')
                        ->set('pago', 'Sim')
                        ->set('forma_pagamento', $forma_pagamento)
                        ->set('data_fechamento', date('Y-m-d'))->where('id', $os_id)->execute();



                    $os = Ordens_servico::select(['ordens_servico.valor_total', 'clientes.nome'])
                        ->join('clientes', 'ordens_servico.cliente_id', '=', 'clientes.id')->where('ordens_servico.id', $os_id)->get();
                    $cliente = $os[0]['nome'];
                    $valor_total = $os[0]['valor_total'];

                    Caixa::insert([
                        'tipo' => 'Entrada',
                        'categoria' => 'Servicos',
                        'descricao' => "OS $ {$os_id} - {$cliente}",
                        'valor' => $valor_total,
                        'forma_pagamento' => $forma_pagamento,
                        'os_id' => $os_id,
                        'usuario_id' => $_SESSION['usuario_id'],
                        'data_movimentacao' => date('Y-m-d'),
                        'empresa_id' => $_SESSION['empresa_id']
                    ])->execute();


                    foreach ($produtos as $prod) {


                        $produto_desc = Estoque::select('descricao')->where('id', $prod['produto_id'])->get();


                        if ($dias > 0) {


                            $data_fim = date('Y-m-d', strtotime("+{$dias} days"));

                            Garantias::insert([
                                'os_id' => $os_id,
                                'tipo' => 'Peca',
                                'item_id' => $prod['produto_id'],
                                'descricao' => $produto_desc[0]['descricao'],
                                'data_inicio' => $data_inicio,
                                'data_fim' => $data_fim,
                                'empresa_id' => $_SESSION['empresa_id']
                            ])->execute();
                        }
                    }

                    foreach ($servicos as $serv) {
                        $servico = Servicos::select()->where('id', $serv['servico_id'])->get();
                        $servico[0];
                        $servico_garantia = $servico['garantia_dias'];
                        if ($servico_garantia > 0) {

                            $data_fim = date('Y-m-d', strtotime("+{$servico_garantia} days"));
                            Garantias::insert([
                                'os_id' => $os_id,
                                'tipo' => 'Serviço',
                                'item_id' => $serv['servico_id'],
                                'descricao' => $serv['nome'],
                                'data_inicio' => $data_inicio,
                                'data_fim' => $data_fim,
                                'empresa_id' => $_SESSION['empresa_id']
                            ])->execute();
                        }
                    }

                    // Enviar WhatsApp
                    $cliente = Clientes::select()->where('id', $os['cliente_id'])->get();
                    $cliente = $cliente[0];

                    if (!empty($cliente)) {
                        $mensagem_wpp = "Olá! Sua OS #{$os_id} foi finalizada.\n\nTotal: R$ " . number_format($os['valor_total'], 2, ',', '.') . "\n\nObrigado pela preferência!\n\n" . $empresa[0]['nome_fantasia'];
                        //enviarWhatsApp($cliente['telefone'], $mensagem_wpp, 'OS Concluída', $os_id);
                    }

                    Model::commit();
                    $mensagem = "OS finalizada com sucesso!";
                } catch (Exception $e) {
                    Model::rollBack();
                    $mensagem = "Erro ao finalizar OS: " . $e->getMessage();
                }
                $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'atualizar_valores') {

                $valor_pecas = Os_itens_produtos::select()->addField(new Func('sum', 'valor_total'), 'total')->where('os_id', $os_id)->execute();
                $valor_pecas = $valor_pecas[0]['total'] ?? 0;

                $valor_servicos = Os_itens_servicos::select()->addField(new Func('sum', 'valor_total'), 'total')->where('os_id', $os_id)->execute();
                $valor_servicos = $valor_servicos[0]['total'] ?? 0;

                $valor_total = ($valor_pecas + $valor_servicos) - $desconto;

                Ordens_servico::update()
                    ->set('valor_pecas', $valor_pecas)
                    ->set('valor_servicos', $valor_servicos)
                    ->set('desconto', $desconto)
                    ->set('valor_total', $valor_total)->where('id', $os_id)->execute();

                $mensagem = "Valores atualizados!";
                $this->redirect("/Os/itens?os_id=$os_id&msg=$mensagem");
            }
        }
    }

    public function imprimirOs($os_id = [])
    {
        $os = Ordens_servico::select([
            'ordens_servico.id',
            'ordens_servico.data_abertura',
            'ordens_servico.data_fechamento',
            'ordens_servico.status',
            'ordens_servico.descricao_problema',
            'ordens_servico.valor_pecas',
            'ordens_servico.valor_servicos',
            'ordens_servico.desconto',
            'ordens_servico.valor_total',
            'clientes.nome as cliente_nome',
            'clientes.cpf',
            'clientes.telefone',
            'clientes.endereco',
            'veiculos.placa',
            'veiculos.marca',
            'veiculos.modelo',
            'veiculos.ano',
            'veiculos.cor',
            'usuarios.nome as atendente_nome'
        ])
            ->join('clientes', 'ordens_servico.cliente_id', '=', 'clientes.id')
            ->join('veiculos', 'ordens_servico.veiculo_id', '=', 'veiculos.id')
            ->join('usuarios', 'ordens_servico.usuario_id', '=', 'usuarios.id')
            ->where('ordens_servico.id', $os_id['id'])->get();
        $os = $os[0];

        $oficina = Empresas::select()->where('id', $_SESSION['empresa_id'])->get();
        $oficina = $oficina[0];

        $produtos_os = Os_itens_produtos::select([
            'os_itens_produtos.id',
            'os_itens_produtos.quantidade',
            'os_itens_produtos.valor_unitario',
            'os_itens_produtos.valor_total',
            'estoque.descricao',
            'estoque.codigo'
        ])
            ->join('estoque', 'os_itens_produtos.produto_id', '=', 'estoque.id')
            ->where('os_itens_produtos.os_id', $os_id['id'])->get();

        $servicos_os = Os_itens_servicos::select([
            'os_itens_servicos.quantidade',
            'os_itens_servicos.valor_unitario',
            'os_itens_servicos.valor_total',
            'os_itens_servicos.garantia_ate',
            'servicos.nome'
        ])
            ->join('servicos', 'os_itens_servicos.servico_id', '=', 'servicos.id')
            ->where('os_itens_servicos.os_id', $os_id['id'])->get();

        $this->render('os_imprimir', [
            'os' => $os,
            'oficina' => $oficina,
            'produtos_os' => $produtos_os,
            'servicos_os' => $servicos_os
        ]);
    }
}
