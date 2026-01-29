<?php

namespace src\controllers;

use ClanCats\Hydrahon\Query\Sql\Func;
use \core\Controller;
use \core\Model;
use Exception;
use src\models\Login;
use src\models\Orcamentos;
use src\models\Estoque;
use src\models\Servicos;
use src\models\Orcamento_itens_servicos;
use src\models\Orcamento_itens_produtos;
use src\models\Empresas;
use src\models\Clientes;
use src\models\Veiculos;
use src\models\Ordens_servico;
use src\models\Os_itens_produtos;
use src\models\Os_itens_servicos;

class OrcamentosController extends Controller
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

        $orcamentos = Orcamentos::select([
            'orcamentos.id',
            'orcamentos.status',
            'orcamentos.data_orcamento',
            'orcamentos.data_validade',
            'orcamentos.cliente_nome',
            'orcamentos.cliente_telefone',
            'orcamentos.veiculo_marca',
            'orcamentos.veiculo_modelo',
            'orcamentos.veiculo_ano',
            'orcamentos.valor_total',
            'usuarios.nome as usuario_nome'
        ])->join('usuarios', 'orcamentos.usuario_id', '=', 'usuarios.id')
            ->where('status', 'Pendente')
            ->where('orcamentos.empresa_id', $_SESSION['empresa_id'])
            ->orderBy('orcamentos.data_orcamento', 'DESC')->get();




        $this->render('orcamentos', [
            'orcamentos' => $orcamentos,
            'mensagem' => $mensagem
        ]);
    }

    public function orcamentoItens()
    {
        $orcamento_id = filter_input(INPUT_GET, 'orcamento_id');
        if (empty($orcamento_id)) $this->redirect('/orcamentos');
        $mensagem = filter_input(INPUT_GET, 'msg') ?? '';

        $orcamento = Orcamentos::select()->where('id', $orcamento_id)->get();
        $orcamento = $orcamento[0];

        if (empty($orcamento)) {
            echo "<script>alert('Orçamento não encontrado!'); window.location.href=/orcamentos;</script>";
            exit;
        }

        $produtos_estoque = Estoque::select()
            ->where('quantidade', '>', 0)
            ->orderBy('descricao')->get();

        $servicos = Servicos::select()->orderBy('nome')->get();

        $itens_produtos = Orcamento_itens_produtos::select()->where('orcamento_id', $orcamento_id)->get();

        $itens_servicos = Orcamento_itens_servicos::select()->where('orcamento_id', $orcamento_id)->get();



        $this->render('orcamento_itens', [
            'orcamento' => $orcamento,
            'produtos_estoque' => $produtos_estoque,
            'servicos_cadastrados' => $servicos,
            'servicos' => $itens_servicos,
            'produtos' => $itens_produtos,
            'mensagem' => $mensagem
        ]);
    }

    public function procurarClientePorCpf($cpf = [])
    {
        $codigo = 404;
        $resposta = ['status' => 'nao encontrado'];
        $cliente = Clientes::select([
            'clientes.id as cliente_id',
            'clientes.nome as cliente_nome',
            'clientes.cpf',
            'clientes.telefone',
            'clientes.email',
            'clientes.endereco',
            'veiculos.id as veiculo_id',
            'veiculos.marca',
            'veiculos.modelo',
            'veiculos.ano',
            'veiculos.placa',
            'veiculos.chassi',
            'veiculos.km_atual'
        ])
            ->join('veiculos', 'clientes.id', '=', 'veiculos.cliente_id')
            ->where('clientes.cpf', $cpf['cpf'])
            ->where('clientes.empresa_id', $_SESSION['empresa_id'])->get();
        if (count($cliente) > 0) {
            $resposta = $cliente[0];
            $codigo = 200;
        }

        header('ContentType: application/json');
        http_response_code($codigo);
        echo json_encode($resposta);
    }

    public function processarAcoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $acao = filter_input(INPUT_POST, 'acao') ?? '';
            $orcamento_id = filter_input(INPUT_POST, 'orcamento_id');
            $desconto = filter_input(INPUT_POST, 'desconto') ?? 0;
            $item_id = filter_input(INPUT_POST, 'item_id');
            $novo_status = filter_input(INPUT_POST, 'novo_status');

            $validade_dias = filter_input(INPUT_POST, 'validade_dias') ?? 7;
            $data_orcamento = filter_input(INPUT_POST, 'data_orcamento');
            $data_validade = date('Y-m-d', strtotime($data_orcamento . " +{$validade_dias} days"));
            $cliente_id = filter_input(INPUT_POST, 'cliente_id');
            $cliente_novo = filter_input(INPUT_POST, 'cliente_novo');
            $cliente_nome = filter_input(INPUT_POST, 'cliente_nome');
            $cliente_cpf = filter_input(INPUT_POST, 'cliente_cpf');
            $cliente_telefone = filter_input(INPUT_POST, 'cliente_telefone');
            $cliente_email = filter_input(INPUT_POST, 'cliente_email', FILTER_VALIDATE_EMAIL);
            $cliente_endereco = filter_input(INPUT_POST, 'cliente_endereco');
            $veiculo_marca = filter_input(INPUT_POST, 'veiculo_marca');
            $veiculo_modelo = filter_input(INPUT_POST, 'veiculo_modelo');
            $veiculo_id = filter_input(INPUT_POST, 'veiculo_id');
            $veiculo_ano = filter_input(INPUT_POST, 'veiculo_ano');
            $veiculo_placa = filter_input(INPUT_POST, 'veiculo_placa');
            $veiculo_km = filter_input(INPUT_POST, 'veiculo_km');
            $descricao_servico = filter_input(INPUT_POST, 'descricao_servico');
            $obsercacoes = filter_input(INPUT_POST, 'observacoes');

            if ($acao === 'cadastrar') {


                $orcamento = Orcamentos::insert([
                    'cliente_nome' => $cliente_nome,
                    'cliente_telefone' => $cliente_telefone,
                    'cliente_email' => $cliente_email,
                    'veiculo_marca' => $veiculo_marca,
                    'veiculo_modelo' => $veiculo_modelo,
                    'veiculo_ano' => $veiculo_ano,
                    'veiculo_placa' => $veiculo_placa,
                    'descricao_servico' => $descricao_servico,
                    'observacoes' => $obsercacoes,
                    'validade_dias' => $validade_dias,
                    'usuario_id' => $_SESSION['usuario_id'],
                    'data_orcamento' => $data_orcamento,
                    'data_validade' => $data_validade,
                    'empresa_id' => $_SESSION['empresa_id']
                ])->execute();

                $this->redirect("/orcamentos/itens?orcamento_id=$orcamento");
                exit;
            } elseif ($acao === 'excluir') {


                Orcamentos::delete()->where('id', $orcamento_id)->execute();
                $mensagem = "Orçamento excluído!";
                $this->redirect('/orcamentos?msg=', $mensagem);
                exit;
            } elseif ($acao === 'mudar_status') {

                Orcamentos::update()->set('status', $novo_status)->where('id', $orcamento_id)->execute();
                $mensagem = "Status atualizado!";
                $this->redirect('/orcamentos?msg=', $mensagem);
            } elseif ($acao === 'add_produto') {

                $descricao = filter_input(INPUT_POST, 'descricao');
                $quantidade = filter_input(INPUT_POST, 'quantidade');
                $valor_unitario = filter_input(INPUT_POST, 'valor_unitario');
                $valor_total = $quantidade * $valor_unitario;
                $produto_id = $_POST['produto_id'] ?? null;


                Orcamento_itens_produtos::insert([
                    'orcamento_id' => $orcamento_id,
                    'produto_id' => $produto_id,
                    'descricao' => $descricao,
                    'quantidade' => $quantidade,
                    'valor_unitario' => $valor_unitario,
                    'valor_total' => $valor_total
                ])->execute();

                $mensagem = "Produto adicionado!";
                $this->redirect("/orcamentos/itens?orcamento_id=$orcamento_id&msg=$mensagem");
            } elseif ($acao === 'add_servico') {
                $descricao = filter_input(INPUT_POST, 'descricao');
                $quantidade = filter_input(INPUT_POST, 'quantidade');
                $valor_unitario = filter_input(INPUT_POST, 'valor_unitario');
                $valor_total = $quantidade * $valor_unitario;
                $servico_id = filter_input(INPUT_POST, 'servico_id')  ?? null;

                Orcamento_itens_servicos::insert([
                    'orcamento_id' => $orcamento_id,
                    'servico_id' => $servico_id,
                    'descricao' => $descricao,
                    'quantidade' => $quantidade,
                    'valor_unitario' => $valor_unitario,
                    'valor_total' => $valor_total
                ])->execute();

                $mensagem = "Serviço adicionado!";
                $this->redirect("/orcamentos/itens?orcamento_id=$orcamento_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'remover_produto') {


                Orcamento_itens_produtos::delete()->where('id', $item_id)->execute();

                $mensagem = "Produto removido!";
                $this->redirect("/orcamentos/itens?orcamento_id=$orcamento_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'remover_servico') {

                Orcamento_itens_servicos::delete()->where('id', $item_id)->execute();

                $mensagem = "Serviço removido!";
                $this->redirect("/orcamentos/itens?orcamento_id=$orcamento_id&msg=$mensagem");
                exit;
            } elseif ($acao === 'atualizar_valores') {

                $valor_pecas = Orcamento_itens_produtos::select()->addField(new Func('sum', 'valor_total'), 'total')->where('orcamento_id', $orcamento_id)->execute();
                $valor_pecas = $valor_pecas[0]['total'] ?? 0;

                $valor_servicos = Orcamento_itens_servicos::select()->addField(new Func('sum', 'valor_total'), 'total')->where('orcamento_id', $orcamento_id)->execute();
                $valor_servicos = $valor_servicos[0]['total'] ?? 0;

                $valor_total = ($valor_pecas + $valor_servicos) - $desconto;

                Orcamentos::update()
                    ->set('valor_pecas', $valor_pecas)
                    ->set('valor_servicos', $valor_servicos)
                    ->set('desconto', $desconto)
                    ->set('valor_total', $valor_total)->where('id', $orcamento_id)->execute();

                $mensagem = "Valores atualizados!";
                $this->redirect("/orcamentos/itens?orcamento_id=$orcamento_id&msg=$mensagem");
            } elseif ($acao == 'criarOs') {

                $valorTotal = Orcamentos::select(['valor_total', 'valor_servicos', 'valor_pecas'])->where('id', $orcamento_id)->get();


                if ($cliente_novo == 'Sim') {


                    Model::beginTransaction();
                    try {

                        $cliente_id = Clientes::insert([
                            'nome' => $cliente_nome,
                            'cpf' => $cliente_cpf,
                            'telefone' => $cliente_telefone,
                            'email' => $cliente_email,
                            'endereco' => $cliente_endereco,
                            'empresa_id' => $_SESSION['empresa_id']
                        ])->execute();

                        $placa = Veiculos::select(['id', 'placa'])
                            ->where('empresa_id', $_SESSION['empresa_id'])
                            ->where('placa', $veiculo_placa)->get();
                        if (count($placa) > 0) {

                            Veiculos::update([
                                'cliente_id' => $cliente_id,
                                'placa' => $veiculo_placa,
                                'marca' => $veiculo_marca,
                                'modelo' => $veiculo_modelo,
                                'ano' => $veiculo_ano,
                                'km_atual' => $veiculo_km
                            ])->where('id', $placa[0]['id'])->execute();
                        }

                        $veiculo_id = Veiculos::insert([
                            'cliente_id' => $cliente_id,
                            'placa' => $veiculo_placa,
                            'marca' => $veiculo_marca,
                            'modelo' => $veiculo_modelo,
                            'ano' => $veiculo_ano,
                            'km_atual' => $veiculo_km,
                            'empresa_id' => $_SESSION['empresa_id']
                        ])->execute();

                        $os_id = Ordens_servico::insert([
                            'cliente_id' => $cliente_id,
                            'veiculo_id' => $veiculo_id,
                            'data_abertura' => date('Y-m-d'),
                            'status' => 'Aberta',
                            'descricao_problema' => $descricao_servico,
                            'observacoes' => $obsercacoes,
                            'valor_total' => $valorTotal[0]['valor_total'],
                            'valor_servicos' => $valorTotal[0]['valor_servicos'],
                            'valor_pecas' => $valorTotal[0]['valor_pecas'],
                            'usuario_id' => $_SESSION['usuario_id'],
                            'empresa_id' => $_SESSION['empresa_id']
                        ])->execute();



                        $produtos = Orcamento_itens_produtos::select()->where('orcamento_id', $orcamento_id)->get();
                        $servicos = Orcamento_itens_servicos::select()->where('orcamento_id', $orcamento_id)->get();
                        foreach ($produtos as $produto) {
                            Os_itens_produtos::insert([
                                'os_id' => $os_id,
                                'produto_id' => $produto['produto_id'],
                                'quantidade' => $produto['quantidade'],
                                'valor_unitario' => $produto['valor_unitario'],
                                'valor_total' => $produto['valor_total']
                            ])->execute();
                        }

                        foreach ($servicos as $servico) {
                            Os_itens_servicos::insert([
                                'os_id' => $os_id,
                                'servico_id' => $servico['servico_id'],
                                'quantidade' => $servico['quantidade'],
                                'valor_unitario' => $servico['valor_unitario'],
                                'valor_total' => $servico['valor_total']
                            ])->execute();

                            Orcamentos::update([
                                'status' => 'Aprovado'
                            ])->where('id', $orcamento_id)->execute();
                        }
                        Model::commit();
                        $mensagem = "Ordem de serviço cadastrada com sucesso!";
                    } catch (Exception $e) {
                        Model::rollBack();
                        $mensagem = $e->getMessage();
                    }


                    $this->redirect("/Os?msg=$mensagem");
                    exit;
                } else {

                    Model::beginTransaction();
                    try {

                        $placa = Veiculos::select(['id', 'placa'])
                            ->where('empresa_id', $_SESSION['empresa_id'])
                            ->where('placa', $veiculo_placa)->get();
                        if (count($placa) > 0) {

                            Veiculos::update([
                                'cliente_id' => $cliente_id,
                                'placa' => $veiculo_placa,
                                'marca' => $veiculo_marca,
                                'modelo' => $veiculo_modelo,
                                'ano' => $veiculo_ano,
                                'km_atual' => $veiculo_km
                            ])->where('id', $placa[0]['id'])->execute();
                        }

                        $veiculo_id = Veiculos::insert([
                            'cliente_id' => $cliente_id,
                            'placa' => $veiculo_placa,
                            'marca' => $veiculo_marca,
                            'modelo' => $veiculo_modelo,
                            'ano' => $veiculo_ano,
                            'km_atual' => $veiculo_km,
                            'empresa_id' => $_SESSION['empresa_id']
                        ])->execute();

                        $os_id = Ordens_servico::insert([
                            'cliente_id' => $cliente_id,
                            'veiculo_id' => $veiculo_id,
                            'data_abertura' => date('Y-m-d'),
                            'status' => 'Aberta',
                            'descricao_problema' => $descricao_servico,
                            'observacoes' => $obsercacoes,
                            'valor_total' => $valorTotal[0]['valor_total'],
                            'valor_servicos' => $valorTotal[0]['valor_servicos'],
                            'valor_pecas' => $valorTotal[0]['valor_pecas'],
                            'usuario_id' => $_SESSION['usuario_id'],
                            'empresa_id' => $_SESSION['empresa_id']
                        ])->execute();



                        $produtos = Orcamento_itens_produtos::select()->where('orcamento_id', $orcamento_id)->get();
                        $servicos = Orcamento_itens_servicos::select()->where('orcamento_id', $orcamento_id)->get();
                        foreach ($produtos as $produto) {
                            Os_itens_produtos::insert([
                                'os_id' => $os_id,
                                'produto_id' => $produto['produto_id'],
                                'quantidade' => $produto['quantidade'],
                                'valor_unitario' => $produto['valor_unitario'],
                                'valor_total' => $produto['valor_total']
                            ])->execute();
                        }

                        foreach ($servicos as $servico) {
                            Os_itens_servicos::insert([
                                'os_id' => $os_id,
                                'servico_id' => $servico['servico_id'],
                                'quantidade' => $servico['quantidade'],
                                'valor_unitario' => $servico['valor_unitario'],
                                'valor_total' => $servico['valor_total']
                            ])->execute();

                            Orcamentos::update([
                                'status' => 'Aprovado'
                            ])->where('id', $orcamento_id)->execute();
                        }
                        Model::commit();
                        $mensagem = "Ordem de serviço cadastrada com sucesso!";
                    } catch (Exception $e) {
                        Model::rollBack();
                        $mensagem = $e->getMessage();
                    }
                    $this->redirect("/Os?msg=$mensagem");
                    exit;
                }
            }
        }
    }

    public function Imprimir($id = [])
    {
        $orcamento = Orcamentos::select()->where('id', $id['id'])->get();
        $empresa = Empresas::select()->get();
        $produtos = Orcamento_itens_produtos::select()->where('orcamento_id', $id['id'])->get();
        $servicos = Orcamento_itens_servicos::select()->where('orcamento_id', $id['id'])->get();

        $this->render('imprimir_orcamento', [
            'orcamento' => $orcamento[0],
            'config' => $empresa[0],
            'produtos_orc' => $produtos,
            'servicos_orc' => $servicos,
            'orcamento_id' => $id['id']
        ]);
    }
}
