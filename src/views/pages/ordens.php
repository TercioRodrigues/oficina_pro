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
    <h1>📋 Ordens de Serviço</h1>
    <div>
        <button onclick="window.location.href='/'" class="btn btn-secondary">← Voltar</button>
        <button class="btn btn-primary" onclick="abrirModal()">+ Nova OS</button>
    </div>

</header>

<div class="card">
    <div class="filtros-lista-ordens">
        <h2 style="margin-bottom: 20px;">Lista de Ordens de Serviço</h2>
        <div style="display: flex; gap: 10px; align-items: center;">
            <div class="filtros">
                <button onclick="window.location.href='?status=Aberta';" class="btn btn-filtro <?= $status_filtro === 'Aberta' ? 'active' : '' ?>">Aberta</button>
                <button onclick="window.location.href='?status=Em_Andamento';" class="btn btn-filtro <?= $status_filtro === 'Em_Andamento' ? 'active' : '' ?>">Em Andamento</button>
                <button onclick="window.location.href='?status=Aguardando_Pecas';" class="btn btn-filtro <?= $status_filtro === 'Aguardando_Pecas' ? 'active' : '' ?>">Aguardando Peças</button>
                <button onclick="window.location.href='?status=Aguardando_Aprovacao';" class="btn btn-filtro <?= $status_filtro === 'Aguardando_Aprovacao' ? 'active' : '' ?>">Aguardando Aprovação</button>
                <button onclick="window.location.href='?status=Concluido';" class="btn btn-filtro <?= $status_filtro === 'Concluido' ? 'active' : '' ?>">Concluído</button>
                <button onclick="window.location.href='?status=Cancelado';" class="btn btn-filtro <?= $status_filtro === 'Cancelado' ? 'active' : '' ?>">Cancelado</button>
            </div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>OS #</th>
                <th>Cliente</th>
                <th>Veículo</th>
                <th>Data Abertura</th>
                <th>Status</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordens as $ordem): ?>
                <tr style="cursor: pointer;" onclick="window.location.href='/Os/itens?os_id=<?= $ordem['id'] ?>';">
                    <td>#<?= str_pad($ordem['os_id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($ordem['cliente_nome']) ?></td>
                    <td><?= htmlspecialchars($ordem['marca'] . ' ' . $ordem['modelo'] . ' - ' . $ordem['placa']) ?></td>
                    <td><?= date('d/m/Y', strtotime($ordem['data_abertura'])) ?></td>
                    <td>
                        <?php if ($ordem['status'] === 'Em_Andamento') {
                            $class = 'badge-andamento';
                            $status = 'Em Andamento';
                        } elseif ($ordem['status'] === 'Aberta') {
                            $class = 'badge-aberta';
                            $status = 'Aberta';
                        } elseif ($ordem['status'] === 'Concluido') {
                            $class = 'badge-concluido';
                            $status = 'Concluído';
                        } elseif ($ordem['status'] === 'Aguardando_Aprovacao') {
                            $class = 'badge-aguardandoaprovacao';
                            $status = 'Aguardando Aprovação';
                        } elseif ($ordem['status'] === 'Aguardando_Pecas') {
                            $class = 'badge-aguardandopecas';
                            $status = 'Aguardando Peças';
                        } elseif ($ordem['status'] === 'Cancelado') {
                            $class = 'badge-cancelado';
                            $status = $ordem['status'];
                        }
                        ?>
                        <span class="badge <?= $class ?>"><?= $status ?></span>
                    </td>
                    <td>R$ <?= number_format($ordem['valor_total'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h2 id="modal-title">Nova Ordem de Serviço</h2>
        <form method="POST" action="/Os/processar">
            <input type="hidden" name="acao" id="acao" value="cadastrar">
            <input type="hidden" name="id" id="ordem-id">

            <div class="form-row">
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
                    <label>Veículo:</label>
                    <select name="veiculo_id" id="veiculo_id" required>
                    </select>
                </div>

                <div class="form-group">
                    <label for="km_atual">Km Atual*</label>
                    <input type="text" name="km" id="km_atual" required>
                </div>

                <div class="form-group">
                    <label for="mecanico">Mecânico*</label>
                    <input type="text" id="mecanico" name="mecanico" required>
                </div>
            </div>

            <div class="form-group">
                <label>Descrição do Problema:*</label>
                <textarea name="descricao_problema" id="descricao_problema" required></textarea>
            </div>

            <div class="form-group">
                <label>Observações:</label>
                <textarea name="observacoes" id="observacoes"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Valor Total (R$):</label>
                    <input type="number" name="valor_total" id="valor_total" step="0.01" min="0" value="0" required>
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
        document.getElementById('modal-title').textContent = 'Nova Ordem de Serviço';
        document.getElementById('acao').value = 'cadastrar';
        document.querySelector('form').reset();
    }

    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
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
                buscarVeiculo(c.id);
            };

            resultadosCliente.appendChild(div);

        });

    });

    function buscarVeiculo(cliente_id) {
        fetch(`/Os/cliente/${cliente_id}/buscarveiculo`)
            .then(response => {
                if (!response.ok) throw new Error(response.status);
                return response.json();
            })
            .then(data => {

                const select = document.getElementById('veiculo_id');
                const option = document.createElement('option');

                option.innerText = 'Selecione..';
                select.appendChild(option);

                data.veiculos.forEach(v => {

                    const option = document.createElement('option');

                    option.value = v.id;
                    option.innerText = `${v.marca} - ${v.modelo} - ${v.placa}`;
                    select.appendChild(option);

                });
            })
            .catch(Error => {
                console.error(Error);
            });
    }
</script>
<?= $render('footer') ?>