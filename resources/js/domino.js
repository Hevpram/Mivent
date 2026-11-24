// Animasi masuk "domino": elemen ber-atribut data-domino muncul bergeser
// dari kiri ke kanan satu per satu (stagger). Elemen di bawah layar baru
// dianimasikan ketika mulai terlihat (IntersectionObserver). Elemen yang
// muncul bersamaan diberi jeda berurutan (90ms) agar terlihat seperti domino.

const JEDA = 90;

document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('[data-domino]');
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduce || !('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-in'));
        return;
    }

    const io = new IntersectionObserver((entries) => {
        let urutan = 0;
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.style.setProperty('--domino-delay', Math.min(urutan * JEDA, 720) + 'ms');
            entry.target.classList.add('is-in');
            io.unobserve(entry.target);
            urutan++;
        });
    }, { threshold: 0.08 });

    items.forEach((el) => io.observe(el));
});
