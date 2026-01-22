<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OficinaPro</title>
    <link rel="stylesheet" href="assets/css/login.css" />
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">🔧</div>
            <h1>Sistema Oficina</h1>
            <p class="subtitle">Faça login para continuar</p>
        </div>
        
        <?php if ($erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= $login['email'] ?>" required autofocus placeholder="seu@email.com">
            </div>
            
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" value="<?= $login['senha'] ?>" required placeholder="Digite sua senha">
            </div>
            
            <button type="submit" class="btn-login">Entrar no Sistema</button>
        </form>
        
        <div class="info-acesso">
            <h3>👤 Usuários de Teste:</h3>
            <ul>
                <li><strong>Admin:</strong> admin@oficina.com</li>
                <li><strong>Gerente:</strong> gerente@oficina.com</li>
                <li><strong>Atendente:</strong> atendente@oficina.com</li>
                <li><strong>Senha:</strong> admin123 (todos)</li>
            </ul>
        </div>
    </div>
</body>
</html>