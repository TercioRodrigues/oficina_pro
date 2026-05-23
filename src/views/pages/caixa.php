<?= $render('header') ?>
<?php if (!empty($_SESSION['mensagem'])): ?>
    <div id="aviso" class="mensagem hide">
        <?php echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);  ?></div>
    <script>
        const aviso = document.getElementById('aviso');
        aviso.classList.remove('hide');

        setTimeout(() => {
            aviso.classList.add('hide');
        }, 5000);
    </script>
<?php endif; ?>
<header>
    <h1>💰 Controle de Caixa</h1>
    <div>
        <a href="index.php" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Lançamento</button>
    </div>
</header>

<!-- Resumo -->
<div class="resumo-grid">
    <div class="resumo-card entradas">
        <div class="resumo-label">💚 Total de Entradas</div>
        <div class="resumo-valor">R$ <?= number_format($total_entradas, 2, ',', '.') ?></div>
    </div>
    <div class="resumo-card saidas">
        <div class="resumo-label">❤️ Total de Saídas</div>
        <div class="resumo-valor">R$ <?= number_format($total_saidas, 2, ',', '.') ?></div>
    </div>
    <div class="resumo-card saldo">
        <div class="resumo-label">💎 Saldo do Período</div>
        <div class="resumo-valor">R$ <?= number_format($saldo, 2, ',', '.') ?></div>
    </div>
</div>

<!-- Filtros -->
<div class="card">
    <h3 style="margin-bottom: 15px;">🔍 Filtros</h3>
    <form method="GET" class="filtros" action="/caixa">
        <div class="form-group">
            <label>Data Início:</label>
            <input type="date" name="data_inicio" value="<?= $data_inicio ?>">
        </div>
        <div class="form-group">
            <label>Data Fim:</label>
            <input type="date" name="data_fim" value="<?= $data_fim ?>">
        </div>
        <div class="form-group">
            <label>Tipo:</label>
            <select name="tipo">
                <option value="">Todos</option>
                <option value="Entrada" <?= $tipo_filtro === 'Entrada' ? 'selected' : '' ?>>Entradas</option>
                <option value="Saída" <?= $tipo_filtro === 'Saída' ? 'selected' : '' ?>>Saídas</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Filtrar</button>
    </form>
</div>

<!-- Movimentações -->
<div class="card">
    <h3 style="margin-bottom: 20px;">📊 Movimentações</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Categoria</th>
                <th>Descrição</th>
                <th>Forma Pgto</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimentacoes as $mov): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($mov['data_movimentacao'])) ?></td>
                    <td>
                        <span class="badge-<?= strtolower($mov['tipo']) ?>">
                            <?= $mov['tipo'] ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($mov['categoria']) ?></td>
                    <td><?= htmlspecialchars($mov['descricao']) ?></td>
                    <td><?= htmlspecialchars($mov['forma_pagamento']) ?></td>
                    <td style="font-weight: 600; color: <?= $mov['tipo'] === 'Entrada' ? '#2f9e44' : '#c92a2a' ?>">
                        <?= $mov['tipo'] === 'Entrada' ? '+' : '-' ?> R$ <?= number_format($mov['valor'], 2, ',', '.') ?>
                    </td>
                    <td>

                        <!-- <form method="POST" action="/caixa/processar" style="display: inline;">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $mov['id'] ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Excluir este lançamento?')">Excluir</button> -->
                        </form>

                        <span style="color: #999; font-size: 0.9em;">Automático</span>

                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($movimentacoes)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Nenhuma movimentação encontrada</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h2>Novo Lançamento</h2>
        <form method="POST" action="/caixa/processar">
            <input type="hidden" name="acao" value="lancar">

            <div class="form-row">
                <div class="form-group">
                    <label>Tipo:</label>
                    <select name="tipo" id="tipo" required onchange="atualizarCategorias()">
                        <option value="">Selecione</option>
                        <option value="Entrada">Entrada</option>
                        <option value="Saída">Saída</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Categoria:</label>
                    <select name="categoria" id="categoria" required>
                        <option value="">Selecione o tipo primeiro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Descrição:</label>
                <input type="text" name="descricao" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Valor (R$):</label>
                    <input type="number" name="valor" step="0.01" min="0" required>
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
                <label>Data:</label>
                <input type="date" name="data_movimentacao" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const categoriasEntrada = [
        'Serviços',
        'Venda de Produtos',
        'Outros Recebimentos'
    ];

    const categoriasSaida = [
        'Compra de Produtos',
        'Aluguel',
        'Energia',
        'Água',
        'Internet',
        'Telefone',
        'Salários',
        'Impostos',
        'Manutenção',
        'Outros Gastos'
    ];

    function atualizarCategorias() {
        const tipo = document.getElementById('tipo').value;
        const categoriaSelect = document.getElementById('categoria');

        categoriaSelect.innerHTML = '<option value="">Selecione</option>';

        const categorias = tipo === 'Entrada' ? categoriasEntrada : categoriasSaida;

        categorias.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat;
            option.textContent = cat;
            categoriaSelect.appendChild(option);
        });
    }

    function abrirModal() {
        document.getElementById('modal').style.display = 'block';
        document.querySelector('form').reset();
        document.getElementById('categoria').innerHTML = '<option value="">Selecione o tipo primeiro</option>';
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