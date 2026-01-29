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
<div class="page-header">
    <h1>📝 Gestão de Orçamentos</h1>
    <p class="breadcrumb">Dashboard / Orçamentos</p>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0;">Lista de Orçamentos</h2>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <!-- <div class="filtros">
                <a href="?status=Pendente" class="btn-filtro <?php //$status_filtro === 'Pendente' ? 'active' : '' 
                                                                ?>">Pendentes</a>
                <a href="?status=Aprovado" class="btn-filtro <?php //$status_filtro === 'Aprovado' ? 'active' : '' 
                                                                ?>">Aprovados</a>
                <a href="?status=Recusado" class="btn-filtro <?php //$status_filtro === 'Recusado' ? 'active' : '' 
                                                                ?>">Recusados</a>
                <a href="?status=Todos" class="btn-filtro <?php //$status_filtro === 'Todos' ? 'active' : '' 
                                                            ?>">Todos</a>
            </div> -->
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
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcamentos as $orc):
                    $dias_validade = (strtotime($orc['data_validade']) - time()) / 86400;
                ?>
                    <tr style="cursor:pointer;" ondblclick="window.location='orcamentos/itens?orcamento_id=<?= $orc['id'] ?>';">
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
                            <form method="POST" style="display: inline;" action="/orcamentos/processar" onsubmit="return confirm('Excluir este orçamento?')">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="orcamento_id" value="<?= $orc['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Excluir</button>
                            </form>
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

<div id="modal" class="modal">
    <div class="modal-content" style="max-width: 900px; padding: 15px;">

        <div class="modal-header" style="padding-bottom: 8px; margin-bottom: 10px;">
            <h2 style="font-size: 18px;">Novo Orçamento</h2>
            <button onclick="fecharModal()" class="btn-close">×</button>
        </div>

        <form method="POST" action="/orcamentos/processar">
            <input type="hidden" name="acao" value="cadastrar">

            <!-- CLIENTE -->
            <h3 style="font-size:14px;margin:10px 0 8px;border-bottom:1px solid #eee;">👤 Dados do Cliente</h3>

            <div class="form-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                <div class="form-group">
                    <label>Nome *</label>
                    <input type="text" name="cliente_nome" id="cliente_nome" required>
                </div>

                <div class="form-group">
                    <label>Telefone *</label>
                    <input type="text" name="cliente_telefone" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="cliente_email">
                </div>
            </div>

            <!-- VEÍCULO -->
            <h3 style="font-size:14px;margin:12px 0 8px;border-bottom:1px solid #eee;">🚗 Dados do Veículo</h3>

            <div class="form-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
                <div class="form-group">
                    <label>Marca *</label>
                    <select name="veiculo_marca">
                        <option value="">Selecione uma marca</option>
                        <option value="Audi">Audi</option>
                        <option value="Bmw">BMW</option>
                        <option value="Byd">BYD</option>
                        <option value="Chery">Chery</option>
                        <option value="Chevrolet">Chevrolet</option>
                        <option value="Citroen">Citroën</option>
                        <option value="Dodge">Dodge</option>
                        <option value="Ferrari">Ferrari</option>
                        <option value="Fiat">Fiat</option>
                        <option value="Ford">Ford</option>
                        <option value="Gwm">GWM</option>
                        <option value="Honda">Honda</option>
                        <option value="Hyundai">Hyundai</option>
                        <option value="Jac">JAC</option>
                        <option value="Jeep">Jeep</option>
                        <option value="Kia">Kia</option>
                        <option value="Land-rover">Land Rover</option>
                        <option value="Lexus">Lexus</option>
                        <option value="Mercedes-benz">Mercedes-Benz</option>
                        <option value="Mitsubishi">Mitsubishi</option>
                        <option value="Nissan">Nissan</option>
                        <option value="Peugeot">Peugeot</option>
                        <option value="Porsche">Porsche</option>
                        <option value="Ram">RAM</option>
                        <option value="Renault">Renault</option>
                        <option value="Subaru">Subaru</option>
                        <option value="Suzuki">Suzuki</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Volkswagen">Volkswagen</option>
                        <option value="Volvo">Volvo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Modelo *</label>
                    <input type="text" name="veiculo_modelo" required>
                </div>

                <div class="form-group">
                    <label>Ano *</label>
                    <input type="text" name="veiculo_ano" maxlength="4" required>
                </div>

                <div class="form-group">
                    <label>Placa</label>
                    <input type="text" name="veiculo_placa" id="veiculo_placa">
                </div>
            </div>

            <!-- ORÇAMENTO -->
            <h3 style="font-size:14px;margin:12px 0 8px;border-bottom:1px solid #eee;">📋 Informações do Orçamento</h3>

            <div class="form-group">
                <label>Descrição do Serviço *</label>
                <textarea name="descricao_servico" rows="2" required></textarea>
            </div>

            <div class="form-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;">
                <div class="form-group">
                    <label>Data *</label>
                    <input type="date" name="data_orcamento" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label>Validade (dias)</label>
                    <input type="number" name="validade_dias" value="7" min="1" required>
                </div>
            </div>

            <div class="form-group">
                <label>Observações</label>
                <textarea name="observacoes" rows="2"></textarea>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer" style="margin-top:10px;padding-top:8px;">
                <button type="submit" class="btn btn-primary">Criar Orçamento</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
            </div>

        </form>
    </div>
</div>



<script>
    function abrirModal() {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('cliente_nome').autofocus = true;

        const placa = document.getElementById('veiculo_placa');
        placa.addEventListener('input', () => {
            placa.value = placa.value.toUpperCase();
        });
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