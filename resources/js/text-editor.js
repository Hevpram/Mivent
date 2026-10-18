document.querySelectorAll('[data-editor]').forEach(editor => {
    const content = editor.querySelector('[data-editor-content]');
    const input = editor.querySelector('[data-editor-input]');
    const error = editor.querySelector('[data-editor-error]');
    const buttons = editor.querySelectorAll('[data-cmd]');

    document.execCommand('defaultParagraphSeparator', false, 'p');

    buttons.forEach(btn => {
        btn.addEventListener('mousedown', e => e.preventDefault());
        btn.addEventListener('click', () => {
            const cmd = btn.dataset.cmd;
            if (cmd === 'createLink') {
                const url = prompt('Masukkan link:', 'https://');
                if (url) document.execCommand('createLink', false, url);
            } else if (cmd === 'code') {
                const text = window.getSelection().toString();
                if (text) document.execCommand('insertHTML', false, `<code>${text}</code>`);
            } else if (cmd === 'formatBlock') {
                const inQuote = document.queryCommandValue('formatBlock') === 'blockquote';
                document.execCommand('formatBlock', false, inQuote ? 'p' : 'blockquote');
            } else {
                document.execCommand(cmd);
            }
            sync();
        });
    });

    function updateActive() {
        buttons.forEach(btn => {
            const cmd = btn.dataset.cmd;
            let aktif = false;
            if (['bold','italic','underline','insertUnorderedList','insertOrderedList'].includes(cmd)) aktif = document.queryCommandState(cmd);
            if (cmd === 'formatBlock') aktif = document.queryCommandValue('formatBlock') === 'blockquote';
            btn.classList.toggle('bg-primary-100', aktif);
            btn.classList.toggle('text-primary-200', aktif);
        });
    }

    function sync() {
        if (content.innerText.trim() === '') content.innerHTML = '';
        input.value = content.innerHTML;
        updateActive();
    }

    content.addEventListener('input', sync);
    content.addEventListener('keyup', updateActive);
    content.addEventListener('mouseup', updateActive);
    content.addEventListener('blur', () => {
        const kosong = content.innerText.trim() === '';
        error.textContent = kosong ? 'Deskripsi lengkap wajib diisi' : '';
        error.classList.toggle('hidden', !kosong);
    });

    content.addEventListener('paste', e => {
        e.preventDefault();
        document.execCommand('insertText', false, e.clipboardData.getData('text/plain'));
    });

    editor.closest('form')?.addEventListener('submit', e => {
        sync();
        if (!input.value) {
            e.preventDefault();
            error.textContent = 'Deskripsi lengkap wajib diisi';
            error.classList.remove('hidden');
            content.focus();
        }
    });
});