<?= $render('header') ?>
<div class="page-header">
    <h1>Dashboard</h1>
    <p class="breadcrumb">Dashboard</p>
</div>

<!-- Estatísticas -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">OS do Mês</div>
                <div class="stat-value"><?= $total_os_mes ?></div>
            </div>
            <div class="stat-icon">📋</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">OS em Andamento</div>
                <div class="stat-value"><?= $os_andamento ?></div>
            </div>
            <div class="stat-icon">⚙️</div>
        </div>
    </div>

    <?php if ($_SESSION['usuario_nivel'] == 'Admin' || $_SESSION['usuario_nivel'] == 'Gerente'): ?>
        <div class="stat-card success">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Faturamento Mês</div>
                    <div class="stat-value" style="font-size: 1.5em;">R$ <?= number_format($faturamento_mes, 2, ',', '.') ?></div>
                </div>
                <div class="stat-icon">💰</div>
            </div>
        </div>
    <?php endif; ?>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Estoque Baixo</div>
                <div class="stat-value"><?= $estoque_baixo ?></div>
            </div>
            <div class="stat-icon">📦</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Agendamentos Hoje</div>
                <div class="stat-value"><?= $agendamentos_hoje ?></div>
            </div>
            <div class="stat-icon">📅</div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="charts-grid">
    <div class="card">
        <h2>OS por Status (Mês Atual)</h2>
        <div class="chart-container">
            <canvas id="chartStatus"></canvas>
        </div>
    </div>
    <?php if ($_SESSION['usuario_nivel'] == 'Admin' || $_SESSION['usuario_nivel'] == 'Gerente'): ?>
        <div class="card">
            <h2>Faturamento (Últimos 6 Meses)</h2>
            <div class="chart-container">
                <canvas id="chartFaturamento"></canvas>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Últimas OS -->
<div class="card">
    <div class="card-header">
        <h2>Últimas Ordens de Serviço</h2>
        <a href="/ordens" class="btn btn-primary">Ver Todas →</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>OS #</th>
                <th>Cliente</th>
                <th>Veículo</th>
                <th>Data</th>
                <th>Status</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ultimas_os as $os): ?>
                <tr>
                    <td><strong><?= str_pad($os['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                    <td><?= htmlspecialchars($os['cliente_nome']) ?></td>
                    <td><?= htmlspecialchars($os['placa']) ?></td>
                    <td><?= date('d/m/Y', strtotime($os['data_abertura'])) ?></td>
                    <?php if ($os['status'] === 'Em_Andamento') {
                        $class = 'badge-andamento';
                        $status = 'Em Andamento';
                    } elseif ($os['status'] === 'Aberta') {
                        $class = 'badge-aberto';
                        $status = 'Aberta';
                    } elseif ($os['status'] === 'Concluido') {
                        $class = 'badge-concluido';
                        $status = 'Concluído';
                    } elseif ($os['status'] === 'Aguardando_Aprovacao') {
                        $class = 'badge-aguardandoaprovacao';
                        $status = 'Aguardando Aprovação';
                    } elseif ($os['status'] === 'Aguardando_Pecas') {
                        $class = 'badge-aguardandopecas';
                        $status = 'Aguardando Peças';
                    } elseif ($os['status'] === 'Cancelado') {
                        $class = 'badge-cancelado';
                        $status = $os['status'];
                    }
                    ?>
                    <td><span class="badge <?= $class ?>"><?= $status ?></span></td>
                    <td><strong>R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Próximos Agendamentos -->
<div class="card">
    <div class="card-header">
        <h2>Próximos Agendamentos</h2>
        <a href="/agendamentos" class="btn btn-primary">Ver Todos →</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Cliente</th>
                <th>Veículo</th>
                <th>Serviço</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proximos_agendamentos as $ag): ?>
                <tr>
                    <td><strong><?= date('d/m/Y', strtotime($ag['data_agendamento'])) ?></strong></td>
                    <td><?= htmlspecialchars($ag['cliente_nome']) ?></td>
                    <td><?= htmlspecialchars($ag['marca'] . ' ' . $ag['modelo'] . ' - ' . $ag['placa']) ?></td>
                    <td><?= htmlspecialchars($ag['servico_solicitado']) ?></td>
                    <td><span class="badge badge-<?= strtolower($ag['status']) ?>"><?= $ag['status'] ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Gráfico de OS por Status
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($os_por_status, 'status')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($os_por_status, 'total')) ?>,
                backgroundColor: [
                    '#4facfe',
                    '#43e97b',
                    '#feca57',
                    '#fa709a',
                    '#667eea',
                    '#764ba2'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Faturamento
    const ctxFaturamento = document.getElementById('chartFaturamento').getContext('2d');
    new Chart(ctxFaturamento, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($faturamento_meses, 'mes')) ?>,
            datasets: [{
                label: 'Faturamento',
                data: <?= json_encode(array_column($faturamento_meses, 'total')) ?>,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
</script>
<?= $render('header') ?>