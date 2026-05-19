import { showToast } from './script.js';

const STORAGE_KEY = 'oficina-pro-version';
const CHECK_INTERVAL = 5 * 60 * 1000;

/* ── Busca versão atual no servidor ── */
async function getVersaoRemota() {
    try {
        const res = await fetch('/sistema/versao?t=' + Date.now(), {
            cache: 'no-store'
        });

        const data = await res.json();
        return data.version;

    } catch (e) {
        console.error('[Updater] Erro ao buscar versão:', e);
        return null;
    }
}

/* ── Toast de atualização ── */
function mostrarAvisoAtualizacao(registration) {

    const toast = showToast(
        'Nova versão disponível! Clique para atualizar.',
        'update',
        () => {

            toast.style.opacity = '0';
            toast.style.transition = 'opacity .3s';

            setTimeout(() => toast.remove(), 300);

            if (registration?.waiting) {
                registration.waiting.postMessage('SKIP_WAITING');
            } else {
                window.location.reload();
            }
        },
        { persist: true }
    );
}

/* ── Inicialização ── */
export async function initUpdater(vAtual) {

    if (!('serviceWorker' in navigator)) {
        console.warn('[Updater] Service Worker não suportado');
        return;
    }

    const versaoAtual = vAtual;

    /* salva versão atual */
    const versaoSalva = localStorage.getItem(STORAGE_KEY);

    if (!versaoSalva || versaoSalva !== versaoAtual) {
        localStorage.setItem(STORAGE_KEY, versaoAtual);
    }

    try {

        /* registra SW */
        const registration = await navigator.serviceWorker.register(
            `/service-worker.js?v=${versaoAtual}`
        );

        console.log('[Updater] SW registrado');

        /* detecta nova versão */
        registration.addEventListener('updatefound', () => {

            const novoWorker = registration.installing;

            novoWorker.addEventListener('statechange', () => {

                if (
                    novoWorker.state === 'installed' &&
                    navigator.serviceWorker.controller
                ) {
                    mostrarAvisoAtualizacao(registration);
                }

            });

        });

    } catch (e) {

        console.error('[Updater] Erro ao registrar SW:', e);

    }

    /* reload automático */
    let refreshing = false;

    navigator.serviceWorker.addEventListener('controllerchange', () => {

        if (refreshing) return;

        refreshing = true;

        setTimeout(() => {
            window.location.reload(true);
        }, 300);

    });

    /* polling de versão */
    setInterval(async () => {

        if (document.visibilityState !== 'visible') return;

        const versaoRemota = await getVersaoRemota();
        const versaoLocal = localStorage.getItem(STORAGE_KEY);

        console.log(
            `[Updater] local=${versaoLocal} | remota=${versaoRemota}`
        );

        if (
            versaoRemota &&
            versaoRemota !== versaoLocal
        ) {

            console.log('[Updater] Nova versão encontrada');

            localStorage.setItem(
                STORAGE_KEY,
                versaoRemota
            );

            const reg = await navigator.serviceWorker.getRegistration();

            if (reg) {
                await reg.update();
            }

            mostrarAvisoAtualizacao(reg);
        }

    }, CHECK_INTERVAL);

}