// Checklist syarat password (sesuai Figma): min 8 karakter, huruf besar &
// kecil, angka, karakter spesial. Juga cek kecocokan konfirmasi.
document.querySelectorAll('[data-password-rules]').forEach((list) => {
    const input = document.querySelector(list.dataset.passwordRules);
    const confirm = document.querySelector(list.dataset.passwordConfirm || '');
    if (!input) return;

    const aturan = {
        length: (v) => v.length >= 8,
        case: (v) => /[a-z]/.test(v) && /[A-Z]/.test(v),
        number: (v) => /\d/.test(v),
        symbol: (v) => /[^A-Za-z0-9]/.test(v),
        match: (v) => !!confirm && v !== '' && v === confirm.value,
    };

    const cek = () => {
        const v = input.value;
        list.querySelectorAll('[data-rule]').forEach((li) => {
            const ok = aturan[li.dataset.rule]?.(v) ?? false;
            li.classList.toggle('is-ok', ok);
        });
    };
    input.addEventListener('input', cek);
    confirm?.addEventListener('input', cek);
    cek();
});
