@extends('admin.layouts.app')
@section('content')
<x-common.page-breadcrumb pageTitle="Category Information" />
<x-common.component-card>
    <form action="{{ route('categories.store') }}" method="POST"  enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 gap-6">
            <div class="space-y-6">            
            <x-form.input.text
                label="Category Name" id="cat_name" name="name" placeholder="Enter category name" required
            />

            <x-form.input.text
                label="Category Slug" id="cat_slug" name="slug" placeholder="Automatically generated from your Category Name" required
            />
            </div>
            
            <div class="space-y-6">
                <x-form.form-elements.file-input-example name="image" />
                <span>
                <x-ui.button size="sm" type="submit">Save</x-ui.button>
                </span>
            </div>
        </div>
    </form> 
</x-common.component-card>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const nameInput = document.getElementById("cat_name");
        const slugInput = document.getElementById("cat_slug");

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
