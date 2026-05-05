import './bootstrap';
import 'bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('click', (event) => {
    const link = event.target.closest('a.instant-nav[href]');

    if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
    }

    event.preventDefault();
    window.location.assign(link.href);
}, true);

Alpine.start();
