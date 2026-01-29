<?= $render('header') ?>
<div class="access-denied-container">
    <div class="icon-container">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 11c-.55 0-1-.45-1-1V8c0-.55.45-1 1-1s1 .45 1 1v4c0 .55-.45 1-1 1zm1 4h-2v-2h2v2z" />
        </svg>
    </div>

    <h1>Acesso Negado</h1>
    <div class="error-code">ERRO 403</div>
    <p>
        Você não possui permissão para acessar este módulo.
        Entre em contato com o administrador do sistema para solicitar acesso
        ou verifique suas credenciais.
    </p>

    <div class="button-group">
        <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
        <a href="/" class="btn btn-primary">Página Inicial</a>
    </div>
</div>
<?= $render('footer') ?>