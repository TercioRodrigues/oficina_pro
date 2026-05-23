document.addEventListener('DOMContentLoaded', () => {

    const overlay = document.getElementById('dropdownOverlay');

    function isMobile() {
        return window.innerWidth <= 768;
    }

    function closeAllDropdowns() {

        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });

        if (overlay) {
            overlay.classList.remove('show');
        }

    }

    document.querySelectorAll('.navbar-item').forEach(item => {

        const link = item.querySelector('.navbar-link');
        const dropdown = item.querySelector('.dropdown-menu');

        if (!dropdown) return;

        link.addEventListener('click', (e) => {

            if (!isMobile()) return;

            e.preventDefault();
            e.stopPropagation();

            const isOpen = dropdown.classList.contains('show');

            closeAllDropdowns();

            if (!isOpen) {
                dropdown.classList.add('show');

                if (overlay) {
                    overlay.classList.add('show');
                }
            }

        });

    });

    // fechar clicando no overlay
    if (overlay) {

        overlay.addEventListener('click', () => {
            closeAllDropdowns();
        });

    }

    // fechar clicando fora
    document.addEventListener('click', (e) => {

        if (
            !e.target.closest('.dropdown-menu') &&
            !e.target.closest('.navbar-link')
        ) {
            closeAllDropdowns();
        }

    });

    // fechar ao redimensionar
    window.addEventListener('resize', () => {
        closeAllDropdowns();
    });

});