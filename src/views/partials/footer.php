</main>

<div class="toast-container" id="toastContainer"></div>
<script src="/assets/js/dropdown.js"></script>
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