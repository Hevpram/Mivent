(function () {
    const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const KELAS = {
        dasar: 'flex h-9 w-full items-center justify-center rounded-md text-sm font-medium transition',
        bulanIni: 'bg-blue-50 text-black hover:bg-primary-100 hover:text-white',
        sebelum: 'bg-primary-50 text-gray-100 hover:bg-gray-50',
        sesudah: 'text-gray-100 hover:bg-primary-50',
        terpilih: 'bg-primary-200 text-white',
        terpilihLuar: 'bg-primary-100 text-white',
        pratinjau: 'bg-primary-100 text-primary-200',
        hariIni: 'ring-1 ring-inset ring-primary-150',
    };

    // Date -> 'YYYY-MM-DD' (zona waktu lokal, tanpa toISOString)
    const iso = (d) => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');

    const formatIndo = (s) => {
        const [y, m, d] = s.split('-').map(Number);
        return d + ' ' + BULAN[m - 1] + ' ' + y;
    };

    const validTanggal = (s) => /^\d{4}-\d{2}-\d{2}$/.test(s || '');

    function initKalender(root) {
        if (root.dataset.drcReady) return;
        root.dataset.drcReady = '1';

        const q = (sel) => root.querySelector(sel);
        const fieldMulai = q('.drc-field-start');
        const fieldSelesai = q('.drc-field-end');
        const teksMulai = q('.drc-text-start');
        const teksSelesai = q('.drc-text-end');
        const popover = q('.drc-popover');
        const label = q('.drc-label');
        const grid = q('.drc-grid');
        const inputMulai = q('.drc-start');
        const inputSelesai = q('.drc-end');

        let mulai = validTanggal(root.dataset.start) ? root.dataset.start : null;
        let selesai = validTanggal(root.dataset.end) ? root.dataset.end : null;
        let hover = null;
        let mode = null;
        if (mulai && selesai && selesai < mulai) [mulai, selesai] = [selesai, mulai];

        const awal = /^\d{4}-\d{2}$/.test(root.dataset.month || '')
            ? root.dataset.month
            : (mulai ? mulai.slice(0, 7) : iso(new Date()).slice(0, 7));
        let tahun = parseInt(awal.slice(0, 4), 10);
        let bulan = parseInt(awal.slice(5, 7), 10) - 1;

        function keBulan(t) {
            tahun = parseInt(t.slice(0, 4), 10);
            bulan = parseInt(t.slice(5, 7), 10) - 1;
        }

        function statusTanggal(t) {
            if (mode === 'end' && mulai && hover) {
                const [a, b] = hover < mulai ? [hover, mulai] : [mulai, hover];
                if (t === mulai) return true;
                return t >= a && t <= b ? 'pratinjau' : false;
            }
            if (mulai && selesai) return t >= mulai && t <= selesai;
            return t === mulai;
        }

        function renderGrid() {
            label.textContent = BULAN[bulan] + ' ' + tahun;
            grid.innerHTML = '';

            const jumlahHari = new Date(tahun, bulan + 1, 0).getDate();
            const geser = (new Date(tahun, bulan, 1).getDay() + 6) % 7; // Senin = 0
            const totalSel = Math.ceil((geser + jumlahHari) / 7) * 7;
            const hariIni = iso(new Date());

            for (let i = 0; i < totalSel; i++) {
                const tgl = new Date(tahun, bulan, i - geser + 1);
                const t = iso(tgl);
                const luar = tgl.getMonth() !== bulan;
                const status = statusTanggal(t);

                let kelas;
                if (status === 'pratinjau') kelas = KELAS.pratinjau;
                else if (status) kelas = luar ? KELAS.terpilihLuar : KELAS.terpilih;
                else if (!luar) kelas = KELAS.bulanIni;
                else kelas = i < geser ? KELAS.sebelum : KELAS.sesudah;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = KELAS.dasar + ' ' + kelas + (t === hariIni && !status ? ' ' + KELAS.hariIni : '');
                btn.textContent = tgl.getDate();
                btn.dataset.date = t;
                btn.setAttribute('aria-label', formatIndo(t));
                btn.setAttribute('aria-pressed', status === true ? 'true' : 'false');
                grid.appendChild(btn);
            }
        }

        function renderField() {
            [[teksMulai, mulai], [teksSelesai, selesai]].forEach(([el, nilai]) => {
                el.textContent = nilai ? formatIndo(nilai) : el.dataset.placeholder;
                el.dataset.empty = nilai ? 'false' : 'true';
            });
            fieldMulai.dataset.active = String(mode === 'start');
            fieldSelesai.dataset.active = String(mode === 'end');
            const terbuka = String(mode !== null);
            fieldMulai.setAttribute('aria-expanded', terbuka);
            fieldSelesai.setAttribute('aria-expanded', terbuka);
            inputMulai.value = mulai || '';
            inputSelesai.value = selesai || '';
        }

        function render() {
            renderField();
            renderGrid();
        }

        function kirimEvent() {
            root.dispatchEvent(new CustomEvent('date-range-change', { bubbles: true, detail: { start: mulai, end: selesai } }));
        }

        function buka(m) {
            mode = m;
            hover = null;
            const acuan = m === 'end' ? (selesai || mulai) : mulai;
            if (acuan) keBulan(acuan);
            popover.classList.remove('hidden');
            render();
        }

        function tutup() {
            if (mode === null) return;
            mode = null;
            hover = null;
            popover.classList.add('hidden');
            render();
        }

        fieldMulai.addEventListener('click', () => {
            mode === 'start' ? tutup() : buka('start');
        });

        fieldSelesai.addEventListener('click', () => {
            const target = mulai ? 'end' : 'start';
            mode === target ? tutup() : buka(target);
        });

        grid.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-date]');
            if (!btn) return;
            const t = btn.dataset.date;
            if (mode === 'start' || !mulai) {
                mulai = t;
                selesai = null;
                mode = 'end';
                hover = null;
                kirimEvent();
                render();
            } else {
                selesai = t;
                if (selesai < mulai) [mulai, selesai] = [selesai, mulai];
                kirimEvent();
                tutup();
            }
        });

        // Pratinjau rentang saat memilih tanggal selesai
        grid.addEventListener('mouseover', (e) => {
            const btn = e.target.closest('button[data-date]');
            if (!btn || mode !== 'end' || !mulai || hover === btn.dataset.date) return;
            hover = btn.dataset.date;
            renderGrid();
        });
        grid.addEventListener('mouseleave', () => {
            if (hover === null) return;
            hover = null;
            renderGrid();
        });

        q('.drc-prev').addEventListener('click', () => {
            bulan--; if (bulan < 0) { bulan = 11; tahun--; }
            renderGrid();
        });
        q('.drc-next').addEventListener('click', () => {
            bulan++; if (bulan > 11) { bulan = 0; tahun++; }
            renderGrid();
        });

        document.addEventListener('click', (e) => {
            if (mode !== null && !e.composedPath().includes(root)) tutup();
        });

        root.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape' || mode === null) return;
            const field = mode === 'start' ? fieldMulai : fieldSelesai;
            tutup();
            field.focus();
        });

        render();
    }

    window.initDateRangeCalendars = function (scope) {
        (scope || document).querySelectorAll('.date-range-calendar').forEach(initKalender);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => window.initDateRangeCalendars());
    } else {
        window.initDateRangeCalendars();
    }
})();
