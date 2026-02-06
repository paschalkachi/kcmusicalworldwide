@props([
    'name' => 'images',
    'existing' => [], // edit page passes this, add page leaves it empty
])

<div
     x-data="{
        existingImages: {{ json_encode($existing) }},
        newImages: [],
        isDragging: false,

        handleFiles(files) {
            [...files].forEach(file => {
                this.newImages.push({
                    file,
                    preview: URL.createObjectURL(file)
                });
            });
        },

        removeExisting(index) {
            this.existingImages.splice(index, 1);
        },

        removeNew(index) {
            this.newImages.splice(index, 1);
        }
    }"
    class="relative border border-dashed rounded-xl p-6"
>
    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
        Product Gallery
    </label>

    <!-- Preview Grid -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <!-- Existing images -->
        <template x-for="(img, index) in existingImages" :key="'old-' + index">
            <div class="relative group">
                <img
                    :src="`{{ asset('uploads/products') }}/${img}`"
                    class="h-24 w-24 rounded-lg border object-cover"
                >
                <button
                    type="button"
                    @click="removeExisting(index)"
                    class="absolute top-1 right-1 bg-black/60 text-white p-1 rounded-full
                           opacity-0 group-hover:opacity-100 transition"
                >✕</button>
            </div>
        </template>

        <!-- New images -->
        <template x-for="(img, index) in newImages" :key="'new-' + index">
            <div class="relative group">
                <img
                    :src="img.preview"
                    class="h-24 w-24 rounded-lg border object-cover"
                >
                <button
                    type="button"
                    @click="removeNew(index)"
                    class="absolute top-1 right-1 bg-black/60 text-white p-1 rounded-full
                           opacity-0 group-hover:opacity-100 transition"
                >✕</button>
            </div>
        </template>
    </div>

    <!-- Hidden input for kept images -->
    <input
        type="hidden"
        name="existing_images"
        :value="JSON.stringify(existingImages)"
    >

    <!-- Dropzone -->
    <div
        @drop.prevent="handleFiles($event.dataTransfer.files); isDragging=false"
        @dragover.prevent="isDragging=true"
        @dragleave.prevent="isDragging=false"
        @click="$refs.file.click()"
        :class="isDragging ? 'bg-gray-100' : 'bg-gray-50'"
        class="cursor-pointer rounded-lg p-6 text-center"
    >
        <p class="text-sm text-gray-600">
            Drag & drop images here or click to browse
        </p>

        <input
            type="file"
            multiple
            accept="image/*"
            x-ref="file"
            class="hidden"
            name="{{ $name }}[]"
            @change="handleFiles($event.target.files); $event.target.value=''"
        >
    </div>
</div>
