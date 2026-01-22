<?php
$pagina_atual = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Oficina Pro</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="/assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- Topbar -->

    <div class="topbar">
        <a href="/" class="topbar-brand">
            <span>🔧</span>
            <span>Oficina do Galego</span>
        </a>

        <div class="topbar-right">
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($_SESSION['usuario_nome'], 0, 1)) ?>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= $_SESSION['usuario_nome'] ?></div>
                    <div class="user-nivel"><?= $_SESSION['usuario_nivel'] ?></div>
                </div>
            </div>
            <a href="/logout" class="btn-logout">🚪 Sair</a>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <ul class="navbar-menu">
            <li class="navbar-item">
                <a href="/dashboard" class="navbar-link <?= $pagina_atual === 'dashboard' ? 'active' : '' ?>">
                    <span class="navbar-icon">📊</span>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="navbar-item">
                <a href="#" class="navbar-link">
                    <span class="navbar-icon">📋</span>
                    <span>Atendimento</span>
                    <span style="margin-left: 5px;">▼</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/orcamentos" class="dropdown-item <?= $pagina_atual === 'orcamentos' || $pagina_atual === 'orcamento_itens' ? 'active' : '' ?>">
                            <span class="dropdown-icon">📝</span>
                            <span>Orçamentos</span>
                        </a></li>
                    <li><a href="/ordens" class="dropdown-item <?= $pagina_atual === 'ordens' || $pagina_atual === 'os_itens' ? 'active' : '' ?>">
                            <span class="dropdown-icon">📋</span>
                            <span>Ordens de Serviço</span>
                        </a></li>
                    <li><a href="/agendamentos" class="dropdown-item <?= $pagina_atual === 'agendamentos' ? 'active' : '' ?>">
                            <span class="dropdown-icon">📅</span>
                            <span>Agendamentos</span>
                        </a></li>
                    <li><a href="/garantias" class="dropdown-item <?= $pagina_atual === 'garantias' ? 'active' : '' ?>">
                            <span class="dropdown-icon">🛡️</span>
                            <span>Garantias</span>
                        </a></li>
                </ul>
            </li>

            <li class="navbar-item">
                <a href="#" class="navbar-link">
                    <span class="navbar-icon">👥</span>
                    <span>Cadastros</span>
                    <span style="margin-left: 5px;">▼</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/clientes" class="dropdown-item <?= $pagina_atual === 'clientes' ? 'active' : '' ?>">
                            <span class="dropdown-icon">👥</span>
                            <span>Clientes</span>
                        </a></li>
                    <li><a href="/veiculos" class="dropdown-item <?= $pagina_atual === 'veiculos' ? 'active' : '' ?>">
                            <span class="dropdown-icon">🚗</span>
                            <span>Veículos</span>
                        </a></li>
                    <li><a href="/servicos" class="dropdown-item <?= $pagina_atual === 'servicos' ? 'active' : '' ?>">
                            <span class="dropdown-icon">🔨</span>
                            <span>Serviços</span>
                        </a></li>
                    <li><a href="/fornecedores" class="dropdown-item <?= $pagina_atual === 'fornecedores' ? 'active' : '' ?>">
                            <span class="dropdown-icon">🏢</span>
                            <span>Fornecedores</span>
                        </a></li>
                </ul>
            </li>

            <li class="navbar-item">
                <a href="#" class="navbar-link">
                    <span class="navbar-icon">📦</span>
                    <span>Estoque</span>
                    <span style="margin-left: 5px;">▼</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/estoque" class="dropdown-item <?= $pagina_atual === 'estoque' ? 'active' : '' ?>">
                            <span class="dropdown-icon">📦</span>
                            <span>Produtos</span>
                        </a></li>
                    <li><a href="/compras" class="dropdown-item <?= $pagina_atual === 'compras' || $pagina_atual === 'compras_itens' ? 'active' : '' ?>">
                            <span class="dropdown-icon">🛒</span>
                            <span>Compras</span>
                        </a></li>
                </ul>
            </li>

            <li class="navbar-item">
                <a href="/caixa" class="navbar-link <?= $pagina_atual === 'caixa' ? 'active' : '' ?>">
                    <span class="navbar-icon">💰</span>
                    <span>Caixa</span>
                </a>
            </li>

            <li class="navbar-item">
                <a href="/funcionarios" class="navbar-link <?= $pagina_atual === 'funcionarios' ? 'active' : '' ?>">
                    <span class="navbar-icon">👷</span>
                    <span>Funcionários</span>
                </a>
            </li>

            <li class="navbar-item">
                <a href="#" class="navbar-link">
                    <span class="navbar-icon">⚙️</span>
                    <span>Configurações</span>
                    <span style="margin-left: 5px;">▼</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/configuracoes" class="dropdown-item <?= $pagina_atual === 'configuracoes' ? 'active' : '' ?>">
                            <span class="dropdown-icon">🏢</span>
                            <span>Empresa</span>
                        </a></li>
                    <?php if ($_SESSION['usuario_nivel'] === 'Admin'): ?>
                        <li><a href="/usuarios" class="dropdown-item <?= $pagina_atual === 'usuarios' ? 'active' : '' ?>">
                                <span class="dropdown-icon">🔐</span>
                                <span>Usuários</span>
                            </a></li>
                    <?php endif; ?>
                    <li class="dropdown-divider"></li>
                    <li><a href="/painel" class="dropdown-item" target="_blank">
                            <span class="dropdown-icon">📺</span>
                            <span>Painel Cliente</span>
                        </a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">