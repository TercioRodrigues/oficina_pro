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
    <h1>🚗 Gestão de Veículos</h1>
    <div>
        <a href="/dashboard" class="btn btn-secondary">← Voltar</a>
        <button class="btn btn-primary" onclick="abrirModal()">+ Novo Veículo</button>
    </div>
</header>

<div class="card">
    <h2 style="margin-bottom: 20px;">Lista de Veículos</h2>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Ano</th>
                <th>Cor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($veiculos as $veiculo): ?>
                <tr>
                    <td><?= $veiculo['cliente_nome'] ?></td>
                    <td><?= $veiculo['placa'] ?></td>
                    <td><?= $veiculo['marca'] ?></td>
                    <td><?= $veiculo['modelo'] ?></td>
                    <td><?= $veiculo['ano'] ?></td>
                    <td><?= $veiculo['cor'] ?? '' ?></td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-success" onclick='editarVeiculo(<?= json_encode($veiculo) ?>)'>Editar</button>
                            <button class="btn btn-primary" onclick="window.location.href='/veiculos/historico/<?= $veiculo['id'] ?>'">Histórico</button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir?')" action="/veiculos/processar">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="veiculo_id" value="<?= $veiculo['id'] ?>">
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
    <div class="modal-content">
        <h2 id="modal-title">Cadastrar Veículo</h2>
        <form method="POST" action="/veiculos/processar" id="cadastrar_editar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="veiculo_id" id="veiculo_id">

            <div class="form-group" style="flex: 2;">
                <label>Cliente</label>
                <div class="busca-cliente">
                    <input type="text"
                        id="buscar-cliente"
                        placeholder="Nome do cliente"
                        autocomplete="off">

                    <input type="hidden"
                        name="cliente_id"
                        id="cliente_id"
                        required>

                    <div id="resultados-cliente"
                        class="resultados-cliente"
                        style="display:none;"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Placa:</label>
                <input type="text" name="placa" id="placa" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="marca">Escolha a marca do veículo:</label>
                    <select name="marca" id="marca">
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
                    <label>Modelo:</label>
                    <input type="text" name="modelo" id="modelo" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ano:</label>
                    <input type="number" name="ano" id="ano" required>
                </div>

                <div class="form-group">
                    <label for="cor">Cor do veículo:</label>
                    <select name="cor" id="cor">
                        <option value="">Selecione uma cor</option>
                        <option value="Branco">Branco</option>
                        <option value="Preto">Preto</option>
                        <option value="Cinza">Cinza</option>
                        <option value="Prata">Prata</option>
                        <option value="Azul">Azul</option>
                        <option value="Vermelho">Vermelho</option>
                        <option value="Verde">Verde</option>
                        <option value="Amarelo">Amarelo</option>
                        <option value="Bege">Bege</option>
                        <option value="Marrom">Marrom</option>
                        <option value="Laranja">Laranja</option>
                        <option value="Rosa">Rosa</option>
                        <option value="Roxo">Roxo</option>
                        <option value="Fantasia">Fantasia (Multicolorido)</option>
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
        document.getElementById('modal-title').textContent = 'Cadastrar Veículo';
        document.getElementById('acao').value = 'cadastrar';
        document.getElementById('cadastrar_editar').reset();

        const placa = document.getElementById('placa');
        placa.addEventListener('input', () => {
            placa.value = placa.value.toUpperCase();
        });
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function editarVeiculo(veiculo) {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Editar Veículo';
        document.getElementById('acao').value = 'editar';
        document.getElementById('veiculo_id').value = veiculo.id;
        document.getElementById('buscar-cliente').value = veiculo.cliente_nome;
        document.getElementById('cliente_id').value = veiculo.cliente_id;
        document.getElementById('placa').value = veiculo.placa;
        document.getElementById('marca').value = veiculo.marca;
        document.getElementById('modelo').value = veiculo.modelo;
        document.getElementById('ano').value = veiculo.ano;
        document.getElementById('cor').value = veiculo.cor || '';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }


    const clientes = <?= json_encode($clientes) ?>;

    const buscaCliente = document.getElementById('buscar-cliente');
    const cliente_id = document.getElementById('cliente_id');
    const resultadosCliente = document.getElementById('resultados-cliente');

    buscaCliente.addEventListener('input', () => {

        const termo = buscaCliente.value.toLowerCase();

        resultadosCliente.innerHTML = '';

        if (termo.length < 1) {
            resultadosCliente.style.display = 'none';
            return;
        }

        const filtrados = clientes.filter(c =>
            c.nome.toLowerCase().includes(termo)
        );

        if (filtrados.length === 0) {
            resultadosCliente.style.display = 'none';
            return;
        }

        resultadosCliente.style.display = 'block';

        filtrados.forEach(c => {

            const div = document.createElement('div');

            div.className = 'item-produto-busca';

            div.innerHTML = `
    <div class="produto-info">
        <strong>${c.nome} ${c.cpf_cnpj ? ` - ${c.cpf_cnpj} -` : ''} ${c.telefone ? ` - ${c.telefone}` : ''}</strong>
    </div>
        `;

            div.onclick = () => {

                buscaCliente.value = c.nome;
                cliente_id.value = c.id;

                resultadosCliente.style.display = 'none';
            };

            resultadosCliente.appendChild(div);

        });

    });
</script>
<?= $render('footer') ?>