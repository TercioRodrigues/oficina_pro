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
    <h1>👥 Gestão de Clientes</h1>
    <div>
        <a href="index.php" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Cliente</button>
    </div>
</header>

<div class="card">
    <h2 style="margin-bottom: 20px;">Lista de Clientes</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nome']) ?></td>
                    <td><?= htmlspecialchars($cliente['cpf']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-success" onclick='editarCliente(<?= json_encode($cliente) ?>)'>Editar</button>
                            <form method="POST" action="/clientes/processar" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir?')">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
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
        <h2 id="modal-title">Cadastrar Cliente</h2>
        <form method="POST" action="/clientes/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="cliente-id">

            <div class="form-group">
                <label>Nome Completo:</label>
                <input type="text" name="nome" id="nome" required>
            </div>

            <div class="form-group">
                <label>CPF:</label>
                <input type="text" name="cpf" id="cpf" required>
            </div>

            <div class="form-group">
                <label>Telefone:</label>
                <input type="text" name="telefone" id="telefone" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="email">
            </div>

            <div class="form-group">
                <label>Endereço:</label>
                <textarea name="endereco" id="endereco"></textarea>
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
        document.getElementById('modal-title').textContent = 'Cadastrar Cliente';
        document.getElementById('acao').value = 'cadastrar';
        document.querySelector('form').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarCliente(cliente) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Cliente';
        document.getElementById('acao').value = 'editar';
        document.getElementById('cliente-id').value = cliente.id;
        document.getElementById('nome').value = cliente.nome;
        document.getElementById('cpf').value = cliente.cpf;
        document.getElementById('telefone').value = cliente.telefone;
        document.getElementById('email').value = cliente.email || '';
        document.getElementById('endereco').value = cliente.endereco || '';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>