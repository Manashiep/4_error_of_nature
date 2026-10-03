@props(['name', 'label', 'type' => 'text', 'required' => false, 'help' => null, 'autocomplete' => null, 'value' => null, 'rows' => 5, 'placeholder' => null])
@php
    $id = 'f-'.$name;
    $err = $errors->first($name);
    $describedBy = trim(($help ? $id.'-help ' : '').($err ? $id.'-err' : ''));
    $cls = 'w-full rounded-2xl border bg-glass px-4 py-3 text-ink placeholder:text-mute/70 hc:border-2 hc:border-white hc:bg-black '
        .($err ? 'border-err' : 'border-edge');
@endphp
<div>
    <label for="{{ $id }}" class="mb-1 mt-4 block text-[.9rem] font-medium text-mute">
        {{ $label }}@if ($required) <span aria-hidden="true">*</span><span class="sr-only">(obligatoire)</span>@endif
    </label>
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @required($required) @if($err) aria-invalid="true" @endif @if($describedBy) aria-describedby="{{ $describedBy }}" @endif class="{{ $cls }}">{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" @required($required) @if($err) aria-invalid="true" @endif @if($describedBy) aria-describedby="{{ $describedBy }}" @endif class="{{ $cls }} [&>option]:bg-night [&>option]:text-ink">{{ $slot }}</select>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" @if($type !== 'password') value="{{ old($name, $value) }}" @endif
               @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif @if($placeholder) placeholder="{{ $placeholder }}" @endif
               @required($required) @if($err) aria-invalid="true" @endif @if($describedBy) aria-describedby="{{ $describedBy }}" @endif class="{{ $cls }}">
    @endif
    @if ($help)
        <p id="{{ $id }}-help" class="mt-1 text-[.85rem] text-mute">{{ $help }}</p>
    @endif
    @if ($err)
        <p id="{{ $id }}-err" class="mt-1 text-[.9rem] text-err"><span class="font-bold">Erreur :</span> {{ $err }}</p>
    @endif
</div>
