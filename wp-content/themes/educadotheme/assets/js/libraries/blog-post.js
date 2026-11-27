(function () {
    'use strict';

    const article = document.querySelector('[data-blog-post]');
    if (!article) return;

    const header = document.querySelector('.ed-header');
    const syncHeader = () => {
        if (!header) return;
        const height = getComputedStyle(header).position === 'fixed' ? header.getBoundingClientRect().height : 0;
        article.style.setProperty('--article-header-height', `${height}px`);
    };
    syncHeader();
    if (header && 'ResizeObserver' in window) new ResizeObserver(syncHeader).observe(header);
    window.addEventListener('resize', syncHeader, { passive: true });

    const tables = Array.from(article.querySelectorAll('.ed-article__rich table'))
        .filter(table => !table.parentElement.closest('table'));
    tables.forEach((table, index) => {
        const firstRow = table.rows[0];
        if (!table.tHead && firstRow && firstRow.cells.length > 1
            && Array.from(firstRow.cells).every(cell => cell.matches('th') || (
                cell.firstElementChild?.matches('b, strong') && cell.firstElementChild.textContent.trim() === cell.textContent.trim()
            ))) {
            firstRow.classList.add('ed-article__table-head');
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'ed-article__table-scroll';
        table.before(wrapper);
        wrapper.append(table);

        const label = table.caption?.textContent.trim() || (tables.length === 1 ? 'Таблиця' : `Таблиця ${index + 1}`);
        const syncOverflow = () => {
            const scrollable = wrapper.scrollWidth > wrapper.clientWidth + 1;
            wrapper.toggleAttribute('data-scrollable', scrollable);
            if (scrollable) {
                wrapper.tabIndex = 0;
                wrapper.setAttribute('role', 'region');
                wrapper.setAttribute('aria-label', label);
            } else {
                wrapper.removeAttribute('tabindex');
                wrapper.removeAttribute('role');
                wrapper.removeAttribute('aria-label');
            }
        };
        syncOverflow();
        if ('ResizeObserver' in window) {
            const observer = new ResizeObserver(syncOverflow);
            observer.observe(wrapper);
            observer.observe(table);
        } else {
            window.addEventListener('resize', syncOverflow, { passive: true });
        }
    });
}());
