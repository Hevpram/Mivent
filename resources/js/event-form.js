// ===== Form Ajukan / Buat Event =====
// 1) Logika Langkah 1 (AND): modul Langkah 2 tampil hanya jika radio
//    "Penyelenggara" DAN "Peran" sudah dipilih. Bagian yang tidak sesuai
//    peran disembunyikan dan input di dalamnya di-disable (tidak terkirim).
// 2) Repeater Timeline / Syarat / Sie lewat modal "Tambah".
// 3) Drag & drop file SIK.

const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const tgl = (s) => {
    if (!/^\d{4}-\d{2}-\d{2}/.test(s || '')) return '-';
    const [y, m, d] = s.slice(0, 10).split('-').map(Number);
    return `${d} ${BULAN[m - 1]} ${y}`;
};
const rentang = (a, b) => (a === b || !b ? tgl(a) : `${tgl(a)} - ${tgl(b)}`);

document.querySelectorAll('[data-event-form]').forEach((form) => {
    const step2 = form.querySelector('[data-step2]');
    const step2Empty = form.querySelector('[data-step2-empty]');
    const modal = document.querySelector('[data-modal="repeater"]');

    const nilai = (name) => form.querySelector(`input[name="${name}"]:checked`)?.value || '';

    // ---------- Langkah 1: tampil/sembunyi modul ----------
    function aturModul() {
        const peran = nilai('Peran');
        const lengkap = nilai('Penyelenggara') !== '' && peran !== '';

        step2.classList.toggle('hidden', !lengkap);
        step2Empty.classList.toggle('hidden', lengkap);
        // seluruh input Langkah 2 khusus ikut nonaktif bila belum lengkap
        step2.querySelectorAll('input, textarea, select').forEach((el) => { el.disabled = !lengkap; });

        document.querySelectorAll('[data-show-for]').forEach((el) => {
            const cocok = el.dataset.showFor.split(',').includes(peran);
            el.classList.toggle('hidden', !cocok);
            if (form.contains(el)) {
                el.querySelectorAll('input, textarea, select').forEach((inp) => { inp.disabled = !cocok || !lengkap; });
            }
        });
        document.querySelectorAll('[data-peran-text]').forEach((el) => {
            el.textContent = el.dataset[peran] || el.dataset.panitia;
        });
        render(); // label & opsi item ikut peran
    }

    // ---------- Repeater ----------
    const state = { timeline: [], syarat: [], divisi: [] };
    try {
        Object.assign(state, JSON.parse(form.querySelector('[data-repeater-init]')?.textContent || '{}'));
    } catch (e) { /* abaikan */ }

    const ikon = {
        edit: '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5z"/></svg>',
        hapus: '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>',
        sampah: '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12M9 7V4h6v3"/></svg>',
        cek: '<svg class="h-6 w-6 shrink-0 text-green-150" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9.5"/></svg>',
    };
    const tombol = (jenis, i, hapusIkon = ikon.hapus) => `
        <div class="flex shrink-0 items-center gap-3">
            <button type="button" data-edit="${jenis}" data-index="${i}" class="text-primary-150 transition hover:text-primary-200" aria-label="Ubah">${ikon.edit}</button>
            <button type="button" data-hapus="${jenis}" data-index="${i}" class="text-black transition hover:text-red-100" aria-label="Hapus">${hapusIkon}</button>
        </div>`;
    const kosong = (teks) => `<p class="rounded-xl border border-dashed border-gray-100 bg-white px-4 py-3 text-center text-xs text-gray-100">${teks}</p>`;

    function render() {
        const peserta = nilai('Peran') === 'peserta';

        const listT = form.querySelector('[data-repeater-list="timeline"]');
        listT.innerHTML = state.timeline.length ? state.timeline.map((t, i) => `
            <div class="flex items-center gap-4 rounded-xl bg-white px-4 py-3 shadow-[0_0_8px_rgba(64,103,159,0.35)]">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-primary-100 text-xs font-bold text-white">${i + 1}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold">${escapeHtml(t.nama)}</p>
                    <p class="text-xs">Rentang Waktu: ${rentang(t.mulai, t.selesai)}</p>
                </div>
                ${tombol('timeline', i)}
                <input type="hidden" name="timelines[${i}][nama]" value="${escapeHtml(t.nama)}">
                <input type="hidden" name="timelines[${i}][mulai]" value="${escapeHtml(t.mulai)}">
                <input type="hidden" name="timelines[${i}][selesai]" value="${escapeHtml(t.selesai)}">
            </div>`).join('') : kosong('Belum ada tahapan. Klik "Tambah Tahap".');

        const listS = form.querySelector('[data-repeater-list="syarat"]');
        listS.innerHTML = state.syarat.length ? state.syarat.map((s, i) => `
            <div class="flex items-center gap-3 rounded-xl bg-white px-4 py-3 shadow-[0_0_8px_rgba(64,103,159,0.35)]">
                ${ikon.cek}
                <p class="min-w-0 flex-1 text-sm">${escapeHtml(s.deskripsi)}
                    ${peserta && Number(s.butuh_berkas) ? '<span class="ml-2 rounded bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-primary-200">Wajib unggah berkas</span>' : ''}</p>
                ${tombol('syarat', i)}
                <input type="hidden" name="syarat[${i}][deskripsi]" value="${escapeHtml(s.deskripsi)}">
                <input type="hidden" name="syarat[${i}][butuh_berkas]" value="${Number(s.butuh_berkas) ? 1 : 0}">
            </div>`).join('') : kosong('Belum ada syarat. Klik "Tambah Syarat".');

        const listD = form.querySelector('[data-repeater-list="divisi"]');
        listD.innerHTML = state.divisi.length ? state.divisi.map((d, i) => `
            <div class="rounded-xl bg-white p-4 shadow-[0_0_8px_rgba(64,103,159,0.35)]">
                <div class="flex items-center justify-between">
                    <span class="rounded-full bg-blue-50 px-3 py-0.5 text-[11px] font-medium text-primary-200">Kuota: ${escapeHtml(d.kuota)} Orang</span>
                    ${tombol('divisi', i, ikon.sampah)}
                </div>
                <p class="mt-2 font-bold">${escapeHtml(d.nama)}</p>
                <p class="mt-1 text-xs leading-relaxed">Deskripsi Peran: ${escapeHtml(d.deskripsi)}</p>
                ${d.id ? `<input type="hidden" name="divisi[${i}][id]" value="${escapeHtml(d.id)}">` : ''}
                <input type="hidden" name="divisi[${i}][nama]" value="${escapeHtml(d.nama)}">
                <input type="hidden" name="divisi[${i}][kuota]" value="${escapeHtml(d.kuota)}">
                <input type="hidden" name="divisi[${i}][deskripsi]" value="${escapeHtml(d.deskripsi)}">
            </div>`).join('') : `<div class="lg:col-span-2">${kosong('Belum ada sie. Klik "Tambah Sie".')}</div>`;

        // input tersembunyi ikut status disable modul
        const lengkap = !step2.classList.contains('hidden');
        step2.querySelectorAll('[data-repeater-list] input').forEach((el) => {
            el.disabled = !lengkap || (el.closest('[data-show-for]')?.classList.contains('hidden') ?? false);
        });
    }

    // ---------- Modal tambah / edit ----------
    let jenisAktif = 'timeline';
    let indexEdit = null;
    const JUDUL = {
        timeline: ['Timeline & Tahapan', 'Tambahkan Tahap', 'calendar'],
        syarat: ['Syarat Pendaftaran', 'Tambahkan Syarat'],
        divisi: ['Kebutuhan Sie & Rincian Tugas', 'Tambahkan Sie'],
    };
    const pane = (j) => modal.querySelector(`[data-repeater-pane="${j}"]`);
    const errEl = modal.querySelector('[data-repeater-error]');

    function pasangKalender(mulai, selesai) {
        const slot = modal.querySelector('[data-drc-slot]');
        const tpl = modal.querySelector('[data-drc-template]');
        slot.innerHTML = '';
        const el = tpl.content.firstElementChild.cloneNode(true);
        el.dataset.start = mulai || '';
        el.dataset.end = selesai || '';
        slot.appendChild(el);
        window.initDateRangeCalendars?.(slot);
    }

    function pilihTab(j) {
        jenisAktif = j;
        modal.querySelectorAll('[data-repeater-tab]').forEach((b) => { b.dataset.active = String(b.dataset.repeaterTab === j); });
        modal.querySelectorAll('[data-repeater-pane]').forEach((p) => p.classList.toggle('hidden', p.dataset.repeaterPane !== j));
        const peran = nilai('Peran') === 'peserta' ? 'Pendaftaran' : 'Open Recruitment';
        modal.querySelector('h2').textContent = j === 'timeline' ? `Timeline & Tahapan ${peran}` : JUDUL[j][0];
        modal.querySelector('[data-repeater-save-label]').textContent = indexEdit === null ? JUDUL[j][1] : 'Simpan Perubahan';
        errEl.classList.add('hidden');
    }

    function bukaModal(j, i = null) {
        indexEdit = i;
        const data = i === null ? {} : state[j][i];
        const p = pane(j);
        p.querySelectorAll('[data-field]').forEach((f) => {
            if (f.type === 'checkbox') f.checked = !!Number(data[f.dataset.field] || 0);
            else f.value = data[f.dataset.field] ?? '';
        });
        if (j === 'timeline') pasangKalender(data.mulai, data.selesai);
        // tab hanya bisa dipindah saat menambah, bukan saat edit
        modal.querySelectorAll('[data-repeater-tab]').forEach((b) => { b.disabled = i !== null && b.dataset.repeaterTab !== j; });
        if (j !== 'timeline' && !modal.querySelector('[data-drc-slot]').children.length) pasangKalender();
        pilihTab(j);
        window.openModal?.('repeater');
        setTimeout(() => p.querySelector('[data-field]')?.focus(), 50);
    }

    function simpan() {
        const p = pane(jenisAktif);
        const data = {};
        p.querySelectorAll('[data-field]').forEach((f) => {
            data[f.dataset.field] = f.type === 'checkbox' ? (f.checked ? 1 : 0) : f.value.trim();
        });
        let pesan = '';
        if (jenisAktif === 'timeline') {
            data.mulai = modal.querySelector('[data-drc-slot] .drc-start')?.value || '';
            data.selesai = modal.querySelector('[data-drc-slot] .drc-end')?.value || data.mulai;
            if (!data.nama) pesan = 'Judul/nama timeline wajib diisi.';
            else if (!data.mulai) pesan = 'Pilih rentang tanggal tahapan.';
        } else if (jenisAktif === 'syarat') {
            if (!data.deskripsi) pesan = 'Syarat pendaftaran wajib diisi.';
        } else {
            const k = parseInt(data.kuota, 10);
            if (!data.nama) pesan = 'Nama sie wajib diisi.';
            else if (!k || k < 1 || k > 500) pesan = 'Kuota harus angka 1 - 500.';
            else if (!data.deskripsi) pesan = 'Deskripsi peran wajib diisi.';
            data.kuota = k;
        }
        if (pesan) {
            errEl.textContent = pesan;
            errEl.classList.remove('hidden');
            return;
        }
        if (indexEdit === null) state[jenisAktif].push(data);
        else state[jenisAktif][indexEdit] = { ...state[jenisAktif][indexEdit], ...data };
        render();
        window.closeModal?.('repeater');
        window.toast?.('success', 'Berhasil', indexEdit === null ? 'Data berhasil ditambahkan.' : 'Data berhasil diperbarui.');
    }

    form.addEventListener('click', (e) => {
        const add = e.target.closest('[data-repeater-add]');
        const edit = e.target.closest('[data-edit]');
        const del = e.target.closest('[data-hapus]');
        if (add) bukaModal(add.dataset.repeaterAdd);
        if (edit) bukaModal(edit.dataset.edit, Number(edit.dataset.index));
        if (del) {
            state[del.dataset.hapus].splice(Number(del.dataset.index), 1);
            render();
        }
    });
    modal?.querySelectorAll('[data-repeater-tab]').forEach((b) => b.addEventListener('click', () => {
        if (b.dataset.repeaterTab === 'timeline' && !modal.querySelector('[data-drc-slot]').children.length) pasangKalender();
        pilihTab(b.dataset.repeaterTab);
    }));
    modal?.querySelector('[data-repeater-save]').addEventListener('click', simpan);
    modal?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && e.target.matches('input')) { e.preventDefault(); simpan(); }
    });

    form.querySelectorAll('input[data-step1]').forEach((r) => r.addEventListener('change', aturModul));

    // ---------- Validasi ringan sebelum kirim ----------
    form.addEventListener('submit', (e) => {
        const peran = nilai('Peran');
        let pesan = '';
        if (!nilai('Penyelenggara') || !peran) pesan = 'Pilih Penyelenggara dan Peran pada Langkah 1.';
        else if (!state.timeline.length) pesan = 'Tambahkan minimal 1 tahapan timeline.';
        else if (!state.syarat.length) pesan = 'Tambahkan minimal 1 syarat pendaftaran.';
        else if (peran === 'panitia' && !state.divisi.length) pesan = 'Event panitia wajib memiliki minimal 1 sie.';
        if (pesan) {
            e.preventDefault();
            window.toast?.('warning', 'Form belum lengkap', pesan);
            return;
        }
        const btn = form.querySelector('[data-submit]');
        if (btn) { btn.disabled = true; btn.classList.add('opacity-60'); }
    });

    aturModul();
});

// ---------- Drag & drop file (SIK, berkas lain) ----------
document.querySelectorAll('[data-file-drop]').forEach((box) => {
    const zone = box.querySelector('[data-file-zone]');
    const input = box.querySelector('[data-file-input]');
    const nama = box.querySelector('[data-file-name]');
    if (!zone || !input) return;

    const tampil = () => {
        const f = input.files[0];
        if (!nama) return;
        nama.textContent = f ? `${f.name} (${(f.size / 1024 / 1024).toFixed(2)} MB)` : '';
        nama.classList.toggle('hidden', !f);
    };
    input.addEventListener('change', tampil);
    zone.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('border-primary-200', 'bg-blue-50'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('border-primary-200', 'bg-blue-50'));
    zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.classList.remove('border-primary-200', 'bg-blue-50');
        const f = e.dataTransfer.files[0];
        if (!f) return;
        const dt = new DataTransfer();
        dt.items.add(f);
        input.files = dt.files;
        tampil();
    });
});
