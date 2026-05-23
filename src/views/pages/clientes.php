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
<header>
    <h1>👥 Gestão de Clientes</h1>
    <div>
        <button onclick="window.location.href='/'" class="btn btn-secondary">← Voltar</button>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Cliente</button>
    </div>
</header>

<div class="card">
    <h2 style="margin-bottom: 20px;">Lista de Clientes</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nome']) ?></td>
                    <td><?= empty($cliente['cpf_cnpj']) ? '' : htmlspecialchars($cliente['cpf_cnpj']) ?></td>
                    <td><?= empty($cliente['telefone']) ? '' : htmlspecialchars($cliente['telefone']) ?></td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-success" onclick='editarCliente(<?= json_encode($cliente) ?>)'>Editar</button>
                            <form method="POST" action="/clientes/processar" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir?')">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content" style="max-width: 900px; padding: 15px;">

        <div class="modal-header" style="padding-bottom: 8px; margin-bottom: 10px;">
            <h2 id="modal-title">Cadastrar Cliente</h2>
            <button onclick="fecharModal()" class="btn-close">×</button>
        </div>
        <form method="POST" action="/clientes/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="cliente-id">

            <div class="form-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                <div class="form-group">
                    <label>Nome Completo:*</label>
                    <input type="text" name="nome" id="nome" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label>CPF/CNPJ:</label>
                    <input type="text" name="cpf" id="cpf">
                </div>

                <div class="form-group">
                    <label>Telefone:*</label>
                    <input type="text" name="telefone" id="telefone" required autocomplete="off">
                </div>
            </div>

            <div class="form-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                <div class="form-group">
                    <label>Cep:</label>
                    <input type="text" name="cep" id="cep">
                </div>

                <div class="form-group">
                    <label>Endereço:</label>
                    <input type="text" name="endereco" id="endereco">
                </div>

                <div class="form-group">
                    <label>Numero:</label>
                    <input type="text" name="numero" id="numero">
                </div>

                <div class="form-group">
                    <label>Bairro:</label>
                    <input type="text" name="bairro" id="bairro">
                </div>

                <div class="form-group">
                    <label>Cidade:</label>
                    <input type="text" name="cidade" id="cidade">
                </div>

                <div class="form-group">
                    <label>Estado:</label>
                    <select name="estado" id="estado">
                        <option value="">Selecione um estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<script>
    function abrirModal() {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Cadastrar Cliente';
        document.getElementById('acao').value = 'cadastrar';
        document.querySelector('form').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarCliente(cliente) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Cliente';
        document.getElementById('acao').value = 'editar';
        document.getElementById('cliente-id').value = cliente.id;
        document.getElementById('nome').value = cliente.nome;
        document.getElementById('cpf').value = cliente.cpf_cnpj;
        document.getElementById('telefone').value = cliente.telefone;
        document.getElementById('email').value = cliente.email || '';
        document.getElementById('endereco').value = cliente.endereco || '';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }

    const buscaCep = document.getElementById('cep');

    buscaCep.addEventListener('blur', () => {

        var cep = '';

        if (buscaCep.value.length >= 8) {

            cep = buscaCep.value.replace('-', '');

            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => {
                        if (!response.ok) {
                            throw new error('Erro' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('endereco').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('endereco').value = data.logradouro;
                            document.getElementById('estado').value = data.uf;
                        }
                    })
                    .catch(Error => {
                        console.error(Error);
                    });
            }
        }

    });
</script>
<?= $render('footer') ?>