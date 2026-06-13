/**
 * Fullajax-навигация без пакетов: перехват внутренних ссылок,
 * fetch с заголовком X-Fullajax (сервер отдаёт только title + контент),
 * замена #content и History API.
 */
const CONTAINER_ID = 'content';

function container() {
    return document.getElementById(CONTAINER_ID);
}

async function load(url, push = true) {
    const el = container();
    if (!el) return;

    el.style.opacity = '0.5';

    try {
        const response = await fetch(url, {
            headers: { 'X-Fullajax': 'true' },
        });

        if (!response.ok) {
            // error pages are rendered without the partial mode — full navigation
            window.location.href = url;

            return;
        }

        const html = await response.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');

        const title = doc.querySelector('title');
        if (title) document.title = title.textContent;
        title?.remove();

        el.innerHTML = doc.body.innerHTML;

        if (push) history.pushState({ fullajax: true }, '', response.url || url);
        window.scrollTo({ top: 0 });
    } catch {
        window.location.href = url; // сеть/ошибка — обычный переход
    } finally {
        el.style.opacity = '';
    }
}

export default function initFullajax() {
    if (!container()) return;

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (
            !link ||
            event.defaultPrevented ||
            event.button !== 0 ||
            event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ||
            link.target === '_blank' ||
            link.hasAttribute('download') ||
            link.hasAttribute('data-no-ajax') ||
            link.origin !== window.location.origin ||
            link.pathname.endsWith('sitemap.xml')
        ) {
            return;
        }

        event.preventDefault();
        load(link.href);
    });

    window.addEventListener('popstate', () => {
        load(window.location.href, false);
    });
}
