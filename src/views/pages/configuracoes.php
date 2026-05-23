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
    <h1>⚙️ Configurações da Empresa</h1>
    <p class="breadcrumb">Início / Configurações</p>
</div>

<!-- ========================================
     FORMULÁRIO PRINCIPAL (dados da empresa)
     ======================================== -->
<form method="POST" action="/configuracoes/processar">

    <div class="card">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">📋 Dados da Empresa</h2>

        <div class="form-grid">
            <div class="form-group">
                <label>Razão Social: *</label>
                <input type="text" name="razao_social" value="<?= empty($config['razao_social']) ? '' : htmlspecialchars($config['razao_social']) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Nome Fantasia: *</label>
                <input type="text" name="nome_fantasia" value="<?= empty($config['nome_fantasia']) ? '' : htmlspecialchars($config['nome_fantasia']) ?>" required>
            </div>

            <div class="form-group">
                <label>CNPJ: *</label>
                <input type="text" name="cnpj" value="<?= empty($config['cnpj']) ? '' : htmlspecialchars($config['cnpj']) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Inscrição Estadual:</label>
                <input type="text" name="inscricao_estadual" value="<?= empty($config['inscricao_estadual']) ? '' : htmlspecialchars($config['inscricao_estadual']) ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">📞 Contato</h2>

        <div class="form-grid">
            <div class="form-group">
                <label>Telefone: *</label>
                <input type="text" name="telefone" value="<?= empty($config['telefone']) ? '' : htmlspecialchars($config['telefone']) ?>" required>
            </div>

            <div class="form-group">
                <label>WhatsApp:</label>
                <input type="text" name="whatsapp" value="<?= empty($config['whatsapp']) ? '' : htmlspecialchars($config['whatsapp']) ?>">
            </div>

            <div class="form-group">
                <label>Email: *</label>
                <input type="email" name="email" value="<?= empty($config['email']) ? '' : htmlspecialchars($config['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Site:</label>
                <input type="text" name="site" value="<?= empty($config['site']) ? '' : htmlspecialchars($config['site']) ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">📍 Endereço</h2>

        <div class="form-grid">
            <div class="form-group">
                <label>CEP:</label>
                <input type="text" name="cep" id="cep" value="<?= empty($config['cep']) ? '' : htmlspecialchars($config['cep']) ?>">
            </div>

            <div class="form-group">
                <label>Endereço:</label>
                <input type="text" name="endereco" id="endereco" value="<?= empty($config['endereco']) ? '' : htmlspecialchars($config['endereco']) ?>">
            </div>

            <div class="form-group">
                <label>Número:</label>
                <input type="text" name="numero" id="numero" value="<?= empty($config['numero']) ? '' : htmlspecialchars($config['numero']) ?>">
            </div>

            <div class="form-group">
                <label>Complemento:</label>
                <input type="text" name="complemento" id="complemento" value="<?= empty($config['complemento']) ? '' : htmlspecialchars($config['complemento']) ?>">
            </div>

            <div class="form-group">
                <label>Bairro:</label>
                <input type="text" name="bairro" id="bairro" value="<?= empty($config['bairro']) ? '' : htmlspecialchars($config['bairro']) ?>">
            </div>

            <div class="form-group">
                <label>Cidade:</label>
                <input type="text" name="cidade" id="cidade" value="<?= empty($config['cidade']) ? '' : htmlspecialchars($config['cidade']) ?>">
            </div>

            <div class="form-group">
                <label>Estado (UF):</label>
                <input type="text" name="estado" id="estado" value="<?= empty($config['estado']) ? '' : htmlspecialchars($config['estado']) ?>" maxlength="2">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">🕐 Informações Adicionais</h2>

        <div class="form-group">
            <label>Horário de Funcionamento:</label>
            <textarea name="horario_funcionamento" rows="3"><?= empty($config['horario_funcionamento']) ? '' : htmlspecialchars($config['horario_funcionamento']) ?></textarea>
            <small style="color: #7f8c8d;">Ex: Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h</small>
        </div>

        <div class="form-group">
            <label>Observações:</label>
            <textarea name="observacoes" rows="4"><?= empty($config['observacoes']) ? '' : htmlspecialchars($config['observacoes']) ?></textarea>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1em;">
            💾 Salvar Configurações
        </button>
    </div>
</form>

<!-- ========================================
     FORMULÁRIO SEPARADO: Upload da Logo
     (enctype obrigatório para envio de arquivo)
     ======================================== -->
<form method="POST" action="/configuracoes/upload/logo" enctype="multipart/form-data" id="form-logo">
    <div class="card" style="margin-top: 30px;">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">🖼️ Logo da Empresa</h2>

        <div style="display: flex; gap: 40px; align-items: flex-start; flex-wrap: wrap;">

            <!-- Logo atual -->
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <span style="font-size: 0.9em; font-weight: 600; color: #7f8c8d; text-transform: uppercase; letter-spacing: 0.5px;">Logo Atual</span>
                <div id="logo-atual-container" style="width: 160px; height: 160px; border: 2px dashed #ddd; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: #fafafa; overflow: hidden;">
                    <?php if (!empty($config['logo'])): ?>
                        <img id="logo-atual"
                            src="<?= htmlspecialchars($config['logo']) ?>"
                            alt="Logo atual"
                            style="max-width: 100%; max-height: 100%; object-fit: contain; padding: 8px;">
                    <?php else: ?>
                        <span id="logo-placeholder" style="font-size: 2.5em; color: #ccc;">🏢</span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($config['logo'])): ?>
                    <small style="color: #7f8c8d; font-size: 0.8em; text-align: center;">
                        <?= htmlspecialchars(basename($config['logo'])) ?>
                    </small>
                <?php endif; ?>
            </div>

            <!-- Controles de upload -->
            <div style="flex: 1; min-width: 220px; display: flex; flex-direction: column; gap: 15px; justify-content: center;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Selecionar nova logo:</label>
                    <input type="file"
                        name="logo"
                        id="input-logo"
                        accept="image/png, image/jpeg, image/webp, image/svg+xml"
                        style="padding: 8px; border: 1px solid #ddd; border-radius: 8px; width: 100%; cursor: pointer;">
                    <small style="color: #7f8c8d;">Formatos aceitos: PNG, JPG, WEBP, SVG. Tamanho máximo: 2 MB.</small>
                </div>

                <!-- Preview da imagem selecionada -->
                <div id="preview-container" style="display: none; flex-direction: column; gap: 8px;">
                    <span style="font-size: 0.85em; color: #667eea; font-weight: 600;">📋 Pré-visualização:</span>
                    <img id="preview-logo"
                        src=""
                        alt="Pré-visualização"
                        style="max-width: 160px; max-height: 100px; object-fit: contain; border: 1px solid #ddd; border-radius: 8px; padding: 6px; background: #fafafa;">
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary">
                        📤 Enviar Logo
                    </button>
                    <?php if (!empty($config['logo'])): ?>
                        <a href="/configuracoes/remover/logo"
                            class="btn btn-danger"
                            onclick="return confirm('Deseja remover a logo atual?')">
                            🗑️ Remover Logo
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
    /* ── CEP Autocomplete ── */
    const meuCep = document.getElementById('cep');
    var cep = '';
    meuCep.addEventListener('input', () => {
        if (meuCep.value.length >= 8) {
            cep = meuCep.value.length > 8 ?
                meuCep.value.replace(/[.,\-_]/g, '') :
                meuCep.value;

            fetch(`https://viacep.com.br/ws/${cep}/json`)
                .then(response => {
                    if (!response.ok) throw new Error('Erro na rede: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('endereco').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;
                        document.getElementById('complemento').value = data.complemento;
                    } else {
                        alert('CEP não encontrado, clique para continuar');
                    }
                })
                .catch(error => console.error(error));
        }
    });

    /* ── Preview da logo antes do envio ── */
    const inputLogo = document.getElementById('input-logo');
    const previewContainer = document.getElementById('preview-container');
    const previewLogo = document.getElementById('preview-logo');

    inputLogo.addEventListener('change', () => {
        const arquivo = inputLogo.files[0];

        if (!arquivo) {
            previewContainer.style.display = 'none';
            return;
        }

        // Validação de tamanho (2 MB)
        if (arquivo.size > 2 * 1024 * 1024) {
            alert('A imagem deve ter no máximo 2 MB.');
            inputLogo.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewLogo.src = e.target.result;
            previewContainer.style.display = 'flex';
        };
        reader.readAsDataURL(arquivo);
    });
</script>

<?= $render('footer') ?>