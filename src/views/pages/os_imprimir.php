<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>OS #<?= str_pad($os['os_id'], 5, '0', STR_PAD_LEFT) ?> - <?= $oficina['nome_fantasia'] ?></title>
    <link rel="stylesheet" href="/assets/css/os_imprimir.css">
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
        <button onclick="window.close()" style="margin-left:10px; background: linear-gradient(90deg,#555,#777);">✖ Fechar</button>
    </div>

    <!-- Cabeçalho -->
    <div class="header">

        <?php if (!empty($oficina['logo'])): ?>
            <!-- Se tiver logo cadastrada no banco, exibe a imagem -->
            <img class="header-logo"
                src="/<?= htmlspecialchars($oficina['logo']) ?>"
                alt="Logo <?= htmlspecialchars($oficina['nome_fantasia']) ?>">
        <?php else: ?>
            <!-- Placeholder SVG de engrenagem quando não há logo -->
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
            <h1>🔧 <?= htmlspecialchars($oficina['nome_fantasia']) ?></h1>
            <p><?= htmlspecialchars($oficina['endereco']) ?> Nº<?= htmlspecialchars($oficina['numero']) ?></p>
            <p>
                Tel: <?= htmlspecialchars($oficina['telefone']) ?>
                &nbsp;|&nbsp;
                WhatsApp: <?= htmlspecialchars($oficina['whatsapp']) ?>
                &nbsp;|&nbsp;
                <?= htmlspecialchars($oficina['email']) ?>
            </p>
        </div>
    </div>

    <div class="os-numero">
        ORDEM DE SERVIÇO Nº #<?= str_pad($os['os_id'], 5, '0', STR_PAD_LEFT) ?>
    </div>

    <!-- Dados do Cliente -->
    <div class="secao">
        <div class="secao-title">Dados do Cliente</div>
        <div class="dados-grid">
            <div class="dado">
                <span class="dado-label">Nome</span>
                <?= htmlspecialchars($os['cliente_nome']) ?>
            </div>
            <div class="dado">
                <span class="dado-label">CPF</span>
                <?= htmlspecialchars($os['cpf_cnpj']) ?>
            </div>
            <div class="dado">
                <span class="dado-label">Telefone</span>
                <?= htmlspecialchars($os['telefone']) ?>
            </div>
        </div>
    </div>

    <!-- Dados do Veículo -->
    <div class="secao">
        <div class="secao-title">Dados do Veículo</div>
        <div class="dados-grid">
            <div class="dado">
                <span class="dado-label">Marca / Modelo</span>
                <?= htmlspecialchars($os['marca'] . ' ' . $os['modelo']) ?>
            </div>
            <div class="dado">
                <span class="dado-label">Placa</span>
                <?= htmlspecialchars($os['placa']) ?>
            </div>
            <div class="dado">
                <span class="dado-label">Ano</span>
                <?= htmlspecialchars($os['ano']) ?>
            </div>
            <div class="dado">
                <span class="dado-label">Cor</span>
                <?= empty($os['cor']) ? '' : htmlspecialchars($os['cor']) ?>
            </div>
        </div>
    </div>

    <!-- Dados da OS -->
    <div class="secao">
        <div class="secao-title">Dados da Ordem de Serviço</div>
        <div class="dados-grid">
            <div class="dado">
                <span class="dado-label">Data Abertura</span>
                <?= date('d/m/Y', strtotime($os['data_abertura'])) ?>
            </div>
            <div class="dado">
                <span class="dado-label">Data Conclusão</span>
                <?= empty($os['data_fechamento']) ? '—' : date('d/m/Y', strtotime($os['data_fechamento'])) ?>
            </div>
            <div class="dado">
                <span class="dado-label">Atendente</span>
                <?= htmlspecialchars($os['atendente_nome']) ?>
            </div>
            <div class="dado">
                <span class="dado-label">Status</span>
                <?= htmlspecialchars($os['status']) ?>
            </div>
            <div class="dado dado-full">
                <span class="dado-label">Problema Relatado</span>
                <?= nl2br(htmlspecialchars($os['descricao_problema'])) ?>
            </div>
        </div>
    </div>

    <!-- Produtos/Peças -->
    <?php if (!empty($produtos_os)): ?>
        <div class="secao">
            <div class="secao-title">Produtos / Peças Utilizados</div>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th style="text-align:center">Qtd</th>
                        <th style="text-align:right">Valor Unit.</th>
                        <th style="text-align:right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos_os as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['codigo']) ?></td>
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
    <?php if (!empty($servicos_os)): ?>
        <div class="secao">
            <div class="secao-title">Serviços Executados</div>
            <table>
                <thead>
                    <tr>
                        <th>Serviço</th>
                        <th style="text-align:center">Qtd</th>
                        <th style="text-align:right">Valor Unit.</th>
                        <th style="text-align:right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos_os as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td style="text-align:center"><?= $item['quantidade'] ?></td>
                            <td style="text-align:right">R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td style="text-align:right">R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Totais + Assinaturas -->
    <div class="rodape-bloco">
        <div class="assinaturas">
            <div class="assinatura">
                <div>Cliente</div>
            </div>
            <div class="assinatura">
                <div>Oficina</div>
            </div>
        </div>

        <div class="totais">
            <div class="total-linha">
                <span>Produtos/Peças:</span>
                <strong>R$ <?= number_format($os['valor_pecas'], 2, ',', '.') ?></strong>
            </div>
            <div class="total-linha">
                <span>Serviços:</span>
                <strong>R$ <?= number_format($os['valor_servicos'], 2, ',', '.') ?></strong>
            </div>
            <?php if ($os['desconto'] > 0): ?>
                <div class="total-linha">
                    <span>Desconto:</span>
                    <strong>- R$ <?= number_format($os['desconto'], 2, ',', '.') ?></strong>
                </div>
            <?php endif; ?>
            <div class="total-linha total-final">
                <span>TOTAL:</span>
                <strong>R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></strong>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="rodape">
        <p>Documento gerado em <?= date('d/m/Y H:i') ?> &nbsp;·&nbsp; Este documento é válido como comprovante de serviço</p>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auto_print') === '1') {
            window.onload = function() {
                window.print();
            }
        }
    </script>
</body>

</html>