// ===== Interaksi umum (vanilla JS + atribut data-*) =====

// ---------- Modal ----------
function openModal(id) {
    const m = document.querySelector(`[data-modal="${id}"]`);
    if (!m) return;
    m.classList.remove('hidden');
    m.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}
function closeModal(id) {
    const m = typeof id === 'string' ? document.querySelector(`[data-modal="${id}"]`) : id;
    if (!m) return;
    m.classList.add('hidden');
    m.classList.remove('flex');
    if (!document.querySelector('[data-modal].flex')) document.body.classList.remove('overflow-hidden');
}
window.openModal = openModal;
window.closeModal = closeModal;

document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-modal-open]');
    if (opener) {
        e.preventDefault();
        openModal(opener.dataset.modalOpen);
        return;
    }
    const closer = e.target.closest('[data-modal-close]');
    if (closer) {
        closeModal(closer.closest('[data-modal]'));
        return;
    }
    // klik latar gelap di luar panel
    if (e.target.matches('[data-modal]')) closeModal(e.target);
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') document.querySelectorAll('[data-modal].flex').forEach(closeModal);
});
document.querySelectorAll('[data-modal-autoopen]').forEach((m) => openModal(m.dataset.modal));

// ---------- Konfirmasi sebelum submit ----------
// <form data-confirm="Yakin?"> -> dialog konfirmasi bawaan browser
document.querySelectorAll('form[data-confirm]').forEach((f) => {
    f.addEventListener('submit', (e) => {
        if (!window.confirm(f.dataset.confirm)) e.preventDefault();
    });
});

// ---------- Cegah submit ganda ----------
document.querySelectorAll('form[data-once]').forEach((f) => {
    f.addEventListener('submit', () => {
        f.querySelectorAll('button[type="submit"]').forEach((b) => { b.disabled = true; b.classList.add('opacity-60'); });
    });
});

// ---------- Accordion (FAQ, divisi) ----------
document.querySelectorAll('[data-accordion]').forEach((item) => {
    const btn = item.querySelector('[data-accordion-btn]');
    const body = item.querySelector('[data-accordion-body]');
    const arrow = item.querySelector('[data-accordion-arrow]');
    btn?.addEventListener('click', () => {
        const buka = body.classList.contains('hidden');
        body.classList.toggle('hidden', !buka);
        arrow?.classList.toggle('rotate-180', buka);
        btn.setAttribute('aria-expanded', String(buka));
    });
});

// ---------- Tab ----------
document.querySelectorAll('[data-tabs]').forEach((wrap) => {
    const tabs = wrap.querySelectorAll('[data-tab]');
    const panes = wrap.querySelectorAll('[data-tab-pane]');
    const pilih = (nama) => {
        tabs.forEach((t) => t.classList.toggle('is-active', t.dataset.tab === nama));
        panes.forEach((p) => p.classList.toggle('hidden', p.dataset.tabPane !== nama));
    };
    tabs.forEach((t) => t.addEventListener('click', () => pilih(t.dataset.tab)));
    pilih(wrap.dataset.tabs || tabs[0]?.dataset.tab);
});

// ---------- Tampilkan / sembunyikan password ----------
document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const input = btn.parentElement.querySelector('input');
        const tampil = input.type === 'password';
        input.type = tampil ? 'text' : 'password';
        btn.querySelector('[data-eye-on]')?.classList.toggle('hidden', tampil);
        btn.querySelector('[data-eye-off]')?.classList.toggle('hidden', !tampil);
    });
});

// ---------- Input kode OTP 6 kotak ----------
document.querySelectorAll('[data-otp]').forEach((wrap) => {
    const boxes = wrap.querySelectorAll('[data-otp-box]');
    const hidden = wrap.querySelector('[data-otp-value]');
    const sync = () => { hidden.value = Array.from(boxes).map((b) => b.value).join(''); };
    boxes.forEach((box, i) => {
        box.addEventListener('input', () => {
            box.value = box.value.replace(/\D/g, '').slice(-1);
            if (box.value && boxes[i + 1]) boxes[i + 1].focus();
            sync();
        });
        box.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !box.value && boxes[i - 1]) boxes[i - 1].focus();
        });
        box.addEventListener('paste', (e) => {
            e.preventDefault();
            const angka = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, boxes.length);
            angka.split('').forEach((c, j) => { boxes[j].value = c; });
            boxes[Math.min(angka.length, boxes.length - 1)].focus();
            sync();
        });
    });
});

// ---------- Hitung mundur (kirim ulang kode) ----------
document.querySelectorAll('[data-countdown]').forEach((el) => {
    let sisa = parseInt(el.dataset.countdown, 10) || 0;
    const target = document.querySelector(el.dataset.countdownEnable || '');
    const tick = () => {
        const m = String(Math.floor(sisa / 60)).padStart(2, '0');
        const s = String(sisa % 60).padStart(2, '0');
        el.textContent = `${m}:${s}`;
        if (target) target.disabled = sisa > 0;
        if (sisa <= 0) { clearInterval(timer); return; }
        sisa--;
    };
    const timer = setInterval(tick, 1000);
    tick();
});

// ---------- Preview gambar (avatar / logo) ----------
document.querySelectorAll('[data-image-preview]').forEach((input) => {
    const img = document.querySelector(input.dataset.imagePreview);
    const maxKb = parseInt(input.dataset.maxKb || '0', 10);
    input.addEventListener('change', () => {
        const f = input.files[0];
        if (!f) return;
        if (maxKb && f.size > maxKb * 1024) {
            window.toast?.('error', 'Ukuran terlalu besar', `Maksimal ${maxKb} kB.`);
            input.value = '';
            return;
        }
        if (img) img.src = URL.createObjectURL(f);
        if (input.dataset.autosubmit !== undefined) input.form?.requestSubmit();
    });
});

// ---------- Nama file terpilih (input file biasa) ----------
document.querySelectorAll('input[type="file"][data-file-label]').forEach((input) => {
    const label = document.querySelector(input.dataset.fileLabel);
    input.addEventListener('change', () => {
        if (label) label.textContent = input.files[0]?.name || label.dataset.empty || 'Belum ada file';
    });
});

// ---------- Kirim form saat pilihan berubah (filter) ----------
document.querySelectorAll('[data-autosubmit-change]').forEach((el) => {
    el.addEventListener('change', () => el.form?.requestSubmit());
});

// ---------- Pilih sie (maks 2) di form pendaftaran ----------
document.querySelectorAll('[data-sie-select]').forEach((wrap) => {
    const max = parseInt(wrap.dataset.max || '2', 10);
    const in1 = wrap.querySelector('[data-sie-1]');
    const in2 = wrap.querySelector('[data-sie-2]');
    const boxes = wrap.querySelectorAll('input[type="checkbox"][data-sie]');
    const info = wrap.querySelector('[data-sie-info]');
    const chips = wrap.querySelector('[data-sie-chips]');
    const chipTpl = wrap.querySelector('template[data-sie-chip]');
    const chipsEmpty = wrap.querySelector('[data-sie-chips-empty]');
    const list = wrap.querySelector('[data-sie-list]');
    wrap.querySelector('[data-sie-toggle]')?.addEventListener('click', (e) => {
        if (e.target.closest('[data-chip-remove]')) return;
        const buka = list?.classList.toggle('hidden') === false;
        wrap.querySelector('[data-sie-chevron]')?.classList.toggle('rotate-180', buka);
    });
    let urutan = [in1.value, in2.value].filter(Boolean);

    const render = () => {
        boxes.forEach((b) => {
            b.checked = urutan.includes(b.value);
            const badge = b.closest('label').querySelector('[data-sie-order]');
            const idx = urutan.indexOf(b.value);
            if (badge) {
                badge.textContent = idx >= 0 ? `Pilihan ${idx + 1}` : '';
                badge.classList.toggle('hidden', idx < 0);
            }
            b.disabled = !b.checked && urutan.length >= max;
        });
        in1.value = urutan[0] || '';
        in2.value = urutan[1] || '';
        if (info) info.textContent = `${urutan.length}/${max} sie dipilih`;
        // chip sie terpilih (Figma: kotak pilihan dengan chip biru + tombol x)
        if (chips && chipTpl) {
            chips.replaceChildren(...urutan.map((id) => {
                const box = wrap.querySelector(`[data-sie][value="${id}"]`);
                const chip = chipTpl.content.firstElementChild.cloneNode(true);
                chip.querySelector('[data-chip-label]').textContent = box?.dataset.nama || '';
                chip.querySelector('[data-chip-remove]').addEventListener('click', () => {
                    urutan = urutan.filter((v) => v !== id);
                    render();
                });
                return chip;
            }));
            chipsEmpty?.classList.toggle('hidden', urutan.length > 0);
        }
        wrap.dispatchEvent(new CustomEvent('sie:change', { bubbles: true }));
    };
    boxes.forEach((b) => b.addEventListener('change', () => {
        urutan = b.checked ? [...urutan, b.value].slice(0, max) : urutan.filter((v) => v !== b.value);
        render();
    }));
    render();
});

// ---------- Form multi langkah (pendaftaran) ----------
document.querySelectorAll('[data-steps]').forEach((form) => {
    const steps = form.querySelectorAll('[data-step]');
    const ind = document.querySelectorAll('[data-step-indicator]');
    let aktif = parseInt(form.dataset.steps || '0', 10) || 0;

    const tampil = (i) => {
        aktif = Math.max(0, Math.min(i, steps.length - 1));
        steps.forEach((s, j) => s.classList.toggle('hidden', j !== aktif));
        ind.forEach((el, j) => {
            el.dataset.state = j < aktif ? 'done' : (j === aktif ? 'active' : 'todo');
        });
        window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
    };
    // validasi bawaan browser untuk langkah aktif
    const valid = () => {
        const fields = steps[aktif].querySelectorAll('input, select, textarea');
        for (const f of fields) {
            if (!f.disabled && !f.checkValidity()) {
                if (f.type === 'file' || f.classList.contains('sr-only')) {
                    const judul = f.closest('[class*="rounded"]')?.querySelector('p')?.textContent?.trim() || 'Berkas';
                    window.toast?.('warning', 'Berkas belum diunggah', `${judul} wajib diunggah.`);
                } else {
                    f.reportValidity();
                }
                return false;
            }
        }
        const custom = steps[aktif].querySelector('[data-step-require]');
        if (custom && !custom.value) {
            window.toast?.('warning', 'Belum lengkap', custom.dataset.stepRequire);
            return false;
        }
        return true;
    };
    form.querySelectorAll('[data-step-next]').forEach((b) => b.addEventListener('click', () => { if (valid()) tampil(aktif + 1); }));
    form.querySelectorAll('[data-step-prev]').forEach((b) => b.addEventListener('click', () => tampil(aktif - 1)));
    // prototype: klik indikator langkah / "Edit Data" -> pindah langkah (maju tetap divalidasi)
    document.querySelectorAll('[data-step-goto]').forEach((b) => b.addEventListener('click', () => {
        const tujuan = parseInt(b.dataset.stepGoto, 10);
        if (tujuan <= aktif) { tampil(tujuan); return; }
        while (aktif < tujuan) { if (!valid()) return; tampil(aktif + 1); }
    }));
    form.addEventListener('submit', (e) => {
        if (!valid()) { e.preventDefault(); return; }
        form.querySelectorAll('button[type="submit"]').forEach((b) => { b.disabled = true; });
    });
    tampil(aktif);
});

// ---------- Penampil berkas (zoom & rotasi gambar) ----------
document.querySelectorAll('[data-viewer]').forEach((v) => {
    const target = v.querySelector('[data-viewer-target]');
    let zoom = 1;
    let rot = 0;
    const apply = () => {
        if (target) target.style.transform = `scale(${zoom}) rotate(${rot}deg)`;
        const label = v.querySelector('[data-viewer-zoom]');
        if (label) label.textContent = Math.round(zoom * 100) + '%';
    };
    v.querySelector('[data-viewer-in]')?.addEventListener('click', () => { zoom = Math.min(zoom + 0.25, 3); apply(); });
    v.querySelector('[data-viewer-out]')?.addEventListener('click', () => { zoom = Math.max(zoom - 0.25, 0.5); apply(); });
    v.querySelector('[data-viewer-rotate]')?.addEventListener('click', () => { rot = (rot + 90) % 360; apply(); });
});

// ---------- Ganti berkas di penampil (Lihat Berkas pendaftar) ----------
document.querySelectorAll('[data-file-switch]').forEach((wrap) => {
    const frame = document.querySelector(wrap.dataset.fileSwitch);
    const show = (btn) => {
        if (!wrap.hasAttribute('data-no-highlight')) {
        wrap.querySelectorAll('[data-file-url]').forEach((b) => b.classList.remove('bg-primary-50', 'text-primary-200'));
        btn.classList.add('bg-primary-50', 'text-primary-200');
        }
        if (!frame) return;
        const url = btn.dataset.fileUrl;
        const isPdf = /\.pdf($|\?)/i.test(url);
        frame.querySelector('[data-viewer-target]').innerHTML = isPdf
            ? `<iframe src="${url}" class="h-[560px] w-full rounded-lg bg-white" title="Berkas"></iframe>`
            : `<img src="${url}" alt="Berkas" class="mx-auto max-h-[560px] rounded-lg object-contain">`;
        const title = frame.querySelector('[data-viewer-title]');
        if (title) title.textContent = btn.dataset.fileName || '';
        const dl = frame.querySelector('[data-viewer-download]');
        if (dl) dl.href = url;
    };
    wrap.querySelectorAll('[data-file-url]').forEach((btn) => btn.addEventListener('click', () => show(btn)));
    // tampilkan berkas pertama tanpa memicu klik (agar modal tidak ikut terbuka)
    const first = wrap.querySelector('[data-file-url]');
    if (first) show(first);
});

// ---------- Pilih semua checkbox ----------
document.querySelectorAll('[data-check-all]').forEach((all) => {
    const items = document.querySelectorAll(all.dataset.checkAll);
    all.addEventListener('change', () => items.forEach((i) => { i.checked = all.checked; }));
});

// ---------- Ringkasan pendaftaran: cermin data langkah 1 & 2 ke langkah 3 ----------
document.querySelectorAll('[data-steps]').forEach((form) => {
    const sync = () => {
        form.querySelectorAll('[data-mirror]').forEach((el) => { el.textContent = form.querySelector(el.dataset.mirror)?.value || '-'; });
        form.querySelectorAll('[data-mirror-file]').forEach((el) => {
            el.textContent = form.querySelector(el.dataset.mirrorFile)?.files?.[0]?.name || el.dataset.default || '-';
        });
        form.querySelectorAll('[data-mirror-sie]').forEach((el) => {
            const id = form.querySelector(el.dataset.mirrorSie === '1' ? '[data-sie-1]' : '[data-sie-2]')?.value;
            const box = id && form.querySelector(`[data-sie][value="${id}"]`);
            el.textContent = box ? box.dataset.nama : '-';
        });
    };
    form.addEventListener('click', (e) => { if (e.target.closest('[data-step-next]')) sync(); });
    document.querySelectorAll('[data-step-goto]').forEach((b) => b.addEventListener('click', sync));
    sync();
});
