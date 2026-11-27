// Toast notifikasi. Dipakai oleh <x-toast /> (data flash Laravel) dan bisa
// dipanggil manual: window.toast('success', 'Judul', 'Pesan').

// Warna tiap varian (hanya warna tema)
const VARIAN = {
    success: { border: 'border-green-100', badge: ['bg-green-50', 'text-green-100'], bar: 'bg-green-100' },
    error:   { border: 'border-red-100', badge: ['bg-red-50', 'text-red-100'], bar: 'bg-red-100' },
    warning: { border: 'border-yellow-100', badge: ['bg-yellow-50', 'text-yellow-100'], bar: 'bg-yellow-100' },
    info:    { border: 'border-primary-200', badge: ['bg-blue-50', 'text-primary-200'], bar: 'bg-primary-200' },
};
const DURASI = 5000;

function tutup(el) {
    if (el.classList.contains('is-leaving')) return;
    el.classList.add('is-leaving');
    // reduced motion: animasi mati -> langsung hapus
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    setTimeout(() => el.remove(), reduce ? 0 : 220);
}

function tampilkan(type, title, message) {
    const root = document.getElementById('toast-root');
    const tpl = document.getElementById('toast-template');
    if (!root || !tpl) return;

    const v = VARIAN[type] || VARIAN.info;
    const el = tpl.content.firstElementChild.cloneNode(true);
    el.classList.add(v.border);
    el.querySelector('[data-toast-badge]').classList.add(...v.badge);
    el.querySelector(`[data-icon="${VARIAN[type] ? type : 'info'}"]`).classList.remove('hidden');
    el.querySelector('[data-toast-title]').textContent = title;
    el.querySelector('[data-toast-message]').textContent = message || '';
    const bar = el.querySelector('[data-toast-bar]');
    bar.classList.add(v.bar);
    el.style.setProperty('--toast-duration', DURASI + 'ms');

    el.querySelector('[data-toast-close]').addEventListener('click', () => tutup(el));
    // tutup otomatis saat progress bar habis (berhenti saat di-hover)
    bar.addEventListener('animationend', () => tutup(el));
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        setTimeout(() => tutup(el), DURASI);
    }
    root.appendChild(el);
}

window.toast = tampilkan;

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('toast-root');
    if (!root) return;
    try {
        JSON.parse(root.dataset.toasts || '[]').forEach((t) => tampilkan(t.type, t.title, t.message));
    } catch (e) { /* data tidak valid: abaikan */ }
});
