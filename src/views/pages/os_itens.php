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
<div class="header">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1>📋 Ordem de Serviço #<?= str_pad($os['id'], 5, '0', STR_PAD_LEFT) ?></h1>
            <?php if ($os['status'] === 'Em_Andamento') $class = 'badge-andamento';

            elseif ($os['status'] === 'Aberta') $class = 'badge-aberta';

            elseif ($os['status'] === 'Concluido') $class = 'badge-concluido';

            elseif ($os['status'] === 'Aguardando_Aprovacao') $class = 'badge-aguardandoaprovacao';

            elseif ($os['status'] === 'Aguardando_Pecas') $class = 'badge-aguardandopecas';

            elseif ($os['status'] === 'Cancelado') $class = 'badge-cancelado';
            ?>

            <form method="POST" action="/Os/processar">
                <input type="hidden" name="acao" value="editar_status">
                <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
                <span class="badge <?= $class ?>">
                    <label>Status:</label>
                    <select name="status" id="status" style="border-style: none; background: none;" class="<?= $class ?>" required>
                        <option value="Aberta">Aberta</option>
                        <option value="Em_Andamento">Em Andamento</option>
                        <option value="Aguardando_Pecas">Aguardando Peças</option>
                        <option value="Aguardando_Aprovacao">Aguardando Aprovação</option>
                        <option value="Concluido">Concluído</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </span>
                <button type="submit" id="mudar_status" class="btn btn-primary" style="display: none; width: 50px; height: 25px; padding: 0;">Mudar</button>
            </form>

        </div>
        <div>
            <?php if ($os['status'] == 'Concluido'): ?>
                <a href="/Os/imprimir/<?= $os['id'] ?>" target="_blank" class="btn btn-primary" style="margin-left: 10px;">🖨️ Imprimir OS</a>
            <?php endif; ?>
            <?php if ($os['status'] == 'Cancelado'): ?>
                <form method="POST" style="display: inline;" action="/Os/processar">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Remover Os?')">Excluir</button>
                </form>
            <?php endif; ?>
            <a href="/Os" class="btn btn-secondary">← Voltar</a>
        </div>
    </div>

    <div class="os-info">
        <div class="info-item">
            <span class="info-label">Cliente</span>
            <span class="info-value"><?= htmlspecialchars($os['cliente_nome']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Veículo</span>
            <span class="info-value"><?= htmlspecialchars($os['marca'] . ' ' . $os['modelo']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Placa</span>
            <span class="info-value"><?= htmlspecialchars($os['placa']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Data Abertura</span>
            <span class="info-value"><?= date('d/m/Y', strtotime($os['data_abertura'])) ?></span>
        </div>
    </div>
    <div>
        <?php if ($os['status'] != 'Concluido'): ?>
            <form method="POST" action="/Os/processar">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
                <div class="form-group">
                    <label>Descrição do Problema:</label>
                    <textarea name="descricao_problema" id="descricao_problema" style="width: 40%;" readonly><?= $os['descricao_problema'] ?></textarea>
                </div>

                <div class="form-group">
                    <label>Observações:</label>
                    <textarea name="observacoes" id="observacoes" style="width: 40%;" readonly><?= $os['observacoes'] ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary" id="btn-salvar" style="display: none;">Salvar</button>
                <button type="button" class="btn btn-secondary" id="btn-cancelar" style="display: none;">Cancelar</button>
            </form>
            <button class="btn btn-primary" id="btn-editar">Editar</button>
        <?php endif; ?>
    </div>
</div>

<?php if ($os['status'] !== 'Concluido' && $os['status'] !== 'Cancelado'): ?>
    <!-- Adicionar Produtos -->
    <div class="card">
        <h3 style="margin-bottom: 15px;">➕ Adicionar Produtos/Peças</h3>
        <form method="POST" class="form-inline" action="/Os/processar">
            <input type="hidden" name="acao" value="add_produto">
            <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
            <div class="form-group" style="flex: 2;">
                <label>Produto:</label>
                <select name="produto_id" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['descricao']) ?> - R$ <?= number_format($p['preco_venda'], 2, ',', '.') ?> (Estoque: <?= $p['quantidade'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Quantidade:</label>
                <input type="number" name="quantidade" min="1" value="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
    </div>

    <!-- Adicionar Serviços -->
    <div class="card">
        <h3 style="margin-bottom: 15px;">➕ Adicionar Serviços</h3>
        <form method="POST" class="form-inline" action="/Os/processar">
            <input type="hidden" name="acao" value="add_servico">
            <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
            <div class="form-group" style="flex: 2;">
                <label>Serviço:</label>
                <select name="servico_id" required>
                    <option value="">Selecione um serviço</option>
                    <?php foreach ($servicos as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['nome']) ?> - R$ <?= number_format($s['valor'], 2, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Quantidade:</label>
                <input type="number" name="quantidade" min="1" value="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
    </div>
<?php endif; ?>

<!-- Lista de Produtos -->
<div class="card">
    <h3 style="margin-bottom: 15px;">📦 Produtos/Peças</h3>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Valor Unit.</th>
                <th>Total</th>
                <?php if ($os['status'] !== 'Concluido' && $os['status'] !== 'Cancelado'): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos_os as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['codigo']) ?></td>
                    <td><?= htmlspecialchars($item['descricao']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                    <?php if ($os['status'] !== 'Concluido' && $os['status'] !== 'Cancelado'): ?>
                        <td>
                            <form method="POST" style="display: inline;" action="/Os/processar">
                                <input type="hidden" name="acao" value="remover_produto">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Remover este produto?')">Remover</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($produtos_os)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Nenhum produto adicionado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Lista de Serviços -->
<div class="card">
    <h3 style="margin-bottom: 15px;">🔨 Serviços</h3>
    <table>
        <thead>
            <tr>
                <th>Serviço</th>
                <th>Quantidade</th>
                <th>Valor Unit.</th>
                <th>Total</th>
                <?php if ($os['status'] !== 'Concluido' && $os['status'] !== 'Cancelado'): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicos_os as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                    <?php if ($os['status'] !== 'Concluido' && $os['status'] !== 'Cancelado'): ?>
                        <td>
                            <form method="POST" style="display: inline;" action="/Os/processar">
                                <input type="hidden" name="acao" value="remover_servico">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Remover este serviço?')">Remover</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($servicos_os)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #999;">Nenhum serviço adicionado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Totais -->
<div class="card">
    <h3 style="margin-bottom: 15px;">💰 Resumo Financeiro</h3>

    <?php if ($os['pago'] == 'Nao' && $os['status'] != 'Cancelado'): ?>
        <form method="POST" style="margin-bottom: 20px;" action="/Os/processar">
            <input type="hidden" name="acao" value="atualizar_valores">
            <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
            <div class="form-inline">
                <div class="form-group">
                    <label>Desconto (R$):</label>
                    <input type="number" name="desconto" step="0.01" min="0" value="<?= $os['desconto'] ?>">
                </div>
                <button type="submit" class="btn btn-success">Atualizar Valores</button>
            </div>
        </form>
    <?php endif; ?>

    <div class="totais">

        <div class="total-item">
            <span>Produtos/Peças:</span>
            <strong>R$ <?= number_format($os['valor_pecas'], 2, ',', '.') ?></strong>

        </div>
        <div class="total-item">
            <span>Serviços:</span>
            <strong>R$ <?= number_format($os['valor_servicos'], 2, ',', '.') ?></strong>
        </div>
        <div class="total-item">
            <span>Desconto:</span>
            <strong>- R$ <?= number_format($os['desconto'], 2, ',', '.') ?></strong>
        </div>
        <div class="total-item total-final">
            <span>TOTAL:</span>
            <strong>R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></strong>
        </div>

    </div>
</div>

<?php if ($os['pago'] == 'Nao' && $os['status'] != 'Cancelado'): ?>
    <!-- Finalizar OS -->
    <div class="card">
        <h3 style="margin-bottom: 15px;">✅ Finalizar Ordem de Serviço</h3>
        <p style="margin-bottom: 15px; color: #666;">
            Ao finalizar, os produtos serão baixados do estoque e a entrada será registrada no caixa.
        </p>
        <form method="POST" action="/Os/processar" onsubmit="return confirm('Confirma a finalização desta OS? Esta ação não pode ser desfeita!')">
            <input type="hidden" name="acao" value="finalizar_os">
            <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
            <div class="form-inline">
                <div class="form-group">
                    <label>Forma de Pagamento:</label>
                    <select name="forma_pagamento" required>
                        <option value="">Selecione</option>
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Cartao Debito">Cartão Débito</option>
                        <option value="Cartao Credito">Cartão Crédito</option>
                        <option value="PIX">PIX</option>
                        <option value="Transferencia">Transferência</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Finalizar OS</button>
            </div>
        </form>
    </div>
<?php endif; ?>
<script>
    const os_status = '<?= $os['status'] ?>'
    const btn_editar = document.getElementById('btn-editar');
    const btn_salvar = document.getElementById('btn-salvar');
    const btn_cancelar = document.getElementById('btn-cancelar');
    const descricao_problema = document.getElementById('descricao_problema');
    const observacoes = document.getElementById('observacoes');
    const status = document.getElementById('status');
    const btn_mudarStatus = document.getElementById('mudar_status');

    status.value = os_status;

    btn_editar.addEventListener('click', () => {
        btn_editar.style.display = 'none';
        btn_salvar.style.display = 'inline-block';
        btn_cancelar.style.display = 'inline-block';

        descricao_problema.readOnly = false;
        descricao_problema.style.background = 'aliceblue';

        observacoes.readOnly = false;
        observacoes.style.background = 'aliceblue';

    });

    btn_cancelar.addEventListener('click', () => {
        btn_salvar.style.display = 'none';
        btn_cancelar.style.display = 'none';
        btn_editar.style.display = 'inline-block';

        descricao_problema.readOnly = true;
        descricao_problema.style.background = 'white';

        observacoes.readOnly = true;
        observacoes.style.background = 'white';
    });

    status.addEventListener('change', () => {

        if (status.value != os_status) {
            btn_mudarStatus.style.display = 'inline-block';
        } else {
            btn_mudarStatus.style.display = 'none';
        }

    });
</script>
<?= $render('footer') ?>