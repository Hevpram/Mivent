{{-- Halaman 6 digit kode (Figma: Verifikasi Kode 7:8820, salah 7:8848). prototype: Verifikasi -> langkah berikutnya --}}
@props(['title', 'action', 'resend', 'email' => null])
<x-layout :title="$title" :navbar="false" :footer="false">
    <div class="flex min-h-screen flex-col bg-primary-50 px-[51px] pt-10">
        <a href="{{ route('login') }}" data-domino class="group flex h-[49px] w-fit items-center gap-3 rounded-[20px] bg-primary-100/50 px-[22px] text-sm font-bold text-primary-200 transition-all duration-300 hover:bg-primary-100">
            <img src="{{ asset('mivent_assets/icons/arrow-back.svg') }}" alt="" class="h-[14.7px] w-[19px] max-w-none transition-transform duration-300 group-hover:-translate-x-1"> Kembali ke Login
        </a>
        <div class="mx-auto mt-[150px] flex w-[858px] flex-col items-center text-center">
            <h1 data-domino class="text-4xl font-bold text-black">Masukan 6 Digit Kode</h1>
            <p data-domino class="mt-4 text-base text-gray-100">Kode telah dikirim diemail atau dinomor WhatsApp Anda{{ $email ? ' (' . $email . ')' : '' }}</p>
            <p data-domino class="mt-3 text-base text-gray-100">Durasi Tersisa : <span data-countdown="60" data-countdown-enable="#btn-resend">01:00</span></p>

            <form data-domino data-once method="POST" action="{{ $action }}" id="form-otp" class="mt-12" novalidate>
                @csrf
                <div data-otp class="flex justify-center gap-[21px]">
                    @for ($i = 0; $i < 6; $i++)
                        <input data-otp-box inputmode="numeric" maxlength="1" aria-label="Digit {{ $i + 1 }}" @if ($i === 0) autofocus @endif
                            class="h-[120px] w-[124px] rounded-[6px] border border-primary-150 bg-white text-center text-[44px] font-medium text-black transition hover:border-primary-200 focus:border-primary-200 focus:shadow-[0_0_8px_rgba(64,103,159,0.5)] focus:outline-none @error('kode') border-red-100 text-red-100 @enderror">
                    @endfor
                    <input type="hidden" name="kode" data-otp-value>
                </div>
                <div class="flex justify-center"><x-field-error name="kode" /></div>
            </form>
            <form method="POST" action="{{ $resend }}" data-once class="mt-11 w-[652px]">
                @csrf
                <button id="btn-resend" type="submit" disabled class="flex h-14 w-full items-center justify-center rounded-[6px] border border-primary-150 bg-white text-base font-bold text-primary-150 transition-all duration-300 enabled:hover:bg-blue-50 disabled:cursor-not-allowed disabled:opacity-60">Kirim Ulang Kode</button>
            </form>
            <button type="submit" form="form-otp" class="mt-5 flex h-14 w-[652px] items-center justify-center rounded-[6px] bg-primary-150 text-base font-bold text-white transition-all duration-300 hover:bg-primary-200">Verifikasi</button>
        </div>
    </div>
</x-layout>
