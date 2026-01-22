<?= $render('header') ?>

<div class="page-header">
    <h1>📝 Orçamento #<?= str_pad($orcamento['id'], 4, '0', STR_PAD_LEFT) ?></h1>
    <p class="breadcrumb">
        <a href="orcamentos.php" style="color: #667eea;">Orçamentos</a> / Gerenciar Itens
    </p>
</div>

<?php if (!empty($mensagem)): ?>
    <div class="alert alert-success"><?= $mensagem ?></div>
<?php endif; ?>

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
    <a href="orcamentos" class="btn btn-secondary" style="padding: 15px 40px; font-size: 1.1em;">
        ← Voltar
    </a>

    <?php if ($orcamento['status'] === 'Pendente'): ?>
        <form method="POST" style="display: inline;" action="/orcamentos/processar">
            <input type="hidden" name="acao" value="mudar_status">
            <input type="hidden" name="novo_status" value="Aprovado">
            <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
            <button type="submit" class="btn btn-sm" style="background: #43e97b; color: white;padding: 15px 40px; font-size: 1.1em;" title="Aprovar">✓ Aprovar</button>
        </form>
        <form method="POST" style="display: inline;" action="/orcamentos/processar">
            <input type="hidden" name="acao" value="mudar_status">
            <input type="hidden" name="novo_status" value="Recusado">
            <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
            <button type="submit" class="btn btn-sm btn-danger" style="padding: 15px 40px; font-size: 1.1em;" title="Recusar">✗ Recusar</button>
        </form>
    <?php endif; ?>

    <form method="POST" style="display: inline;" action="/orcamentos/processar" onsubmit="return confirm('Excluir este orçamento?')">
        <input type="hidden" name="acao" value="excluir">
        <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">
        <button type="submit" class="btn btn-sm btn-danger" style="padding: 15px 40px; font-size: 1.1em;">🗑️ Excluir</button>
    </form>

</div>


<script>
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