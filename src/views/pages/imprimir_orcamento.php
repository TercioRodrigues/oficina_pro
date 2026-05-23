<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Orçamento #<?= str_pad($orcamento['orcamento_id'], 4, '0', STR_PAD_LEFT) ?></title>
    <link rel="stylesheet" href="/assets/css/orcamento.css">
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
        <button onclick="window.close()" style="margin-left:10px; background: linear-gradient(90deg,#555,#777);">✖ Fechar</button>
    </div>

    <!-- Cabeçalho -->
    <div class="header">

        <?php if (!empty($config['logo'])): ?>
            <img class="header-logo"
                src="/<?= htmlspecialchars($config['logo']) ?>"
                alt="Logo <?= htmlspecialchars($config['nome_fantasia']) ?>">
        <?php else: ?>
            <div class="header-logo-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="52" height="52">
                    <circle cx="32" cy="32" r="10" fill="#1a3a6b" />
                    <g fill="#1a3a6b">
                        <?php
                        $angles = [0, 45, 90, 135, 180, 225, 270, 315];
                        foreach ($angles as $a):
                            $rad = deg2rad($a);
                            $x = 32 + 20 * cos($rad);
                            $y = 32 + 20 * sin($rad);
                        ?>
                            <rect x="<?= $x - 3 ?>" y="<?= $y - 5 ?>"
                                width="6" height="10" rx="2"
                                transform="rotate(<?= $a ?> <?= $x ?> <?= $y ?>)" />
                        <?php endforeach; ?>
                    </g>
                    <circle cx="32" cy="32" r="7" fill="#2563b0" />
                    <circle cx="32" cy="32" r="3" fill="white" />
                </svg>
            </div>
        <?php endif; ?>

        <div class="header-info">
            <h1>🔧 <?= htmlspecialchars($config['nome_fantasia']) ?></h1>
            <div class="empresa-info">
                <?= htmlspecialchars($config['razao_social']) ?><br>
                CNPJ: <?= htmlspecialchars($config['cnpj']) ?><br>
                <?= htmlspecialchars($config['endereco'] . ', ' . $config['numero'] . ' - ' . $config['bairro']) ?><br>
                <?= htmlspecialchars($config['cidade'] . '/' . $config['estado']) ?> &nbsp;|&nbsp;
                Tel: <?= htmlspecialchars($config['telefone']) ?> &nbsp;|&nbsp;
                Email: <?= htmlspecialchars($config['email']) ?>
            </div>
        </div>
    </div>

    <div class="orcamento-numero">
        ORÇAMENTO Nº <?= str_pad($orcamento['orcamento_id'], 4, '0', STR_PAD_LEFT) ?>
    </div>

    <!-- Dados do Cliente -->
    <div class="secao">
        <div class="secao-title">👤 Dados do Cliente</div>
        <div class="dados-grid">
            <div class="dado">
                <div class="dado-label">Nome:</div>
                <div class="dado-valor"><?= htmlspecialchars($orcamento['cliente_nome']) ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Telefone:</div>
                <div class="dado-valor"><?= htmlspecialchars($orcamento['cliente_telefone']) ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">CPF/CNPJ:</div>
                <div class="dado-valor"><?= htmlspecialchars($orcamento['cliente_cpf_cnpj']) ?></div>
            </div>
        </div>
    </div>

    <!-- Dados do Veículo -->
    <div class="secao">
        <div class="secao-title">🚗 Dados do Veículo</div>
        <div class="dados-grid">
            <div class="dado">
                <div class="dado-label">Marca/Modelo:</div>
                <div class="dado-valor"><?= htmlspecialchars($orcamento['veiculo_marca'] . ' ' . $orcamento['veiculo_modelo']) ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Ano:</div>
                <div class="dado-valor"><?= htmlspecialchars($orcamento['veiculo_ano']) ?></div>
            </div>
            <?php if ($orcamento['veiculo_placa']): ?>
                <div class="dado">
                    <div class="dado-label">Placa:</div>
                    <div class="dado-valor"><?= htmlspecialchars($orcamento['veiculo_placa']) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <div class="dado" style="margin-top: 8px;">
            <div class="dado-label">Descrição do Serviço Solicitado:</div>
            <div class="dado-valor"><?= nl2br(htmlspecialchars($orcamento['descricao_servico'])) ?></div>
        </div>
    </div>

    <!-- Produtos -->
    <?php if (!empty($produtos_orc)): ?>
        <div class="secao">
            <div class="secao-title">📦 Produtos / Peças</div>
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th style="width:80px; text-align:center">Qtd</th>
                        <th style="width:120px; text-align:right">Valor Unit.</th>
                        <th style="width:120px; text-align:right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos_orc as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td style="text-align:center"><?= $item['quantidade'] ?></td>
                            <td style="text-align:right">R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td style="text-align:right">R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Serviços -->
    <?php if (!empty($servicos_orc)): ?>
        <div class="secao">
            <div class="secao-title">🔨 Serviços</div>
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th style="width:80px; text-align:center">Qtd</th>
                        <th style="width:120px; text-align:right">Valor Unit.</th>
                        <th style="width:120px; text-align:right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos_orc as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td style="text-align:center"><?= $item['quantidade'] ?></td>
                            <td style="text-align:right">R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td style="text-align:right">R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Totais -->
    <div class="totais">
        <div class="total-linha">
            <span>Produtos/Peças:</span>
            <strong>R$ <?= number_format($orcamento['valor_pecas'], 2, ',', '.') ?></strong>
        </div>
        <div class="total-linha">
            <span>Serviços:</span>
            <strong>R$ <?= number_format($orcamento['valor_servicos'], 2, ',', '.') ?></strong>
        </div>
        <?php if ($orcamento['desconto'] > 0): ?>
            <div class="total-linha">
                <span>Desconto:</span>
                <strong>- R$ <?= number_format($orcamento['desconto'], 2, ',', '.') ?></strong>
            </div>
        <?php endif; ?>
        <div class="total-linha total-final">
            <span>TOTAL:</span>
            <strong>R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></strong>
        </div>
    </div>

    <div style="clear:both;"></div>

    <!-- Validade -->
    <div class="validade-box">
        <strong>⏰ VALIDADE DO ORÇAMENTO:</strong><br>
        Este orçamento é válido até <strong><?= date('d/m/Y', strtotime($orcamento['data_validade'])) ?></strong>
        (<?= $orcamento['validade_dias'] ?> dias a partir de <?= date('d/m/Y', strtotime($orcamento['data_orcamento'])) ?>)
    </div>

    <!-- Observações -->
    <?php if ($orcamento['observacoes']): ?>
        <div class="observacoes">
            <strong>📌 Observações:</strong><br>
            <?= nl2br(htmlspecialchars($orcamento['observacoes'])) ?>
        </div>
    <?php endif; ?>

    <!-- Assinatura -->
    <div class="assinatura-wrapper">
        <div class="assinatura">
            <strong>De acordo</strong><br>
            Cliente: <?= htmlspecialchars($orcamento['cliente_nome']) ?>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="rodape">
        <p><strong>Orçamento gerado em:</strong> <?= date('d/m/Y H:i') ?></p>
        <p>Este documento não tem validade fiscal</p>
        <?php if ($config['horario_funcionamento']): ?>
            <p><?= htmlspecialchars($config['horario_funcionamento']) ?></p>
        <?php endif; ?>
    </div>

</body>

</html>