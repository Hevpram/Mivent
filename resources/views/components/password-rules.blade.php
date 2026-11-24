{{-- Checklist syarat kata sandi (Figma: "Persyaratan" — lingkaran 15px, centang hijau saat terpenuhi; live di resources/js/password.js) --}}
@props(['for' => '#password', 'confirm' => '#password_confirmation'])
<ul data-password-rules="{{ $for }}" data-password-confirm="{{ $confirm }}" {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }}>
    @foreach (['length' => 'Minimal 8 karakter', 'case' => 'Mengandung huruf besar dan kecil', 'number' => 'Mengandung angka (0-9)', 'symbol' => 'Mengandung karakter khusus/spesial seperti !@#$%^&*'] as $r => $t)
        <li data-rule="{{ $r }}" class="group flex items-center gap-3 text-xs leading-4 text-black">
            <img src="{{ asset('mivent_assets/icons/rule-off.svg') }}" alt="" class="size-[15px] group-[.is-ok]:hidden">
            <img src="{{ asset('mivent_assets/icons/rule-on.svg') }}" alt="" class="hidden size-[15px] group-[.is-ok]:block">
            {{ $t }}
        </li>
    @endforeach
</ul>
