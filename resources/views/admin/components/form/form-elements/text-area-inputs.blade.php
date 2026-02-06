{{-- <x-common.component-card title="Textarea input fields"> --}}
@props([
        'short' => null,
        'description' => null,
])

        <!-- Elements -->
        <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Short Description
                </label>
                <textarea name="short_description" placeholder="Enter short description..." type="text" rows="6"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('short_description', $short) }}</textarea>
        </div>
    @error('short_description')
        <p class="text-theme-xs text-error-500">{{ $message }}</p>
    @enderror

    <!-- Elements -->
        <div>
           <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
               Description
           </label>
          <textarea name="description" placeholder="Enter a description..." type="text" rows="6"
              class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('description', $description) }}</textarea>
        </div>
    @error('description')
        <p class="text-theme-xs text-error-500">{{ $message }}</p>
    @enderror

    <!-- Elements -->
    {{-- <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Description
        </label>
        <textarea placeholder="Enter a description..." type="text" rows="6"
            class="dark:bg-dark-900 border-error-300 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
        <p class="text-theme-xs text-error-500">
            Please enter a message in the textarea.
        </p>
 </div> --}}
{{-- </x-common.component-card> --}}
