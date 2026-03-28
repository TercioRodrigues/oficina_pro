<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico do Veículo - Sistema Oficina</title>
    <link rel="stylesheet" href="/assets/css/historico.css">
</head>

<body>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <div class="header-content">
                <div class="vehicle-info">
                    <h1><?= $veiculo['marca'] ?> <?= $veiculo['modelo'] ?></h1>
                    <div class="vehicle-details">
                        <div class="detail-item">
                            <span class="detail-label">Placa</span>
                            <span class="detail-value"><?= $veiculo['placa'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Ano</span>
                            <span class="detail-value"><?= $veiculo['ano'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Cor</span>
                            <span class="detail-value"><?= $veiculo['cor'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Proprietário</span>
                            <span class="detail-value"><?= $veiculo['cliente_nome'] ?></span>
                        </div>
                    </div>
                </div>
                <a href="/veiculos" class="btn-voltar">← Voltar</a>
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="content">
            <!-- Estatísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $veiculo['total_visitas'] ?></div>
                    <div class="stat-label">Total de Visitas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">R$ <?= number_format($veiculo['valor_total'], 2, ',', '.') ?></div>
                    <div class="stat-label">Valor Total Gasto</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($veiculo['km_atual'], 0, '.', '.') ?> km</div>
                    <div class="stat-label">Quilometragem Atual</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= empty($veiculo['historico'][0]['data_abertura']) ? '' : date('d/m/Y', strtotime($veiculo['historico'][0]['data_abertura'])) ?></div>
                    <div class="stat-label">Última Visita</div>
                </div>
            </div>

            <!-- Timeline de Histórico -->
            <h2 class="section-title">Histórico de Serviços</h2>
            <div class="timeline">
                <?php if ($veiculo['historico']): ?>
                    <?php foreach ($veiculo['historico'] as $historico): ?>

                        <!-- Item 1 -->
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <span class="timeline-date"><?= date('d/m/Y - H:i', strtotime($historico['data_cadastro'])) ?></span>
                                <span class="timeline-type type-manutencao">Manutenção</span>
                            </div>
                            <div class="timeline-content">
                                <h3><?= $historico['descricao_problema'] ?> <span class="status-badge status-concluido"><?= $historico['status'] ?></span></h3>
                                <ul class="servicos-list">
                                    <?php foreach ($historico['servicos'] as $servicos): ?>
                                        <?php foreach ($servicos as $servico): ?>
                                            <li><?= $servico['descricao'] ?? $servico['nome'] ?></li>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="timeline-details">
                                    <div class="detail-box">
                                        <strong>Quilometragem:</strong>
                                        <span><?= empty($historico['km_veiculo']) ? '' : number_format($historico['km_veiculo'], 0, '.', '.') . ' km' ?></span>
                                    </div>
                                    <div class="detail-box">
                                        <strong>Mecânico:</strong>
                                        <span><?= $historico['mecanico_nome'] ?></span>
                                    </div>
                                    <div class="detail-box">
                                        <strong>Valor:</strong>
                                        <span>R$ <?= number_format($historico['valor_total'], 2, ',', '.') ?></span>
                                    </div>
                                    <div class="detail-box">
                                        <strong>Garantia:</strong>
                                        <span>6 meses</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>

</html>