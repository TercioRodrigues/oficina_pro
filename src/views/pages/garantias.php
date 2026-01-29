<?= $render('header') ?>
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">🛡️ Controle de Garantias</h1>
            <a href="/dashboard" class="btn btn-secondary">← Dashboard</a>
        </div>
    </div>

    <div class="mb-3">
        <a href="?status=ativa" class="btn btn-<?= $status_filtro === 'ativa' ? 'primary' : 'outline-primary' ?>">Ativas</a>
        <a href="?status=todas" class="btn btn-<?= $status_filtro === 'todas' ? 'primary' : 'outline-primary' ?>">Todas</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>OS #</th>
                        <th>Cliente</th>
                        <th>Veículo</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Dias Restantes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($garantias as $g):
                        $dias_restantes = (strtotime($g['data_fim']) - time()) / 86400;
                    ?>
                        <tr>
                            <td>#<?= str_pad($g['os_numero'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= htmlspecialchars($g['cliente_nome']) ?></td>
                            <td><?= htmlspecialchars($g['placa']) ?></td>
                            <td><?= $g['tipo'] ?></td>
                            <td><?= htmlspecialchars($g['descricao']) ?></td>
                            <td><?= date('d/m/Y', strtotime($g['data_inicio'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($g['data_fim'])) ?></td>
                            <td>
                                <?php if ($dias_restantes > 0): ?>
                                    <span class="badge badge-emandamento"><?= floor($dias_restantes) ?> dias</span>
                                <?php else: ?>
                                    <span class="badge badge-expirado">Expirada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $g['status'] === 'Ativa' ? 'ativo' : 'inativa' ?>">
                                    <?= $g['status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $render('footer') ?>