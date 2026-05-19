</main>

<div id="toastContainer"></div>

<script>
    const APP_VERSION = '<?= VERSAO ?>';
</script>

<script type="module">
    import {
        initUpdater
    } from '/assets/js/updater.js';

    initUpdater(APP_VERSION);
</script>

</body>

</html>