export function showToast(msg, type = 'info', onClick = null, options = {}) {

    const cont = document.getElementById('toastContainer');

    if (!cont) return;

    const t = document.createElement('div');

    const isPersistent = options.persist || false;

    t.className = `toast ${type} ${isPersistent ? 'persist' : ''}`;

    const icons = {
        success: '✅',
        error: '❌',
        info: 'ℹ️',
        update: '🚀'
    };

    t.innerHTML = `
    <span>${icons[type]}</span>
    <span>${msg}</span>
  `;

    if (onClick) {
        t.style.cursor = 'pointer';
        t.onclick = onClick;
    }

    cont.appendChild(t);

    if (!isPersistent) {

        setTimeout(() => {

            t.style.opacity = '0';
            t.style.transition = 'opacity .3s';

            setTimeout(() => t.remove(), 300);

        }, 5000);

    }

    return t;
}