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
    <h1>⚙️ Configurações da Empresa</h1>
    <p class="breadcrumb">Início / Configurações</p>
</div>

<form method="POST" action="/configuracoes/processar">
    <div class="card">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">📋 Dados da Empresa</h2>

        <div class="form-grid">
            <div class="form-group">
                <label>Razão Social: *</label>
                <input type="text" name="razao_social" value="<?= htmlspecialchars($config['razao_social']) ?>" required>
            </div>

            <div class="form-group">
                <label>Nome Fantasia: *</label>
                <input type="text" name="nome_fantasia" value="<?= htmlspecialchars($config['nome_fantasia']) ?>" required>
            </div>

            <div class="form-group">
                <label>CNPJ: *</label>
                <input type="text" name="cnpj" value="<?= htmlspecialchars($config['cnpj']) ?>" required>
            </div>

            <div class="form-group">
                <label>Inscrição Estadual:</label>
                <input type="text" name="inscricao_estadual" value="<?= htmlspecialchars($config['inscricao_estadual']) ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">📞 Contato</h2>

        <div class="form-grid">
            <div class="form-group">
                <label>Telefone: *</label>
                <input type="text" name="telefone" value="<?= htmlspecialchars($config['telefone']) ?>" required>
            </div>

            <div class="form-group">
                <label>WhatsApp:</label>
                <input type="text" name="whatsapp" value="<?= htmlspecialchars($config['whatsapp']) ?>">
            </div>

            <div class="form-group">
                <label>Email: *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($config['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Site:</label>
                <input type="text" name="site" value="<?= htmlspecialchars($config['site']) ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">📍 Endereço</h2>

        <div class="form-grid">
            <div class="form-group">
                <label>CEP:</label>
                <input type="text" name="cep" id="cep" value="<?= htmlspecialchars($config['cep']) ?>">
            </div>

            <div class="form-group">
                <label>Endereço:</label>
                <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($config['endereco']) ?>">
            </div>

            <div class="form-group">
                <label>Número:</label>
                <input type="text" name="numero" id="numero" value="<?= htmlspecialchars($config['numero']) ?>">
            </div>

            <div class="form-group">
                <label>Complemento:</label>
                <input type="text" name="complemento" id="complemento" value="<?= htmlspecialchars($config['complemento']) ?>">
            </div>

            <div class="form-group">
                <label>Bairro:</label>
                <input type="text" name="bairro" id="bairro" value="<?= htmlspecialchars($config['bairro']) ?>">
            </div>

            <div class="form-group">
                <label>Cidade:</label>
                <input type="text" name="cidade" id="cidade" value="<?= htmlspecialchars($config['cidade']) ?>">
            </div>

            <div class="form-group">
                <label>Estado (UF):</label>
                <input type="text" name="estado" id="estado" value="<?= htmlspecialchars($config['estado']) ?>" maxlength="2">
            </div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">🕐 Informações Adicionais</h2>

        <div class="form-group">
            <label>Horário de Funcionamento:</label>
            <textarea name="horario_funcionamento" rows="3"><?= htmlspecialchars($config['horario_funcionamento']) ?></textarea>
            <small style="color: #7f8c8d;">Ex: Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h</small>
        </div>

        <div class="form-group">
            <label>Observações:</label>
            <textarea name="observacoes" rows="4"><?= htmlspecialchars($config['observacoes']) ?></textarea>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1em;">
            💾 Salvar Configurações
        </button>
    </div>
</form>

<script>
    const meuCep = document.getElementById('cep');
    var cep = '';
    meuCep.addEventListener('input', () => {
        if (meuCep.value.length >= 8) {

            if (meuCep.value.length > 8) {
                cep = meuCep.value.replace(['.', ',', '-', '_']);
            } else {
                cep = meuCep.value;
            }

            fetch(`https://viacep.com.br/ws/${cep}/json`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na rede: ' + response.status);
                    }
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
                        alert('Cep não encontrado, clique para continuar');
                    }
                })
                .catch(Error => {
                    console.Error(Error)
                });
        }
    });
</script>

<?= $render('footer') ?>