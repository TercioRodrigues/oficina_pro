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
<div class="header">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1>🛒 Itens da Compra</h1>
        </div>
        <div>
            <a href="/compras" class="btn btn-secondary">← Voltar</a>
        </div>
    </div>

    <div class="os-info">
        <div class="info-item">
            <span class="info-label">Fornecedor</span>
            <span class="info-value"><?= htmlspecialchars($compra['nome_empresa']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">NF</span>
            <span class="info-value"><?= htmlspecialchars($compra['numero_nf']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Data</span>
            <span class="info-value"><?= date('d/m/Y', strtotime($compra['data_compra'])) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Valor Total</span>
            <span class="info-value">R$ <?= number_format($compra['valor_total'], 2, ',', '.') ?></span>
        </div>
    </div>
</div>

<!-- Adicionar Item -->
<?php if ($compra['status'] == 'Pendente'): ?>
    <div class="card">
        <h3 style="margin-bottom: 15px;">➕ Adicionar Produto</h3>
        <form method="POST" class="form-inline" action="/compras/processar">
            <input type="hidden" name="acao" value="add_item">
            <input type="hidden" name="compra_id" value="<?= $compra_id ?>">
            <div class="form-group" style="flex: 2;">
                <label>Produto:</label>
                <select name="produto_id" required>
                    <option value="">Selecione</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['codigo'] . ' - ' . $p['descricao']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Quantidade:</label>
                <input type="number" name="quantidade" min="1" value="1" required>
            </div>
            <div class="form-group">
                <label>Valor Unit. (R$):</label>
                <input type="number" name="valor_unitario" step="0.01" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
    </div>
<?php endif; ?>

<!-- Lista de Itens -->
<div class="card">
    <h3 style="margin-bottom: 15px;">📦 Produtos da Compra</h3>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Valor Unit.</th>
                <th>Total</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_geral = 0;
            foreach ($itens as $item):
                $total_geral += $item['valor_total'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['codigo']) ?></td>
                    <td><?= htmlspecialchars($item['descricao']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                    <td>
                        <?php if ($compra['status'] == 'Pendente'): ?>
                            <form method="POST" style="display: inline;" action="/compras/processar">
                                <input type="hidden" name="acao" value="remover_item">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="compra_id" value="<?= $compra_id ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Remover?')">Remover</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($itens)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Nenhum item adicionado</td>
                </tr>
            <?php else: ?>
                <tr class="totais">
                    <td class="total-item total-final">TOTAL DOS ITENS:
                        <strong>R$ <?= number_format($total_geral, 2, ',', '.') ?></strong>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($compra['status'] == 'Pendente'): ?>

    <div class="card">
        <h3 style="margin-bottom: 15px;">✅ Finalizar compra</h3>
        <p style="margin-bottom: 15px; color: #666;">
            Ao finalizar, os produtos serão adicionados no estoque e a saida será registrada no caixa.
        </p>
        <form method="POST" action="/compras/processar" onsubmit="return confirm('Confirmar compra?')">
            <input type="hidden" name="acao" value="finalizar_compra">
            <input type="hidden" name="compra_id" value="<?= $compra_id ?>">
            <button type="submit" class="btn btn-success">✓ Concluir Compra</button>
        </form>
    </div>
<?php endif; ?>

<?= $render('footer') ?>