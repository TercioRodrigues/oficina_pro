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
    <h1>📦 Gestão de Estoque</h1>
    <div>
        <a href="index.php" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Produto</button>
        <a href="/estoque/categorias" class="btn btn-primary">Categorias</a>
    </div>
</header>

<div class="card">
    <h2 style="margin-bottom: 20px;">Lista de Produtos</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Qtd</th>
                <th>Preço Custo</th>
                <th>Preço Venda</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['codigo']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    <td><?= htmlspecialchars($produto['categoria']) ?></td>
                    <td><?= $produto['quantidade'] ?></td>
                    <td>R$ <?= number_format($produto['preco_custo'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($produto['preco_venda'], 2, ',', '.') ?></td>
                    <td>
                        <?php
                        if ($produto['quantidade'] <= 0) {
                            echo '<span class="badge badge-pendente">SEM ESTOQUE</span>';
                        } elseif ($produto['quantidade'] <= $produto['estoque_minimo']) {
                            echo '<span class="badge badge-info">ESTOQUE BAIXO</span>';
                        } else {
                            echo '<span class="badge badge-ativo">OK</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-success" onclick='editarProduto(<?= json_encode($produto) ?>)'>Editar</button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir?')">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h2 id="modal-title">Cadastrar Produto</h2>
        <form method="POST" action="/estoque/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="produto-id">

            <div class="form-row">
                <div class="form-group">
                    <label>Código:</label>
                    <input type="text" name="codigo" id="codigo" placeholder="Ex: PEC001" required>
                </div>

                <div class="form-group">
                    <label>Categoria:</label>
                    <select name="categoria" id="categoria" required>
                        <option value="">Selecione</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id'] ?>"><?= $categoria['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Descrição:</label>
                <input type="text" name="descricao" id="descricao" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Quantidade:</label>
                    <input type="number" name="quantidade" id="quantidade" min="0" required>
                </div>

                <div class="form-group">
                    <label>Estoque Mínimo:</label>
                    <input type="number" name="estoque_minimo" id="estoque_minimo" min="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Preço de Custo (R$):</label>
                    <input type="number" name="preco_custo" id="preco_custo" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label>Preço de Venda (R$):</label>
                    <input type="number" name="preco_venda" id="preco_venda" step="0.01" min="0" required>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModal() {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Cadastrar Produto';
        document.getElementById('acao').value = 'cadastrar';
        document.querySelector('form').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarProduto(produto) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Produto';
        document.getElementById('acao').value = 'editar';
        document.getElementById('produto-id').value = produto.id;
        document.getElementById('codigo').value = produto.codigo;
        document.getElementById('descricao').value = produto.descricao;
        document.getElementById('categoria').value = produto.categoria_id;
        document.getElementById('quantidade').value = produto.quantidade;
        document.getElementById('preco_custo').value = produto.preco_custo;
        document.getElementById('preco_venda').value = produto.preco_venda;
        document.getElementById('estoque_minimo').value = produto.estoque_minimo;
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>