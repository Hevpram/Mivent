document.querySelectorAll('[data-counter]').forEach(input => {
    const wrap = input.parentElement;
    const count = wrap.querySelector('[data-count]');
    const error = wrap.querySelector('[data-char-error]');
    const max = input.maxLength;

    input.addEventListener('input', ()=> {
        const len = input.value.length;
        count.textContent = len;

        let pesan = '';
        if (len === 0) {
            pesan = 'Deskripsi singkat wajib di isi';
        } else if (len >= max) {
            pesan = `Deskripsi sudah mencapai batas ${max} karakter`;
        }
        error.textContent = pesan;
        error.classList.toggle('hidden', !pesan);
        input.classList.toggle('border-red-100', !!pesan);
        count.classList.toggle('text-red-100', len >= max);
    })
})