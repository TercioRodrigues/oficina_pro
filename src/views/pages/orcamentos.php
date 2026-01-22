<?= $render('header') ?>

<div class="page-header">
    <h1>📝 Gestão de Orçamentos</h1>
    <p class="breadcrumb">Dashboard / Orçamentos</p>
</div>

<?php if (!empty($mensagem)): ?>
    <div class="alert alert-success"><?= $mensagem ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0;">Lista de Orçamentos</h2>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <div class="filtros">
                <a href="?status=Pendente" class="btn-filtro <?= $status_filtro === 'Pendente' ? 'active' : '' ?>">Pendentes</a>
                <a href="?status=Aprovado" class="btn-filtro <?= $status_filtro === 'Aprovado' ? 'active' : '' ?>">Aprovados</a>
                <a href="?status=Recusado" class="btn-filtro <?= $status_filtro === 'Recusado' ? 'active' : '' ?>">Recusados</a>
                <a href="?status=Todos" class="btn-filtro <?= $status_filtro === 'Todos' ? 'active' : '' ?>">Todos</a>
            </div>
            <button class="btn btn-primary" onclick="abrirModal()">+ Novo Orçamento</button>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Telefone</th>
                    <th>Veículo</th>
                    <th>Valor</th>
                    <th>Validade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcamentos as $orc):
                    $dias_validade = (strtotime($orc['data_validade']) - time()) / 86400;
                ?>
                    <tr style="cursor:pointer;" onclick="window.location='orcamentos/itens?orcamento_id=<?= $orc['id'] ?>';">
                        <td><strong>#<?= str_pad($orc['id'], 4, '0', STR_PAD_LEFT) ?></strong></td>
                        <td><?= date('d/m/Y', strtotime($orc['data_orcamento'])) ?></td>
                        <td><?= htmlspecialchars($orc['cliente_nome']) ?></td>
                        <td><?= htmlspecialchars($orc['cliente_telefone']) ?></td>
                        <td><?= htmlspecialchars($orc['veiculo_marca'] . ' ' . $orc['veiculo_modelo'] . ' ' . $orc['veiculo_ano']) ?></td>
                        <td><strong>R$ <?= number_format($orc['valor_total'], 2, ',', '.') ?></strong></td>
                        <td>
                            <?php if ($dias_validade < 0): ?>
                                <span class="badge badge-expirado">Expirado</span>
                            <?php else: ?>
                                <?= date('d/m/Y', strtotime($orc['data_validade'])) ?>
                                <br><small style="color: #7f8c8d;">(<?= floor($dias_validade) ?> dias)</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= strtolower($orc['status']) ?>">
                                <?= $orc['status'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($orcamentos)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; color: #999;">Nenhum orçamento encontrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Novo Orçamento</h2>
            <button onclick="fecharModal()" class="btn-close">×</button>
        </div>

        <form method="POST" action="orcamentos/processar">
            <input type="hidden" name="acao" value="cadastrar">

            <h3 style="color: #2c3e50; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">👤 Dados do Cliente</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome: *</label>
                    <input type="text" name="cliente_nome" required>
                </div>

                <div class="form-group">
                    <label>Telefone: *</label>
                    <input type="text" name="cliente_telefone" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="cliente_email">
                </div>
            </div>

            <h3 style="color: #2c3e50; margin: 20px 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">🚗 Dados do Veículo</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>Marca: *</label>
                    <input type="text" name="veiculo_marca" required>
                </div>

                <div class="form-group">
                    <label>Modelo: *</label>
                    <input type="text" name="veiculo_modelo" required>
                </div>

                <div class="form-group">
                    <label>Ano: *</label>
                    <input type="text" name="veiculo_ano" required maxlength="4">
                </div>

                <div class="form-group">
                    <label>Placa:</label>
                    <input type="text" name="veiculo_placa">
                </div>
            </div>

            <h3 style="color: #2c3e50; margin: 20px 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">📋 Informações do Orçamento</h3>

            <div class="form-group">
                <label>Descrição do Serviço: *</label>
                <textarea name="descricao_servico" rows="3" required placeholder="Ex: Troca de pastilhas de freio, revisão dos 10 mil km..."></textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Data do Orçamento: *</label>
                    <input type="date" name="data_orcamento" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label>Validade (dias): *</label>
                    <input type="number" name="validade_dias" value="7" min="1" required>
                    <small style="color: #7f8c8d;">Padrão: 7 dias</small>
                </div>
            </div>

            <div class="form-group">
                <label>Observações:</label>
                <textarea name="observacoes" rows="2"></textarea>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Criar e Adicionar Itens →</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<script>
    function abrirModal() {
        document.getElementById('modal').style.display = 'block';
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target === document.getElementById('modal')) {
            fecharModal();
        }
    }
</script>

<?= $render('footer') ?>