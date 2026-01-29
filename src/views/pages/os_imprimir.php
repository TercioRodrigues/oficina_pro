<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>OS #<?= str_pad($os['id'], 5, '0', STR_PAD_LEFT) ?> - <?= $oficina['nome_fantasia'] ?></title>
    <link rel="stylesheet" href="/assets/css/os_imprimir.css">
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14pt; cursor: pointer;">
            🖨️ Imprimir / Salvar PDF
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 14pt; cursor: pointer; margin-left: 10px;">
            ✖ Fechar
        </button>
    </div>

    <div class="header">
        <h1>🔧 <?= $oficina['nome_fantasia'] ?></h1>
        <p><?= $oficina['endereco'] ?> Nº<?= $oficina['numero'] ?></p>
        <p>Telefone: <?= $oficina['telefone'] ?> | Whatsapp: <?= $oficina['whatsapp'] ?></p>
        <p>Email: <?= $oficina['email'] ?></p>
    </div>

    <div class="os-numero">
        ORDEM DE SERVIÇO Nº <?= str_pad($os['id'], 5, '0', STR_PAD_LEFT) ?>
    </div>

    <!-- Dados do Cliente -->
    <div class="secao">
        <div class="secao-title">DADOS DO CLIENTE</div>
        <div class="dados-grid">
            <div class="dado">
                <div class="dado-label">Nome:</div>
                <div><?= $os['cliente_nome'] ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">CPF:</div>
                <div><?= $os['cpf'] ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Telefone:</div>
                <div><?= $os['telefone'] ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Endereço:</div>
                <div><?= $os['endereco'] ?></div>
            </div>
        </div>
    </div>

    <!-- Dados do Veículo -->
    <div class="secao">
        <div class="secao-title">DADOS DO VEÍCULO</div>
        <div class="dados-grid">
            <div class="dado">
                <div class="dado-label">Marca/Modelo:</div>
                <div><?= $os['marca'] . ' ' . $os['modelo'] ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Placa:</div>
                <div><?= $os['placa'] ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Ano:</div>
                <div><?= $os['ano'] ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Cor:</div>
                <div><?= $os['cor'] ?></div>
            </div>
        </div>
    </div>

    <!-- Dados da OS -->
    <div class="secao">
        <div class="secao-title">DADOS DA ORDEM DE SERVIÇO</div>
        <div class="dados-grid">
            <div class="dado">
                <div class="dado-label">Data Abertura:</div>
                <div><?= date('d/m/Y',strtotime($os['data_abertura'])) ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Data Conclusão:</div>
                <div><?= date('d/m/Y',strtotime($os['data_fechamento'])) ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Atendente:</div>
                <div><?= $os['atendente_nome'] ?></div>
            </div>
            <div class="dado">
                <div class="dado-label">Status:</div>
                <div><?= $os['status'] ?></div>
            </div>
        </div>
        <div class="dado" style="grid-column: 1 / -1;">
            <div class="dado-label">Problema Relatado:</div>
            <div><?= nl2br($os['descricao_problema']) ?></div>
        </div>
    </div>

    <!-- Produtos/Peças -->
    <?php if (!empty($produtos_os)): ?>
        <div class="secao">
            <div class="secao-title">PRODUTOS/PEÇAS UTILIZADOS</div>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Qtd</th>
                        <th>Valor Unit.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos_os as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['codigo']) ?></td>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td>R$ <?= number_format($item['valor_unitario'], 2 ,',', '.') ?></td>
                            <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Serviços -->
    <?php if (!empty($servicos_os)): ?>
        <div class="secao">
            <div class="secao-title">SERVIÇOS EXECUTADOS</div>
            <table>
                <thead>
                    <tr>
                        <th>Serviço</th>
                        <th>Qtd</th>
                        <th>Valor Unit.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos_os as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
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

    <!-- Assinaturas -->
    <div class="assinaturas">
        <div class="assinatura">
            <div>Cliente</div>
        </div>
        <div class="assinatura">
            <div>Oficina</div>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="rodape">
        <p>Documento gerado em <?= date('d/m/Y H:i') ?></p>
        <p>Este documento é válido como comprovante de serviço</p>
    </div>

    <script>
        // Auto-print quando solicitado
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auto_print') === '1') {
            window.onload = function() {
                window.print();
            }
        }
    </script>
</body>

</html>