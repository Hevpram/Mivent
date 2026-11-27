document.querySelectorAll('[data-poster]').forEach(box => {
    const input = box.querySelector('[data-poster-input]');
    const img = box.querySelector('[data-poster-img]');
    const drop = box.querySelector('[data-poster-drop]');
    const del = box.querySelector('[data-poster-delete]');
    const error = box.querySelector('[data-poster-error]');
    const empty = box.querySelector('[data-poster-empty]');   
    const boleh = ['image/jpeg', 'image/png'];

    function tampilkan(file) {
        if (!file) return;
        if (!boleh.includes(file.type)) {
            error.textContent = 'Format harus .jpg, .jpeg, atau .png';
            error.classList.remove('hidden');
            input.value = '';
            return;
        }
        error.classList.add('hidden');
        img.src = URL.createObjectURL(file);
        img.classList.remove('hidden');
        empty.classList.add('hidden');   
    }

    input.addEventListener('change', () => tampilkan(input.files[0]));

    drop.addEventListener('dragover', e => {
        e.preventDefault();
        drop.classList.add('ring-4', 'ring-primary-150');
    });
    drop.addEventListener('dragleave', () => drop.classList.remove('ring-4', 'ring-primary-150'));
    drop.addEventListener('drop', e => {
        e.preventDefault();
        drop.classList.remove('ring-4', 'ring-primary-150');
        const file = e.dataTransfer.files[0];
        if (!file) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        tampilkan(file);
    });

    del.addEventListener('click', () => {
        input.value = '';
        img.src = '';
        img.classList.add('hidden');
        empty.classList.remove('hidden');  
        error.classList.add('hidden');
    });
});