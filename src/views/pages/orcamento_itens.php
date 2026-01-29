<?= $render('header') ?>
<?php if (!empty($mensagem)): ?>
    <div id="aviso" class="mensagem hide"><?= $mensagem ?></div>
    <script>
        const aviso = document.getElementById('aviso');
        aviso.classList.remove('hide');

        setTimeout(() => {
            aviso.classList.add('hide');
        }, 5000);
    </script>
<?php endif; ?>
<div class="page-header">
    <h1>📝 Orçamento #<?= str_pad($orcamento['id'], 4, '0', STR_PAD_LEFT) ?></h1>
    <p class="breadcrumb">
        <a href="orcamentos.php" style="color: #667eea;">Orçamentos</a> / Gerenciar Itens
    </p>
</div>

<div class="card">
    <h3 style="color: #2c3e50; margin-bottom: 15px;">Informações do Orçamento</h3>
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Cliente:</span>
            <span class="info-value"><?= htmlspecialchars($orcamento['cliente_nome']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Telefone:</span>
            <span class="info-value"><?= htmlspecialchars($orcamento['cliente_telefone']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Veículo:</span>
            <span class="info-value"><?= htmlspecialchars($orcamento['veiculo_marca'] . ' ' . $orcamento['veiculo_modelo'] . ' ' . $orcamento['veiculo_ano']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Data:</span>
            <span class="info-value"><?= date('d/m/Y', strtotime($orcamento['data_orcamento'])) ?></span>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px;">➕ Adicionar Produto/Peça</h3>
    <form method="POST" class="form-inline" action="/orcamentos/processar">
        <input type="hidden" name="acao" value="add_produto">
        <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
        <div class="form-group">
            <label>Produto do Estoque (opcional):</label>
            <select name="produto_id" onchange="preencherProduto(this)">
                <option value="">Selecione ou digite manualmente</option>
                <?php foreach ($produtos_estoque as $p): ?>
                    <option value="<?= $p['id'] ?>" data-desc="<?= htmlspecialchars($p['descricao']) ?>" data-preco="<?= $p['preco_venda'] ?>">
                        <?= htmlspecialchars($p['descricao']) ?> - <?= $p['preco_venda'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="flex: 2;">
            <label>Descrição: *</label>
            <input type="text" name="descricao" id="desc_produto" required>
        </div>
        <div class="form-group">
            <label>Qtd: *</label>
            <input type="number" name="quantidade" min="1" value="1" required>
        </div>
        <div class="form-group">
            <label>Valor Unit. (R$): *</label>
            <input type="number" name="valor_unitario" id="valor_produto" step="0.01" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px;">➕ Adicionar Serviço</h3>
    <form method="POST" class="form-inline" action="/orcamentos/processar">
        <input type="hidden" name="acao" value="add_servico">
        <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
        <div class="form-group">
            <label>Serviço Cadastrado (opcional):</label>
            <select name="servico_id" onchange="preencherServico(this)">
                <option value="">Selecione ou digite manualmente</option>
                <?php foreach ($servicos_cadastrados as $s): ?>
                    <option value="<?= $s['id'] ?>" data-desc="<?= htmlspecialchars($s['nome']) ?>" data-preco="<?= $s['valor'] ?>">
                        <?= htmlspecialchars($s['nome']) ?> - <?= $s['valor'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="flex: 2;">
            <label>Descrição: *</label>
            <input type="text" name="descricao" id="desc_servico" required>
        </div>
        <div class="form-group">
            <label>Qtd: *</label>
            <input type="number" name="quantidade" min="1" value="1" required>
        </div>
        <div class="form-group">
            <label>Valor Unit. (R$): *</label>
            <input type="number" name="valor_unitario" id="valor_servico" step="0.01" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px;">📦 Produtos/Peças</h3>
    <table>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Qtd</th>
                <th>Valor Unit.</th>
                <th>Total</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['descricao']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                    <td>
                        <form method="POST" style="display: inline;" action="/orcamentos/processar">
                            <input type="hidden" name="acao" value="remover_produto">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remover?')">🗑️</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($produtos)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #999;">Nenhum produto adicionado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px;">🔨 Serviços</h3>
    <table>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Qtd</th>
                <th>Valor Unit.</th>
                <th>Total</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicos as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['descricao']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                    <td>
                        <form method="POST" style="display: inline;" action="/orcamentos/processar">
                            <input type="hidden" name="acao" value="remover_servico">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remover?')">🗑️</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($servicos)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #999;">Nenhum serviço adicionado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px;">💰 Resumo Financeiro</h3>

    <form method="POST" style="margin-bottom: 20px;" action="/orcamentos/processar">
        <input type="hidden" name="acao" value="atualizar_valores">
        <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
        <div class="form-inline">
            <div class="form-group">
                <label>Desconto (R$):</label>
                <input type="number" name="desconto" step="0.01" min="0" value="<?= $orcamento['desconto'] ?>">
            </div>
            <button type="submit" class="btn btn-success">Atualizar Valores</button>
        </div>
    </form>

    <div class="totais">
        <div class="total-item">
            <span>Produtos/Peças:</span>
            <strong>R$ <?= number_format($orcamento['valor_pecas'], 2, ',', '.') ?></strong>
        </div>
        <div class="total-item">
            <span>Serviços:</span>
            <strong>R$ <?= number_format($orcamento['valor_servicos'], 2, ',', '.') ?></strong>
        </div>
        <div class="total-item">
            <span>Desconto:</span>
            <strong>- R$ <?= number_format($orcamento['desconto'], 2, ',', '.') ?></strong>
        </div>
        <div class="total-item total-final">
            <span>TOTAL:</span>
            <strong>R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></strong>
        </div>
    </div>
</div>

<div style="text-align: center; margin-top: 30px;">
    <a href="/orcamentos/imprimir/<?= $orcamento['id'] ?>" class="btn btn-success" target="_blank" style="padding: 15px 40px; font-size: 1.1em;">
        🖨️ Imprimir Orçamento
    </a>
    <a href="/orcamentos" class="btn btn-secondary" style="padding: 15px 40px; font-size: 1.1em;">
        ← Voltar
    </a>

    <?php if ($orcamento['status'] === 'Pendente'): ?>
        <form method="POST" style="display: inline;" action="/orcamentos/processar">
            <input type="hidden" name="acao" value="mudar_status">
            <input type="hidden" name="novo_status" value="Agendado">
            <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
            <button type="submit" class="btn btn-sm" style="background: #43e97b; color: white;padding: 15px 40px; font-size: 1.1em;" title="Aprovar">✓ Agendar</button>
        </form>
    <?php endif; ?>
    <button type="button" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1em;" onclick='criarOs(<?= json_encode($orcamento) ?>)'>Criar Os</button>
</div>

<div id="modal" class="modal">
    <div class="modal-content" style="max-width: 900px; padding: 15px;">

        <div class="modal-header" style="padding-bottom: 8px; margin-bottom: 10px;">
            <h2 style="font-size: 18px;">Criar Os</h2>
            <button onclick="fecharModal()" class="btn-close">×</button>
        </div>

        <form method="POST" action="/orcamentos/processar">
            <input type="hidden" name="acao" id="acao" value="criarOs">
            <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
            <input type="hidden" name="cliente_id" id="cliente_id" value="">
            <input type="hidden" name="veiculo_id" id="veiculo_id" value="">
            <input type="hidden" name="cliente_novo" id="cliente_novo" value="Sim">

            <!-- CLIENTE -->
            <h3 style="font-size:14px;margin:10px 0 8px;border-bottom:1px solid #eee;">👤 Dados do Cliente</h3>

            <div class="form-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                <div class="form-group">
                    <label>Nome *</label>
                    <input type="text" name="cliente_nome" id="cliente_nome" required>
                </div>

                <div class="form-group">
                    <label>CPF *</label>
                    <input type="text" id="cliente_cpf" name="cliente_cpf" required>
                </div>

                <div class="form-group">
                    <label>Telefone *</label>
                    <input type="text" id="cliente_telefone" name="cliente_telefone" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="cliente_email" name="cliente_email">
                </div>
                <div class="form-group">
                    <label>Endereço</label>
                    <input type="text" id="cliente_endereco" name="cliente_endereco">
                </div>
            </div>

            <!-- VEÍCULO -->
            <h3 style="font-size:14px;margin:12px 0 8px;border-bottom:1px solid #eee;">🚗 Dados do Veículo</h3>

            <div class="form-grid" style="display:flex;">
                <div class="form-group">
                    <label>Marca *</label>
                    <select name="veiculo_marca" id="veiculo_marca">
                        <option value="">Selecione uma marca</option>
                        <option value="Audi">Audi</option>
                        <option value="Bmw">BMW</option>
                        <option value="Byd">BYD</option>
                        <option value="Chery">Chery</option>
                        <option value="Chevrolet">Chevrolet</option>
                        <option value="Citroen">Citroën</option>
                        <option value="Dodge">Dodge</option>
                        <option value="Ferrari">Ferrari</option>
                        <option value="Fiat">Fiat</option>
                        <option value="Ford">Ford</option>
                        <option value="Gwm">GWM</option>
                        <option value="Honda">Honda</option>
                        <option value="Hyundai">Hyundai</option>
                        <option value="Jac">JAC</option>
                        <option value="Jeep">Jeep</option>
                        <option value="Kia">Kia</option>
                        <option value="Land-rover">Land Rover</option>
                        <option value="Lexus">Lexus</option>
                        <option value="Mercedes-benz">Mercedes-Benz</option>
                        <option value="Mitsubishi">Mitsubishi</option>
                        <option value="Nissan">Nissan</option>
                        <option value="Peugeot">Peugeot</option>
                        <option value="Porsche">Porsche</option>
                        <option value="Ram">RAM</option>
                        <option value="Renault">Renault</option>
                        <option value="Subaru">Subaru</option>
                        <option value="Suzuki">Suzuki</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Volkswagen">Volkswagen</option>
                        <option value="Volvo">Volvo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Modelo *</label>
                    <input type="text" name="veiculo_modelo" id="veiculo_modelo" required>
                </div>

                <div class="form-group" style="max-width: 70px;">
                    <label>Ano *</label>
                    <input type="text" name="veiculo_ano" id="veiculo_ano" maxlength="4" required>
                </div>

                <div class="form-group" style="max-width: 120px;">
                    <label>Placa</label>
                    <input type="text" name="veiculo_placa" id="veiculo_placa">
                </div>
                <div class="form-group" style="max-width: 90px;">
                    <label>KM Atual:</label>
                    <input type="text" name="veiculo_km" id="veiculo_km">
                </div>
            </div>

            <!-- ORÇAMENTO -->
            <h3 style="font-size:14px;margin:12px 0 8px;border-bottom:1px solid #eee;">📋 Informações do Orçamento</h3>

            <div class="form-group">
                <label>Descrição do Serviço *</label>
                <textarea name="descricao_servico" id="descricao_servico" rows="2" required></textarea>
            </div>

            <div class="form-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;">
                <div class="form-group">
                    <label>Data *</label>
                    <input type="date" name="data_orcamento" id="data_orcamento">
                </div>

                <div class="form-group">
                    <label>Validade (dias)</label>
                    <input type="number" name="validade_dias" id="validade" value="7" min="1">
                </div>
            </div>

            <div class="form-group">
                <label>Observações</label>
                <textarea name="observacoes" id="observacoes" rows="2"></textarea>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer" style="margin-top:10px;padding-top:8px;">
                <button type="submit" class="btn btn-primary">Criar Os</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
            </div>

        </form>
    </div>
</div>


<script>
    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function criarOs(user) {
        console.log(user);
        const input_cpf = document.getElementById('cliente_cpf');
        var cpf = '';

        document.getElementById('modal').style.display = 'block';

        document.getElementById('acao').value = 'criarOs';

        document.getElementById('cliente_nome').value = user.cliente_nome || '';
        document.getElementById('cliente_cpf').value = user.cliente_cpf || '';
        document.getElementById('cliente_telefone').value = user.cliente_telefone || '';
        document.getElementById('cliente_email').value = user.cliente_email || '';
        document.getElementById('cliente_endereco').value = user.cliente_endereco || '';
        document.getElementById('veiculo_marca').value = user.veiculo_marca;
        document.getElementById('veiculo_modelo').value = user.veiculo_modelo;
        document.getElementById('veiculo_ano').value = user.veiculo_ano;
        document.getElementById('veiculo_placa').value = user.veiculo_placa;
        document.getElementById('veiculo_km').value = user.veiculo_km_atual || '';
        document.getElementById('descricao_servico').value = user.descricao_servico;
        document.getElementById('data_orcamento').value = user.data_orcamento;
        document.getElementById('validade').value = user.validade_dias;
        document.getElementById('observacoes').value = user.observacoes;

        input_cpf.addEventListener('input', () => {

            if (input_cpf.value.length >= 11) {
                cpf = input_cpf.value;

                if (input_cpf.value.length > 11) {
                    cpf = input_cpf.value.replace([',', '.', '-', '_', ' ']);
                }

                fetch(`/orcamentos/procurarCliente/${cpf}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao pesquisar: ' + response.status);
                        }

                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('cliente_id').value = data.cliente_id;
                        document.getElementById('cliente_nome').value = data.cliente_nome;
                        document.getElementById('cliente_cpf').value = data.cpf;
                        document.getElementById('cliente_telefone').value = data.telefone;
                        document.getElementById('cliente_email').value = data.email;
                        document.getElementById('cliente_endereco').value = data.endereco || '';
                        document.getElementById('veiculo_marca').value = data.marca;
                        document.getElementById('veiculo_modelo').value = data.modelo;
                        document.getElementById('veiculo_ano').value = data.ano;
                        document.getElementById('veiculo_placa').value = data.placa;
                        document.getElementById('cliente_novo').value = 'Nao';
                    })
                    .catch(Error => {
                        console.log(Error);
                    });
            }

        });
    }

    window.onclick = function(event) {
        if (event.target === document.getElementById('modal')) {
            fecharModal();
        }
    }

    function preencherProduto(select) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            document.getElementById('desc_produto').value = option.dataset.desc;
            document.getElementById('valor_produto').value = option.dataset.preco;
        }
    }

    function preencherServico(select) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            document.getElementById('desc_servico').value = option.dataset.desc;
            document.getElementById('valor_servico').value = option.dataset.preco;
        }
    }
</script>
<?= $render('footer') ?>