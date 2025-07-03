// Bootstrap JS + CSS
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';

// Alpine (opsional)
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Debug log (opsional)
document.addEventListener('DOMContentLoaded', () => {
    console.log('Bootstrap and AlpineJS initialized');
});

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebarBtn');
    const closeBtn = document.getElementById('closeSidebarBtn');

    openBtn?.addEventListener('click', () => {
        sidebar.classList.remove('d-none');
    });

    closeBtn?.addEventListener('click', () => {
        sidebar.classList.add('d-none');
    });
});
