<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Orçamento #<?= str_pad($orcamento_id, 4, '0', STR_PAD_LEFT) ?></title>
    <link rel="stylesheet" href="/assets/css/orcamento.css">
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 12px 30px; font-size: 14pt; cursor: pointer; background: #667eea; color: white; border: none; border-radius: 8px;">
            🖨️ Imprimir / Salvar PDF
        </button>
        <button onclick="window.close()" style="padding: 12px 30px; font-size: 14pt; cursor: pointer; margin-left: 10px; background: #6c757d; color: white; border: none; border-radius: 8px;">
            ✖ Fechar
        </button>
    </div>

    <div class="header">
        <h1>🔧 <?= htmlspecialchars($config['nome_fantasia']) ?></h1>
        <div class="empresa-info">
            <?= htmlspecialchars($config['razao_social']) ?><br>
            CNPJ: <?= htmlspecialchars($config['cnpj']) ?><br>
            <?= htmlspecialchars($config['endereco'] . ', ' . $config['numero'] . ' - ' . $config['bairro']) ?><br>
            <?= htmlspecialchars($config['cidade'] . '/' . $config['estado']) ?> |
            Tel: <?= htmlspecialchars($config['telefone']) ?> |
            Email: <?= htmlspecialchars($config['email']) ?>
        </div>
    </div>

    <div class="orcamento-numero">
        ORÇAMENTO Nº <?= str_pad($orcamento_id, 4, '0', STR_PAD_LEFT) ?>
    </div>

    <!-- Dados do Cliente -->
    <div class="secao">
        <div class="secao-title">👤 DADOS DO CLIENTE</div>
        <div class="dados-grid">
            <div class="dado">
                <div class="dado-label">Nome:</div>
                <div class="dado-valor"><?= htmlspecialchars($orcamento['cliente_nome']) ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Telefone:</div>
                <div class="dado-valor"><?= htmlspecialchars($orcamento['cliente_telefone']) ?></div>
            </div>
            <?php if ($orcamento['cliente_email']): ?>
                <div class="dado">
                    <div class="dado-label">Email:</div>
                    <div class="dado-valor"><?= htmlspecialchars($orcamento['cliente_email']) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Dados do Veículo -->
    <div class="secao">
        <div class="secao-title">🚗 DADOS DO VEÍCULO</div>
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

        <div class="dado" style="margin-top: 10px;">
            <div class="dado-label">Descrição do Serviço Solicitado:</div>
            <div class="dado-valor"><?= nl2br(htmlspecialchars($orcamento['descricao_servico'])) ?></div>
        </div>
    </div>

    <!-- Produtos -->
    <?php if (!empty($produtos_orc)): ?>
        <div class="secao">
            <div class="secao-title">📦 PRODUTOS/PEÇAS</div>
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th style="width: 80px;">Qtd</th>
                        <th style="width: 120px;">Valor Unit.</th>
                        <th style="width: 120px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos_orc as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td style="text-align: center;"><?= $item['quantidade'] ?></td>
                            <td><?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td><?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Serviços -->
    <?php if (!empty($servicos_orc)): ?>
        <div class="secao">
            <div class="secao-title">🔨 SERVIÇOS</div>
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th style="width: 80px;">Qtd</th>
                        <th style="width: 120px;">Valor Unit.</th>
                        <th style="width: 120px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos_orc as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td style="text-align: center;"><?= $item['quantidade'] ?></td>
                            <td><?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td><?= number_format($item['valor_total'], 2, ',', '.') ?></td>
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
            <strong><?= number_format($orcamento['valor_pecas'], 2, ',', '.') ?></strong>
        </div>
        <div class="total-linha">
            <span>Serviços:</span>
            <strong><?= number_format($orcamento['valor_servicos'], 2, ',', '.') ?></strong>
        </div>
        <?php if ($orcamento['desconto'] > 0): ?>
            <div class="total-linha">
                <span>Desconto:</span>
                <strong>- <?= number_format($orcamento['desconto'], 2, ',', '.') ?></strong>
            </div>
        <?php endif; ?>
        <div class="total-linha total-final">
            <span>TOTAL:</span>
            <strong>R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></strong>
        </div>
    </div>

    <div style="clear: both;"></div>

    <!-- Validade -->
    <div class="validade-box">
        <strong>⏰ VALIDADE DO ORÇAMENTO:</strong><br>
        Este orçamento é válido até <strong><?= date('d/m/Y', strtotime($orcamento['data_validade'])) ?></strong> (<?= $orcamento['validade_dias'] ?> dias a partir de <?= date('d/m/Y', strtotime($orcamento['data_orcamento'])) ?>)
    </div>

    <!-- Observações -->
    <?php if ($orcamento['observacoes']): ?>
        <div class="observacoes">
            <strong>📌 Observações:</strong><br>
            <?= nl2br(htmlspecialchars($orcamento['observacoes'])) ?>
        </div>
    <?php endif; ?>

    <!-- Assinatura -->
    <div class="assinatura">
        <strong>De acordo</strong><br>
        Cliente: <?= htmlspecialchars($orcamento['cliente_nome']) ?>
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