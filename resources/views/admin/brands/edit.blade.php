@extends('admin.layouts.app')
@section('content')
<x-common.page-breadcrumb pageTitle="Edit Brand" />
<x-common.component-card>
    <form 
        action="{{ route('brands.update', $brand) }}" 
        method="POST" 
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">

            <div class="space-y-6">
                <x-form.input.text
                    label="Brand Name"
                    id="brand_name"
                    name="name"
                    :value="$brand->name"
                    placeholder="Enter brand name"
                />

                <x-form.input.text
                    label="Brand Slug"
                    id="brand_slug"
                    name="slug"
                    :value="$brand->slug"
                    placeholder="Automatically generated from your brand Name"
            />
            </div>

            <div class="space-y-6">
                {{-- File input with live preview and current image --}}
                <x-form.form-elements.file-input-example 
                    name="image" 
                    :current="$brand->image" 
                    folder="brands"
                />

                <span>
                    <x-ui.button size="sm" type="submit">Update</x-ui.button>
                </span>
            </div>

        </div>
    </form>
</x-common.component-card>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("brand_name");
    const slugInput = document.getElementById("brand_slug");

    if (nameInput && slugInput) {
        nameInput.addEventListener("input", function () {
            slugInput.value = stringToSlug(this.value);
        });
    }
});

function stringToSlug(text) {
    return text
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/--+/g, '-');
}
</script>
@endpush
