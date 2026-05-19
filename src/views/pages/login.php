<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OficinaPro</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/login.css" />
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <img src="/assets/images/logo.png" alt="logo" style="width: 200px;">
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
    </div>
</body>

</html>