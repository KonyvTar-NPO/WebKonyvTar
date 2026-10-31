{{-- styling for the input forms --}}
@props(['name', 'label', 'type' => 'text', 'id' => null, 'form' => null])

@php $id ??= $name; @endphp

<div>
    <label for="{{ $id }}" class="mb-1 block text-sm font-medium text-gray-900 dark:text-gray-200">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
           @if ($type !== 'password') value="{{ old($name) }}" @endif
        {{ $attributes->merge(['class' => 'w-full h-6.5 px-1 border-1 rounded border-gray-400 bg-white text-gray-900 dark:text-gray-100 dark:border-gray-400 dark:bg-gray-900']) }}>
    @if ($form === null || old('_form') === $form)
        @error($name) <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    @endif
</div>
