<?php
$pagina_atual = $_SERVER['REQUEST_URI'];
$pagina_atual = str_replace('/', '', $pagina_atual);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION['empresa_nome'] ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Topbar -->

    <div class="topbar">
        <a href="/" class="topbar-brand" style="height: 60px">
            <?php if (isset($_SESSION['empresa_logo'])): ?>
                <img src="/<?= $_SESSION['empresa_logo'] ?>" alt="logo" style="height: 100%">
            <?php endif; ?>
            <span><?= $_SESSION['empresa_nome'] ?></span>
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
            <button class="btn-logout" onclick="window.location.replace('/logout')" style="cursor: pointer;">🚪 <span class="logout-text">Sair</span></button>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-scroll">
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
                        <li><a href="/Os" class="dropdown-item <?= $pagina_atual === 'Os' || $pagina_atual === "Ositens?os_id=" . substr($pagina_atual, strpos($pagina_atual, '=') + 1, strlen($pagina_atual)) ? 'active' : '' ?>">
                                <span class="dropdown-icon">📋</span>
                                <span>Ordens de Serviço</span>
                            </a></li>
                        <li><a href="/agendamentos" class="dropdown-item <?= $pagina_atual === 'agendamentos' ? 'active' : '' ?>">
                                <span class="dropdown-icon">📅</span>
                                <span>Agendamentos</span>
                            </a></li>
                        <li><a href="/garantias" class="dropdown-item <?= $pagina_atual === 'garantias' || $pagina_atual === "garantias?status=" . substr($pagina_atual, strpos($pagina_atual, '=') + 1, strlen($pagina_atual)) ? 'active' : '' ?>">
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
                        <li>
                            <a href="/configuracoes" class="dropdown-item <?= $pagina_atual === 'configuracoes' ? 'active' : '' ?>">
                                <span class="dropdown-icon">🏢</span>
                                <span>Empresa</span>
                            </a>
                        </li>
                        <li>
                            <a href="/usuarios" class="dropdown-item <?= $pagina_atual === 'usuarios' ? 'active' : '' ?>">
                                <span class="dropdown-icon">🔐</span>
                                <span>Usuários</span>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="#" class="dropdown-item" onclick="window.open('/painel', '', 'fullscreen=yes')">
                                <span class="dropdown-icon">📺</span>
                                <span>Painel Cliente</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">