<div {{ $attributes->merge(['class' => 'relative w-full ' . $ratioClass]) }}>
    <div class="absolute inset-0 overflow-hidden">
       {{ $slot }}
    </div>
</div>
