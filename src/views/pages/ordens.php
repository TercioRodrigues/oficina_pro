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
    <h1>📋 Ordens de Serviço</h1>
    <div>
        <a href="index.php" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Nova OS</button>
    </div>

</header>

<div class="card">
    <div class="filtros-lista-ordens">
        <h2 style="margin-bottom: 20px;">Lista de Ordens de Serviço</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
            <div class="filtros">
                <a href="?status=Aberta" class="btn-filtro <?= $status_filtro === 'Aberta' ? 'active' : '' ?>">Aberta</a>
                <a href="?status=Em_Andamento" class="btn-filtro <?= $status_filtro === 'Em_Andamento' ? 'active' : '' ?>">Em Andamento</a>
                <a href="?status=Aguardando_Pecas" class="btn-filtro <?= $status_filtro === 'Aguardando_Pecas' ? 'active' : '' ?>">Aguardando Peças</a>
                <a href="?status=Aguardando_Aprovacao" class="btn-filtro <?= $status_filtro === 'Aguardando_Aprovacao' ? 'active' : '' ?>">Aguardando Aprovação</a>
                <a href="?status=Concluido" class="btn-filtro <?= $status_filtro === 'Concluido' ? 'active' : '' ?>">Concluído</a>
                <a href="?status=Cancelado" class="btn-filtro <?= $status_filtro === 'Cancelado' ? 'active' : '' ?>">Cancelado</a>
            </div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>OS #</th>
                <th>Cliente</th>
                <th>Veículo</th>
                <th>Data Abertura</th>
                <th>Status</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordens as $ordem): ?>
                <tr style="cursor: pointer;" onclick="window.location.href='/Os/itens?os_id=<?= $ordem['id'] ?>';">
                    <td>#<?= str_pad($ordem['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($ordem['cliente_nome']) ?></td>
                    <td><?= htmlspecialchars($ordem['marca'] . ' ' . $ordem['modelo'] . ' - ' . $ordem['placa']) ?></td>
                    <td><?= date('d/m/Y', strtotime($ordem['data_abertura'])) ?></td>
                    <td>
                        <?php if ($ordem['status'] === 'Em_Andamento') {
                            $class = 'badge-andamento';
                            $status = 'Em Andamento';
                        } elseif ($ordem['status'] === 'Aberta') {
                            $class = 'badge-aberta';
                            $status = 'Aberta';
                        } elseif ($ordem['status'] === 'Concluido') {
                            $class = 'badge-concluido';
                            $status = 'Concluído';
                        } elseif ($ordem['status'] === 'Aguardando_Aprovacao') {
                            $class = 'badge-aguardandoaprovacao';
                            $status = 'Aguardando Aprovação';
                        } elseif ($ordem['status'] === 'Aguardando_Pecas') {
                            $class = 'badge-aguardandopecas';
                            $status = 'Aguardando Peças';
                        } elseif ($ordem['status'] === 'Cancelado') {
                            $class = 'badge-cancelado';
                            $status = $ordem['status'];
                        }
                        ?>
                        <span class="badge <?= $class ?>"><?= $status ?></span>
                    </td>
                    <td>R$ <?= number_format($ordem['valor_total'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h2 id="modal-title">Nova Ordem de Serviço</h2>
        <form method="POST" action="/Os/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="ordem-id">

            <div class="form-row">
                <div class="form-group">
                    <label>Cliente:</label>
                    <select name="cliente_id" id="cliente_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Veículo:</label>
                    <select name="veiculo_id" id="veiculo_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($veiculos as $veiculo): ?>
                            <option value="<?= $veiculo['id'] ?>">
                                <?= htmlspecialchars($veiculo['cliente_nome'] . ' - ' . $veiculo['marca'] . ' ' . $veiculo['modelo'] . ' (' . $veiculo['placa'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Data Abertura:</label>
                    <input type="date" name="data_abertura" id="data_abertura" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" id="status" required>
                        <option value="Aberta">Aberta</option>
                        <option value="Em_Andamento">Em Andamento</option>
                        <option value="Concluida">Concluída</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Descrição do Problema:</label>
                <textarea name="descricao_problema" id="descricao_problema" required></textarea>
            </div>

            <div class="form-group">
                <label>Observações:</label>
                <textarea name="observacoes" id="observacoes"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Valor Total (R$):</label>
                    <input type="number" name="valor_total" id="valor_total" step="0.01" min="0" value="0" required>
                </div>

                <div class="form-group">
                    <label>Data Fechamento:</label>
                    <input type="date" name="data_fechamento" id="data_fechamento">
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
        document.getElementById('modal-title').textContent = 'Nova Ordem de Serviço';
        document.getElementById('acao').value = 'cadastrar';
        document.querySelector('form').reset();
        document.getElementById('data_abertura').value = '<?= date('Y-m-d') ?>';
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