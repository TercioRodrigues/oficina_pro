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
    <h1>🔨 Gestão de Serviços</h1>
    <div>
        <a href="index.php" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Serviço</button>
    </div>
</header>

<div class="card">
    <h2 style="margin-bottom: 20px;">Lista de Serviços Oferecidos</h2>
    <table>
        <thead>
            <tr>
                <th>Nome do Serviço</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Tempo Estimado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($servico['nome']) ?></strong></td>
                    <td><?= htmlspecialchars($servico['descricao']) ?></td>
                    <td>R$ <?= number_format($servico['valor'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($servico['tempo_estimado']) ?></td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-success" onclick='editarServico(<?= json_encode($servico) ?>)'>Editar</button>
                            <form method="POST" style="display: inline;" action="/servicos/processar" onsubmit="return confirm('Deseja realmente excluir?')">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $servico['id'] ?>">
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
        <h2 id="modal-title">Cadastrar Serviço</h2>
        <form method="POST" action="/servicos/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="servico-id">

            <div class="form-group">
                <label>Nome do Serviço:</label>
                <input type="text" name="nome" id="nome" required placeholder="Ex: Troca de Óleo">
            </div>

            <div class="form-group">
                <label>Descrição:</label>
                <textarea name="descricao" id="descricao" placeholder="Descreva o serviço em detalhes"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Valor (R$):</label>
                    <input type="number" name="valor" id="valor" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label>Tempo Estimado:</label>
                    <input type="text" name="tempo_estimado" id="tempo_estimado" placeholder="Ex: 1 hora" required>
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
        document.getElementById('modal-title').textContent = 'Cadastrar Serviço';
        document.getElementById('acao').value = 'cadastrar';
        document.querySelector('form').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarServico(servico) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Serviço';
        document.getElementById('acao').value = 'editar';
        document.getElementById('servico-id').value = servico.id;
        document.getElementById('nome').value = servico.nome;
        document.getElementById('descricao').value = servico.descricao || '';
        document.getElementById('valor').value = servico.valor;
        document.getElementById('tempo_estimado').value = servico.tempo_estimado;
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>