// A versão é injetada via ?v= na URL do registro — ex: /service-worker.js?v=3.1
// Isso garante que cada deploy cria um cache novo e limpa os antigos
const VERSION = new URL(location.href).searchParams.get('v') ?? '1';
const CACHE_NAME = `oficina-pro-v${VERSION}`;

const FALLBACK_IMAGE_URL = '/assets/images/icones/aviso.png';

// Instalação: força ativação imediata sem esperar abas fecharem
self.addEventListener('install', (event) => {
    console.log(`[SW] Instalado — cache: ${CACHE_NAME}`);
});

// Ativação: limpa todos os caches de versões anteriores
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.map(key => {
                    if (key !== CACHE_NAME) {
                        console.log('[SW] Removendo cache antigo:', key);
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim()) // assume controle de todas as abas
    );
});

// Atualização manual via botão (mantido para compatibilidade)
self.addEventListener('message', (event) => {
    if (event.data === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

// Estratégia: network-first para JS/CSS/HTML, stale-while-revalidate para imagens
self.addEventListener('fetch', (event) => {
    const requestUrl = new URL(event.request.url);

    if (requestUrl.protocol !== 'http:' && requestUrl.protocol !== 'https:') return;
    if (event.request.method !== 'GET') return;

    const isImage = requestUrl.pathname.match(/\.(png|jpg|jpeg|gif|svg|webp|ico)$/i);
    const isAsset = requestUrl.pathname.match(/\.(css|js|woff|woff2)$/i);
    const isPage = event.request.headers.get('accept')?.includes('text/html');

    if (!isImage && !isAsset && !isPage) return;

    if (isAsset) {
        // JS e CSS: network-first — garante sempre a versão mais recente
        event.respondWith(
            fetch(event.request).then(async (response) => {
                if (response && response.status === 200) {
                    const cache = await caches.open(CACHE_NAME);
                    cache.put(event.request, response.clone());
                }
                return response;
            }).catch(async () => {
                // Sem rede: usa cache como fallback
                const cached = await caches.match(event.request);
                return cached;
            })
        );
        return;
    }

    if (isPage) {

        event.respondWith(

            fetch(event.request)
                .then(async (response) => {

                    const cache = await caches.open(CACHE_NAME);

                    cache.put(
                        event.request,
                        response.clone()
                    );

                    return response;

                })
                .catch(async () => {

                    return await caches.match(event.request);

                })

        );

        return;
    }

    if (isImage) {
        // Imagens: stale-while-revalidate — velocidade sem sacrificar muito
        event.respondWith(
            caches.match(event.request).then(async (cached) => {
                const fetchPromise = fetch(event.request).then(async (response) => {
                    if (response && response.status === 200) {
                        const cache = await caches.open(CACHE_NAME);
                        cache.put(event.request, response.clone());
                    }
                    return response;
                });

                return cached ?? fetchPromise.catch(() => caches.match(FALLBACK_IMAGE_URL));
            })
        );
    }
});