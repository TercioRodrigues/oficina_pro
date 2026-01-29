<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Acompanhamento - <?= $empresa['nome_fantasia'] ?></title>
    <meta http-equiv="refresh" content="30">
    <link rel="stylesheet" href="/assets/css/painel.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>🔧 <?= $empresa['nome_fantasia'] ?></h1>
            <h2 style="color: #333; margin-bottom: 10px;">Painel de Acompanhamento</h2>
            <p class="subtitle">Veículos em Atendimento</p>
        </header>

        <?php if (empty($ordens)): ?>
            <div class="empty-state">
                <div class="empty-icon">✅</div>
                <div class="empty-text">Nenhum veículo em atendimento no momento</div>
            </div>
        <?php else: ?>
            <div class="status-grid">
                <?php foreach ($ordens as $os):
                    $status_class = strtolower(str_replace(' ', '-', $os['status']));
                    $dias_servico = (strtotime(date('Y-m-d')) - strtotime($os['data_abertura'])) / 86400; ?>
                    <div class="os-card <?= $status_class ?>">
                        <div class="os-header">
                            <div class="os-numero">
                                OS #<?= str_pad($os['id'], 5, '0', STR_PAD_LEFT) ?>
                            </div>
                            <?php if ($status_class == 'Aberta'): ?>
                                <div class="badge badge-aberta">
                                <?php elseif ($status_class == 'Em_Andamento'): ?>
                                    <div class="badge badge-Em_Andamento">
                                    <?php else: ?>
                                        <div class="badge badge-aguardando">
                                        <?php endif; ?>
                                        <?= $os['status'] ?>
                                        </div>
                                    </div>

                                    <div class="os-info">
                                        <div class="info-row">
                                            <span class="info-label">👤 Cliente:</span>
                                            <span class="info-value"><?= htmlspecialchars($os['cliente_nome']) ?></span>
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">📅 Entrada:</span>
                                            <span class="info-value"><?= date('d/m/Y', strtotime($os['data_abertura'])) ?></span>
                                        </div>
                                    </div>

                                    <div class="veiculo-info">
                                        <div class="veiculo-destaque">
                                            🚗 <?= htmlspecialchars($os['marca'] . ' ' . $os['modelo']) ?>
                                        </div>
                                        <div style="color: #666; font-size: 1.1em;">
                                            Placa: <strong><?= htmlspecialchars($os['placa']) ?></strong>
                                        </div>
                                    </div>

                                    <div class="tempo-servico">
                                        <div class="dias-label">Tempo em Atendimento</div>
                                        <div class="dias-valor">
                                            <?php if ($dias_servico == 0): ?>
                                                Hoje
                                            <?php elseif ($dias_servico == 1): ?>
                                                1 dia
                                            <?php else: ?>
                                                <?= floor($dias_servico) ?> dias
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="atualizado">
                        📡 Atualizado em: <?= date('d/m/Y H:i:s') ?> | Auto-refresh a cada 30 segundos
                    </div>
                    </div>
</body>

</html>