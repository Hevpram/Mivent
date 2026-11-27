document.querySelectorAll('[data-phone]').forEach(input => {
    const error = input.parentElement.parentElement.querySelector('[data-phone-error]');

    input.addEventListener('input', () => {
        const asli = input.value;
        input.value = asli.replace(/[^0-9]/g, '');

        let pesan = '';
        if (asli !== input.value) pesan = 'Nomor telepon hanya boleh berisi angka';
        else if (input.value.length > 0 && input.value.length < 10) pesan = 'Nomor telepon minimal 10 digit';

        if (error) {
            error.textContent = pesan;
            error.classList.toggle('hidden', !pesan);
        }
        input.classList.toggle('border-red-100', !!pesan);
        input.setCustomValidity(pesan);
    });
});