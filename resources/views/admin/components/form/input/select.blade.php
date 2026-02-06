@props([
    'label',
    'name',
    'options' => [],
    'placeholder' => 'Select Option',
    'value' => null,

    // NEW (optional)
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'optionData' => [], // ['code' => 'code']
])

<div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
        {{ $label }}
    </label>

    <select
        name="{{ $name }}"
        id="{{ $attributes->get('id') }}"
        @change="isOptionSelected = true"
        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
        {{ $attributes->merge([
            'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                        dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300
                        bg-transparent pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                        dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
        ]) }}
    >
        <option value="">{{ $placeholder }}</option>

        @php($selected = old($name, $value))

        @foreach($options as $key => $option)

            {{-- CASE 1: Collection / Model --}}
            @if(is_object($option))
                <option
                    value="{{ $option->{$optionValue} }}"
                    @foreach($optionData as $attr => $field)
                        data-{{ $attr }}="{{ $option->{$field} }}"
                    @endforeach
                    {{ (string)$option->{$optionValue} === (string)$selected ? 'selected' : '' }}
                >
                    {{ $option->{$optionLabel} }}
                </option>

            {{-- CASE 2: Simple key => value array --}}
            @else
                <option
                    value="{{ $key }}"
                    {{ (string)$key === (string)$selected ? 'selected' : '' }}
                >
                    {{ $option }}
                </option>
            @endif

        @endforeach
    </select>

    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-700 dark:text-gray-400">
        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>

    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
