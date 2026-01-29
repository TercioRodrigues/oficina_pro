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
<header>
    <h1>🛒 Gestão de Compras</h1>
    <div>
        <a href="/dashboard" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Nova Compra</button>
    </div>
</header>

<div class="card">
    <h2 style="margin-bottom: 20px;">Lista de Compras</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>NF</th>
                <th>Fornecedor</th>
                <th>Valor Total</th>
                <th>Observações</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($compras as $compra): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($compra['data_compra'])) ?></td>
                    <td><?= htmlspecialchars($compra['numero_nf']) ?></td>
                    <td><?= htmlspecialchars($compra['nome_empresa']) ?></td>
                    <td style="font-weight: 600;">R$ <?= number_format($compra['valor_total'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($compra['observacoes']) ?></td>
                    <td>
                        <div class="actions">
                            <a href="compras/itens/<?= $compra['id'] ?>" class="btn btn-info">Ver Itens</a>
                            <form method="POST" style="display: inline;" action="/compras/processar">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="compra_id" value="<?= $compra['id'] ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Excluir esta compra?')">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($compras)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Nenhuma compra registrada</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h2>Registrar Nova Compra</h2>
        <form method="POST" action="/compras/processar">
            <input type="hidden" name="acao" value="cadastrar">

            <div class="form-group">
                <label>Fornecedor:</label>
                <select name="fornecedor_id" required>
                    <option value="">Selecione</option>
                    <?php foreach ($fornecedores as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Número da NF:</label>
                    <input type="text" name="numero_nf">
                </div>

                <div class="form-group">
                    <label>Data da Compra:</label>
                    <input type="date" name="data_compra" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Valor Total (R$):</label>
                    <input type="number" name="valor_total" value="0,00" step="0.01" min="0">
                </div>

                <div class="form-group">
                    <label>Forma de Pagamento:</label>
                    <select name="forma_pagamento" required>
                        <option value="">Selecione</option>
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Cartão Débito">Cartão Débito</option>
                        <option value="Cartão Crédito">Cartão Crédito</option>
                        <option value="PIX">PIX</option>
                        <option value="Transferência">Transferência</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Observações:</label>
                <textarea name="observacoes"></textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Salvar e Adicionar Itens</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModal() {
        document.getElementById('modal').style.display = 'block';
        document.querySelector('form').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>