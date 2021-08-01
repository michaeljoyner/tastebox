<div {{ $attributes }}>
    <label>
        <span class="type-b4">{{ $label }}</span>
        @if($error)
            <span class="text-sm text-red-500">{{ $error }}</span>
        @endif
        <input class="block w-full" type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}">
    </label>
</div>
