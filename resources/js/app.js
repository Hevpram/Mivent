import './date-range-calendar';
import './phone-input';
import './text-editor';
import './char-counter';
import './poster-upload';
import './domino';
import './toast';
import './ui';
import './event-form';
import './password';

// ===== Navbar publik: berubah warna saat halaman di-scroll =====
const navbar = document.getElementById('navbar');
const nav_text = document.querySelector('.navbar_text');
const nav_textIn = document.querySelectorAll('.navbar_textIn');
const nav_btn = document.querySelector('.navbar_button');
const nav_user = document.querySelector('.navbar_user');
const logo_text = document.querySelector('.logo_text');
const logo = document.getElementById('logo');

function aturNavbar() {
    // Halaman tanpa navbar publik (admin, auth): lewati
    if (!navbar) return;

    if(window.scrollY > 10) {
        navbar.classList.add('bg-primary-200');
        navbar.classList.add('shadow-[0px_4px_8px_0px_rgba(64,103,159,0.75)]');
        nav_text?.classList.replace('text-black', 'text-white');
        nav_user?.classList.replace('text-black', 'text-white');
        nav_textIn.forEach(function(elemen) {
            elemen.classList.add('navbar_textIn_white');
        });
        const btnClasses = [
            ['border-primary-150', 'border-white'],
            ['text-primary-150', 'text-white'],
            ['hover:bg-primary-150', 'hover:bg-white'],
            ['hover:text-white', 'hover:text-primary-200'],
        ];
        if (nav_btn) btnClasses.forEach(([lama, baru]) => nav_btn.classList.replace(lama, baru));
        logo_text?.classList.replace('text-gradient-mivent', 'text-white');
        if (logo) logo.src = logo.dataset.logoWhite;

    } else {
        navbar.classList.remove('bg-primary-200');
        navbar.classList.remove('shadow-[0px_4px_8px_0px_rgba(64,103,159,0.75)]');
        nav_text?.classList.replace('text-white', 'text-black');
        nav_user?.classList.replace('text-white', 'text-black');
        nav_textIn.forEach(function(elemen) {
            elemen.classList.remove('navbar_textIn_white');
        });
        const btnClasses = [
            ['border-white', 'border-primary-150'],
            ['text-white', 'text-primary-150'],
            ['hover:bg-white', 'hover:bg-primary-150'],
            ['hover:text-primary-200', 'hover:text-white'],
        ]
        if (nav_btn) btnClasses.forEach(([lama,baru]) => nav_btn.classList.replace(lama,baru));
        logo_text?.classList.replace('text-white', 'text-gradient-mivent');
        if (logo) logo.src = logo.dataset.logoColor;
    }
}
window.addEventListener('scroll', aturNavbar);
aturNavbar();

// ===== Dropdown pilihan (radio di dalam .dropdown-list) =====
document.querySelectorAll('.dropdown').forEach(dropdown => {
    const btn   = dropdown.querySelector('.dropdown-btn');
    const label = dropdown.querySelector('.dropdown-label');
    const arrow = dropdown.querySelector('.dropdown-arrow');
    const list  = dropdown.querySelector('.dropdown-list');

    if (!btn || !list) return;

    btn.addEventListener('click', () => {
        list.classList.toggle('hidden');
        arrow?.classList.toggle('rotate-180');
    });

    list.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            if (label) {
                label.textContent = radio.nextElementSibling.textContent;
                label.classList.replace('text-gray-100', 'text-black');
            }
            list.classList.add('hidden');
            arrow?.classList.remove('rotate-180');
        });
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
            list.classList.add('hidden');
            arrow?.classList.remove('rotate-180');
        }
    });
});