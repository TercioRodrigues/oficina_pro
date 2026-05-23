<?= $render('header') ?>
<?php if (!empty($_SESSION['mensagem'])): ?>
    <div id="aviso" class="mensagem hide">
        <?php echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);  ?></div>
    <script>
        const aviso = document.getElementById('aviso');
        aviso.classList.remove('hide');

        setTimeout(() => {
            aviso.classList.add('hide');
        }, 5000);
    </script>
<?php endif; ?>
<div class="page-header">
    <h1>👥 Gestão de Usuários do Sistema</h1>
    <p class="breadcrumb">Início / Configurações / Usuários</p>
</div>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Usuários do Sistema</h2>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Usuário</button>
    </div>

    <div class="info-box">
        <strong>ℹ️ Informação:</strong> Usuários do sistema têm acesso ao painel administrativo.
        Para mecânicos que não precisam acessar o sistema, cadastre apenas como funcionários.
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Nível de Acesso</th>
                    <th>Status</th>
                    <th>Último Acesso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($user['nome']) ?></strong>
                            <?php if ($user['id'] == $_SESSION['usuario_id']): ?>
                                <span class="badge badge-info">Você</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['telefone'] ?? '-') ?></td>
                        <td>
                            <span class="badge badge-nivel-<?= strtolower($user['nivel']) ?>">
                                <?= $user['nivel'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['ativo']): ?>
                                <span class="badge badge-ativo">Ativo</span>
                            <?php else: ?>
                                <span class="badge badge-inativo">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $user['ultimo_acesso'] ? date('d/m/Y H:i:s', strtotime($user['ultimo_acesso'])) : 'Nunca' ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-sm btn-info" onclick='editarUsuario(<?= json_encode($user) ?>)' title="Editar">
                                    ✏️
                                </button>
                                <form method="POST" action="/usuarios/processar" style="display: inline;" onsubmit="return confirm('Será gerado uma senha aleatória, deseja continuar?')">
                                    <input type="hidden" name="acao" value="resetar_senha">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-warning" title="Resetar Senha">🔑</button>
                                </form>
                                <?php if ($user['id'] != $_SESSION['usuario_id']): ?>
                                    <form method="POST" action="/usuarios/processar" style="display: inline;" onsubmit="return confirm('Excluir este usuário?')">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Excluir">🗑️</button>
                                    </form>
                                <?php endif; ?>
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
            <h2 id="modal-title">Cadastrar Usuário</h2>
            <button onclick="fecharModal()" class="btn-close">×</button>
        </div>

        <form method="POST" action="/usuarios/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="user-id">

            <div class="form-grid">
                <div class="form-group">
                    <label>Nome Completo: *</label>
                    <input type="text" name="nome" id="nome" required>
                </div>

                <div class="form-group">
                    <label>Email: *</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label>Telefone:</label>
                    <input type="text" name="telefone" id="telefone">
                </div>

                <div class="form-group">
                    <label>Nível de Acesso: *</label>
                    <select name="nivel" id="nivel" required>
                        <option value="Admin">Admin (Acesso Total)</option>
                        <option value="Gerente">Gerente (Gestão)</option>
                        <option value="Atendente">Atendente (Operacional)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Senha: <span id="senha-opcional" style="display: none; color: #7f8c8d;">(deixe em branco para manter)</span></label>
                    <input type="password" name="senha" id="senha">
                    <small style="color: #7f8c8d;">Mínimo 6 caracteres</small>
                </div>

                <div class="form-group">
                    <label>Status: *</label>
                    <select name="ativo" id="ativo" required>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
            </div>

            <div class="info-box" style="margin-top: 20px;">
                <strong>💡 Dica:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    <li><strong>Admin:</strong> Acesso total ao sistema, incluindo configurações</li>
                    <li><strong>Gerente:</strong> Pode gerenciar OS, estoque, compras e visualizar relatórios</li>
                    <li><strong>Atendente:</strong> Pode criar OS, cadastrar clientes e agendar serviços</li>
                </ul>
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
        document.getElementById('modal-title').textContent = 'Cadastrar Usuário';
        document.getElementById('acao').value = 'cadastrar';
        document.getElementById('senha-opcional').style.display = 'none';
        document.getElementById('senha').required = true;
        document.querySelector('form').reset();
        document.getElementById('nivel').value = 'Atendente';
        document.getElementById('ativo').value = '1';
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarUsuario(user) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Usuário';
        document.getElementById('acao').value = 'editar';
        document.getElementById('senha-opcional').style.display = 'inline';
        document.getElementById('senha').required = false;
        document.getElementById('user-id').value = user.id;
        document.getElementById('nome').value = user.nome;
        document.getElementById('email').value = user.email;
        document.getElementById('telefone').value = user.telefone || '';
        document.getElementById('nivel').value = user.nivel;
        document.getElementById('ativo').value = user.ativo;
        document.getElementById('senha').value = '';
    }

    window.onclick = function(event) {
        if (event.target === document.getElementById('modal')) {
            fecharModal();
        }
    }
</script>
<?= $render('footer') ?>