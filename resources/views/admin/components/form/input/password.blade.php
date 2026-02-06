@props(['label', 'name', 'placeholder' => ''])

<div x-data="{ showPassword: false }" class="relative">
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
        {{ $label }}
    </label>

    <input 
        :type="showPassword ? 'text' : 'password'" 
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                        dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                        bg-transparent px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400
                        focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900
                        dark:text-white/90 dark:placeholder:text-white/30'
        ]) }}
    />

    <span @click="showPassword = !showPassword"
        class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500 dark:text-gray-400">
        <svg x-show="!showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20">
            <path fill="#98A2B3" d="M10 13.862C7.234 13.862 4.868 12.137 3.923 9.702 4.868 7.268 7.234 5.543 10 5.543c2.767 0 5.132 1.725 6.077 4.16-0.945 2.435-3.31 4.16-6.077 4.16z"/>
        </svg>
        <svg x-show="showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20">
            <path fill="#98A2B3" d="M4.638 3.577l11.725 11.725"/>
        </svg>
    </span>

    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
