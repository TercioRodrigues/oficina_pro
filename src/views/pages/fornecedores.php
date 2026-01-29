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
    <h1>🏢 Gestão de Fornecedores</h1>
    <div>
        <a href="index.php" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Fornecedor</button>
    </div>
</header>

<div class="card">
    <h2 style="margin-bottom: 20px;">Lista de Fornecedores</h2>
    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>CNPJ</th>
                <th>Contato</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Produtos</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fornecedores as $fornecedor): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($fornecedor['nome_empresa']) ?></strong></td>
                    <td><?= htmlspecialchars($fornecedor['cnpj']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['contato']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['email']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['produtos_fornecidos']) ?></td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-success" onclick='editarFornecedor(<?= json_encode($fornecedor) ?>)'>Editar</button>
                            <form method="POST" action="/fornecedores/processar" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir?')">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $fornecedor['id'] ?>">
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
        <h2 id="modal-title">Cadastrar Fornecedor</h2>
        <form method="POST" action="/fornecedores/processar" id="form">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="fornecedor-id">

            <div class="form-group">
                <label>Nome da Empresa:</label>
                <input type="text" name="nome_empresa" id="nome_empresa" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>CNPJ:</label>
                    <input type="text" name="cnpj" id="cnpj" required>
                </div>

                <div class="form-group">
                    <label>Nome do Contato:</label>
                    <input type="text" name="contato" id="contato" placeholder="Ex: Tércio Rodrigues" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Telefone:</label>
                    <input type="text" name="telefone" id="telefone" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" id="email">
                </div>
            </div>

            <div class="form-group">
                <label>Endereço:</label>
                <textarea name="endereco" id="endereco"></textarea>
            </div>

            <div class="form-group">
                <label>Produtos Fornecidos:</label>
                <textarea name="produtos_fornecidos" id="produtos_fornecidos" placeholder="Ex: Peças automotivas, Ferramentas, Óleos e filtros"></textarea>
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
        document.getElementById('modal-title').textContent = 'Cadastrar Fornecedor';
        document.getElementById('acao').value = 'cadastrar';
        document.getElementById('form').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarFornecedor(fornecedor) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Fornecedor';
        document.getElementById('acao').value = 'editar';
        document.getElementById('fornecedor-id').value = fornecedor.id;
        document.getElementById('nome_empresa').value = fornecedor.nome_empresa;
        document.getElementById('cnpj').value = fornecedor.cnpj;
        document.getElementById('contato').value = fornecedor.contato;
        document.getElementById('telefone').value = fornecedor.telefone;
        document.getElementById('email').value = fornecedor.email || '';
        document.getElementById('endereco').value = fornecedor.endereco || '';
        document.getElementById('produtos_fornecidos').value = fornecedor.produtos_fornecidos || '';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>