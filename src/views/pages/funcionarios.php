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
    <h1>👷 Gestão de Funcionários</h1>
    <p class="breadcrumb">Início / Funcionários</p>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0;">Lista de Funcionários</h2>
        </div>

        <div class="filtros">
            <a href="?status=Ativo" class="btn-filtro <?= $filtro_status === 'Ativo' ? 'active' : '' ?>">Ativos</a>
            <a href="?status=Inativo" class="btn-filtro <?= $filtro_status === 'Inativo' ? 'active' : '' ?>">Inativos</a>
            <a href="?status=Todos" class="btn-filtro <?= $filtro_status === 'Todos' ? 'active' : '' ?>">Todos</a>
        </div>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Funcionário</button>

    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Cargo</th>
                    <th>Especialidade</th>
                    <th>Telefone</th>
                    <th>Admissão</th>
                    <th>Salário</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($funcionarios as $func): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($func['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($func['cpf']) ?></td>
                        <td><?= $func['cargo'] ?></td>
                        <td><?= htmlspecialchars($func['especialidade']) ?></td>
                        <td><?= htmlspecialchars($func['telefone']) ?></td>
                        <td><?= date('d/m/Y', strtotime($func['data_admissao'])) ?></td>
                        <td>R$ <?= number_format($func['salario'], 2, ',', ',') ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($func['status']) ?>">
                                <?= $func['status'] ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-sm btn-info" onclick='editarFuncionario(<?= json_encode($func) ?>)'>✏️</button>
                                <form method="POST" action="/funcionarios/processar" style="display: inline;" onsubmit="return confirm('Excluir este funcionário?')">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?= $func['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Cadastrar Funcionário</h2>
            <button onclick="fecharModal()" class="btn-close">×</button>
        </div>

        <form method="POST" action="/funcionarios/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="func-id">

            <div class="form-grid">
                <div class="form-group">
                    <label>Nome Completo: *</label>
                    <input type="text" name="nome" id="nome" required>
                </div>

                <div class="form-group">
                    <label>CPF: *</label>
                    <input type="text" name="cpf" id="cpf" required>
                </div>

                <div class="form-group">
                    <label>RG:</label>
                    <input type="text" name="rg" id="rg">
                </div>

                <div class="form-group">
                    <label>Data de Nascimento:</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" value="<?= date('Y-m-d', strtotime('-18 Year')) ?>">
                </div>

                <div class="form-group">
                    <label>Telefone: *</label>
                    <input type="text" name="telefone" id="telefone" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" id="email">
                </div>

                <div class="form-group">
                    <label>Cargo: *</label>
                    <select name="cargo" id="cargo" required>
                        <option value="">Selecione</option>
                        <option value="Mecânico">Mecânico</option>
                        <option value="Auxiliar">Auxiliar</option>
                        <option value="Gerente">Gerente</option>
                        <option value="Recepcionista">Recepcionista</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Especialidade:</label>
                    <input type="text" name="especialidade" id="especialidade" placeholder="Ex: Motor, Suspensão">
                </div>

                <div class="form-group">
                    <label>Salário:</label>
                    <input type="number" name="salario" id="salario" step="0.01" min="0">
                </div>

                <div class="form-group">
                    <label>Data de Admissão: *</label>
                    <input type="date" name="data_admissao" id="data_admissao" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label>Status: *</label>
                    <select name="status" id="status" required>
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                        <option value="Férias">Férias</option>
                        <option value="Afastado">Afastado</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Endereço:</label>
                <textarea name="endereco" id="endereco" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label>Observações:</label>
                <textarea name="observacoes" id="observacoes" rows="3"></textarea>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">💾 Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>




<script>
    function abrirModal() {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Cadastrar Funcionário';
        document.getElementById('acao').value = 'cadastrar';
        document.querySelector('form').reset();
        document.getElementById('status').value = 'Ativo';
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarFuncionario(func) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Funcionário';
        document.getElementById('acao').value = 'editar';
        document.getElementById('func-id').value = func.id;
        document.getElementById('nome').value = func.nome;
        document.getElementById('cpf').value = func.cpf;
        document.getElementById('rg').value = func.rg || '';
        document.getElementById('data_nascimento').value = func.data_nascimento || '';
        document.getElementById('telefone').value = func.telefone;
        document.getElementById('email').value = func.email || '';
        document.getElementById('cargo').value = func.cargo;
        document.getElementById('especialidade').value = func.especialidade || '';
        document.getElementById('salario').value = func.salario || '';
        document.getElementById('data_admissao').value = func.data_admissao;
        document.getElementById('status').value = func.status;
        document.getElementById('endereco').value = func.endereco || '';
        document.getElementById('observacoes').value = func.observacoes || '';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>