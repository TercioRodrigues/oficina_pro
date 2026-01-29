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
    <h1>📅 Agendamentos</h1>
    <div>
        <a href="/" class="btn btn-secondary">← Dashboard</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Agendamento</button>
    </div>
</header>

<!-- Filtros -->
<div class="filtros">
    <a href="?filtro=Agendado" class="<?= $filtro === 'Agendado' ? 'active' : '' ?>">Agendados</a>
    <a href="?filtro=Confirmado" class="<?= $filtro === 'Confirmado' ? 'active' : '' ?>">Confirmados</a>
    <a href="?filtro=Concluido" class="<?= $filtro === 'Concluido' ? 'active' : '' ?>">Concluído</a>
    <a href="?filtro=Cancelado" class="<?= $filtro === 'Cancelado' ? 'active' : '' ?>">Cancelados</a>
</div>

<div class="card">
    <h2 style="margin-bottom: 20px;">Lista de Agendamentos</h2>
    <table>
        <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Cliente</th>
                <th>Telefone</th>
                <th>Veículo</th>
                <th>Serviço Solicitado</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agendamentos as $ag): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($ag['data_agendamento'])) ?></td>
                    <td><?= htmlspecialchars($ag['cliente_nome']) ?></td>
                    <td><?= htmlspecialchars($ag['telefone']) ?></td>
                    <td><?= htmlspecialchars($ag['marca'] . ' ' . $ag['modelo'] . ' - ' . $ag['placa']) ?></td>
                    <td><?= htmlspecialchars($ag['servico_solicitado']) ?></td>
                    <td><span class="badge badge-<?= strtolower(str_replace([' ', '_'], '', $ag['status'])) ?>"><?= $ag['status'] ?></span></td>
                    <td>
                        <div class="actions">
                            <?php if ($ag['status'] === 'Confirmado'): ?>
                                <form method="POST" style="display: inline;" action="/Os/processar">
                                    <input type="hidden" name="acao" value="cadastrar">
                                    <input type="hidden" name="agendamento_id" value="<?= $ag['id'] ?>">
                                    <input type="hidden" name="cliente_id" value="<?= $ag['cliente_id'] ?>">
                                    <input type="hidden" name="veiculo_id" value="<?= $ag['veiculo_id'] ?>">
                                    <input type="hidden" name="descricao_problema" value="<?= $ag['servico_solicitado'] ?>">
                                    <button type="submit" class="btn btn-success">Criar OS</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($ag['status'] != 'Concluido'): ?>
                                <button class="btn btn-info" onclick='editarAgendamento(<?= json_encode($ag) ?>)'>Editar</button>
                                <form method="POST" style="display: inline;" action="/agendamentos/processar">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?= $ag['id'] ?>">
                                    <input type="hidden" name="filtro" value="<?= $filtro ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Excluir?')">Excluir</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($agendamentos)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Nenhum agendamento encontrado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h2 id="modal-title">Novo Agendamento</h2>
        <form method="POST" action="/agendamentos/processar" id="cadastrar_editar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="agendamento-id">
            <input type="hidden" name="filtro" value="<?= $filtro ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Cliente:</label>
                    <select name="cliente_id" id="cliente_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Veículo:</label>
                    <select name="veiculo_id" id="veiculo_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($veiculos as $v): ?>
                            <option value="<?= $v['id'] ?>">
                                <?= htmlspecialchars($v['cliente_nome'] . ' - ' . $v['marca'] . ' ' . $v['modelo'] . ' (' . $v['placa'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Data e Hora:</label>
                    <input type="datetime-local" name="data_agendamento" id="data_agendamento" required>
                </div>

                <div class="form-group" id="status-group" style="display: none;">
                    <label>Status:</label>
                    <select name="status" id="status">
                        <option value="Agendado">Agendado</option>
                        <option value="Confirmado">Confirmado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Serviço Solicitado:</label>
                <textarea name="servico_solicitado" id="servico_solicitado" required></textarea>
            </div>

            <div class="form-group">
                <label>Observações:</label>
                <textarea name="observacoes" id="observacoes"></textarea>
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
        document.getElementById('modal-title').textContent = 'Novo Agendamento';
        document.getElementById('acao').value = 'cadastrar';
        document.getElementById('status-group').style.display = 'none';
        document.getElementById('cadastrar_editar').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
        document.getElementById('cadastrar_editar').reset();
    }

    function editarAgendamento(ag) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Agendamento';
        document.getElementById('acao').value = 'editar';
        document.getElementById('status-group').style.display = 'block';
        document.getElementById('agendamento-id').value = ag.id;
        document.getElementById('cliente_id').value = ag.cliente_id;
        document.getElementById('veiculo_id').value = ag.veiculo_id;
        document.getElementById('data_agendamento').value = ag.data_agendamento.replace(' ', 'T');
        document.getElementById('servico_solicitado').value = ag.servico_solicitado;
        document.getElementById('observacoes').value = ag.observacoes || '';
        document.getElementById('status').value = ag.status;
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>