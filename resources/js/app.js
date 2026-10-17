const navbar = document.getElementById('navbar');
const nav_text = document.querySelector('.navbar_text');
const nav_textIn = document.querySelectorAll('.navbar_textIn');
const nav_btn = document.querySelector('.navbar_button');
const logo_text = document.querySelector('.logo_text');
const logo = document.getElementById('logo');

window.addEventListener('scroll', ()=> {
    if(window.scrollY > 10) {
        navbar.classList.add('bg-primary-200');
        navbar.classList.add('rounded-b-4xl');
        navbar.classList.add('shadow-[0px_4px_8px_0px_rgba(64,103,159,0.75)]');
        nav_text.classList.replace('text-black', 'text-white');
        nav_textIn.forEach(function(elemen) {
            elemen.classList.add('navbar_textIn_white');
        });
        const btnClasses = [
            ['outline-primary-200', 'outline-white'],
            ['text-primary-200', 'text-white'],
            ['hover:bg-primary-200', 'hover:bg-white'],
            ['hover:text-white', 'hover:text-primary-200'],
        ];
        btnClasses.forEach(([lama, baru]) => nav_btn.classList.replace(lama, baru));
        logo_text.classList.replace('text-gradient-mivent', 'text-white');
        logo.src = '/build/assets/mivent_assets/logoWhite.png';

    } else {
        navbar.classList.remove('bg-primary-200');
        navbar.classList.remove('rounded-b-4xl');
        navbar.classList.remove('shadow-[0px_4px_8px_0px_rgba(64,103,159,0.75)]');
        nav_text.classList.replace('text-white', 'text-black');
        nav_textIn.forEach(function(elemen) {
            elemen.classList.remove('navbar_textIn_white');
        });
        const btnClasses = [
            ['outline-white', 'outline-primary-200'],
            ['text-white', 'text-primary-200'],
            ['hover:bg-white', 'hover:bg-primary-200'],
            ['hover:text-primary-200', 'hover:text-white'],
        ]
        btnClasses.forEach(([lama,baru]) => nav_btn.classList.replace(lama,baru));
        logo_text.classList.replace('text-white', 'text-gradient-mivent');
        logo.src = '/build/assets/mivent_assets/logoColor.png';
    }
})