{{-- Input kata sandi dengan tombol lihat/sembunyikan --}}
@props(['name' => 'password', 'id' => null, 'placeholder' => 'Masukkan kata sandi', 'icon' => false, 'bag' => 'default', 'compact' => false, 'auth' => false, 'authSm' => false])
<div class="relative">
    @if ($auth)
        <img src="{{ asset('mivent_assets/icons/auth-lock.svg') }}" alt="" class="pointer-events-none absolute left-5 top-1/2 z-10 h-[21px] w-4 -translate-y-1/2">
    @elseif ($icon)
        <x-ui-icon name="lock" class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-black" />
    @endif
    <input type="password" name="{{ $name }}" id="{{ $id ?? $name }}" placeholder="{{ $placeholder }}" autocomplete="new-password"
        {{ $attributes->merge(['class' => ($auth ? 'input-auth pl-12 pr-14 ' : '') . ($auth ? '' : ($authSm ? 'h-10 w-full rounded-[6px] border border-gray-100 bg-white pl-3 pr-12 text-sm text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition placeholder:text-gray-100 focus:border-primary-200 focus:outline-none ' : '') . ($authSm ? '' : ($compact ? 'h-[27px] w-full rounded-[5px] border-[0.5px] border-black bg-primary-50 pl-2 pr-9 text-sm leading-5 text-black placeholder:text-gray-100 transition focus:border-primary-200 focus:outline-none ' : 'input pr-11 ')) . ($icon ? 'pl-11 ' : ''))  . ($errors->getBag($bag)->has($name) ? 'border-red-100' : '')]) }}>
    <button type="button" data-toggle-password aria-label="Tampilkan kata sandi" class="absolute z-10 {{ $auth ? 'right-5' : ($compact ? 'right-1.5' : 'right-3') }} top-1/2 -translate-y-1/2 rounded p-1 text-black transition hover:text-primary-200">
        @if ($auth || $authSm)
            <img src="{{ asset('mivent_assets/icons/auth-eye-off.svg') }}" alt="" data-eye-on @class(['h-[19.792px] w-[24.127px]' => $auth, 'h-[14px] w-[17px]' => $authSm])>
        @elseif ($compact)
            <img src="{{ asset('mivent_assets/icons/eye-field.svg') }}" alt="" data-eye-on class="h-[12.432px] w-[19px] -scale-x-100">
        @else
            <x-ui-icon name="eye-off" data-eye-on class="h-5 w-5" />
        @endif
        <x-ui-icon name="eye" data-eye-off @class(["hidden", "h-4 w-4" => $compact, "h-5 w-5" => ! $compact]) />
    </button>
</div>
