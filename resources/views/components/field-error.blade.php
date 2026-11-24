@props(['name', 'bag' => 'default'])
@if ($errors->getBag($bag)->has($name))
    <p class="mt-1 flex items-center gap-1 text-xs text-red-100"><x-ui-icon name="alert" class="h-3.5 w-3.5 shrink-0" /> {{ $errors->getBag($bag)->first($name) }}</p>
@endif
