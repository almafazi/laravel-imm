@props(['value'])

<label {{ $attributes->merge(['class' => 'form-floating form-floating-outline mb-4']) }}>
    {{ $value ?? $slot }}
</label>
