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
    <h1>📦 Categorias</h1>
    <div>
        <a href="/estoque" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Nova categoria</button>
    </div>
</header>

<div class="card">
    <h2 style="margin-bottom: 20px;">Categorias</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= htmlspecialchars($categoria['nome']) ?></td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-success" onclick='editarProduto(<?= json_encode($categoria) ?>)'>Editar</button>
                            <form method="POST" action="/estoque/processar" style="display: inline;" onsubmit="return confirm('Todos os produtos relacionados a essa categoria serão perdidos, Deseja realmente excluir?')">
                                <input type="hidden" name="acao" value="excluir_categoria">
                                <input type="hidden" name="categoria_id" value="<?= $categoria['id'] ?>">
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div id="modal" class="modal">
    <div class="modal-content">
        <h2 id="modal-title">Cadastrar Produto</h2>
        <form method="POST" action="/estoque/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar_categoria">
            <input type="hidden" name="categoria_id" id="categoria_id">

            <div class="form-row">
                <div class="form-group">
                    <label>Nome:*</label>
                    <input type="text" name="categoria_nome" id="categoria_nome" placeholder="Ex: Peças, Acessórios..." required>
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
        document.getElementById('modal-title').textContent = 'Cadastrar Categoria';
        document.getElementById('acao').value = 'cadastrar_categoria';
        document.querySelector('form').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarProduto(categoria) {
        console.log(categoria);
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Categoria';
        document.getElementById('acao').value = 'editar_categoria';
        document.getElementById('categoria_id').value = categoria.id;
        document.getElementById('categoria_nome').value = categoria.nome;
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>