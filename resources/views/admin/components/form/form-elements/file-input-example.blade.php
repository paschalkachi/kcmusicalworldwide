{{-- <x-common.component-card title="Image Input"> --}}
    <!-- Elements -->
    {{-- @props(['name'])
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Upload Image
        </label>

        <input
            type="file"
            name="{{ $name }}"
            {{ $attributes->merge(['class' => 'focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400']) }}
        >
    </div> --}}
{{-- </x-common.component-card> --}}
@props([
    'name',
    'current' => null, // existing image (edit page)
    'folder' => '',
])

<div 
    x-data="{
        preview: '{{ $current ? asset('uploads/' . $folder . '/' . $current) : '' }}',
        hasImage: {{ $current ? 'true' : 'false'}},

        handleFile(e) {
            const file = e.target.files[0];
            if (!file) return;

            this.preview = URL.createObjectURL(file);
            this.hasImage = true;
        },

        removeImage() {
            this.preview = '';
            this.hasImage = false;
            this.$refs.input.value = '';
        }
    }"
>
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
        Upload Image
    </label>

    <!-- Preview -->
    <div x-show="hasImage" x-cloak class="mb-3 relative w-24 h-24 group">
        <img 
            :src="preview"
            class="h-24 w-24 rounded-lg border object-cover"
        >

        <!-- Remove button -->
        <button
            type="button"
            @click="removeImage"
            class="absolute top-1 right-1 bg-black/60 text-white rounded-full p-1
                   opacity-0 group-hover:opacity-100 transition"
        >
            ✕
        </button>
    </div>

    <!-- File Input -->
    <input
        x-ref="input"
        type="file"
        name="{{ $name }}"
        accept="image/*"
        @change="handleFile"
        {{ $attributes->merge([
            'class' => 'focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300
                        h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent
                        text-sm text-gray-500 transition-colors file:mr-5 file:cursor-pointer
                        file:rounded-l-lg file:border-0 file:border-r file:border-gray-200
                        file:bg-gray-50 file:py-3 file:px-3 file:text-sm file:text-gray-700
                        hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700
                        dark:bg-gray-900 dark:text-gray-400 dark:file:border-gray-800
                        dark:file:bg-white/[0.03] dark:file:text-gray-400'
        ]) }}
    >
</div>

 @error('image') <span class="alert alert-danger">{{ $message }}</span> @enderror
