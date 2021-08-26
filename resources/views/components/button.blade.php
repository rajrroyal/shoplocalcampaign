<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? "disabled" : null }}>
    {{ $slot }}
</button>