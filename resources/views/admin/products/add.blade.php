@extends('admin.layouts.app')
@section('content')
<x-common.page-breadcrumb pageTitle="Product Information" />

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        
        {{-- Left Card --}}
        <x-common.component-card>
            <div class="space-y-6">
                {{-- Product Name --}}
                <x-form.input.text
                    label="Product Name"
                    id="prod_name"
                    name="name"
                    placeholder="Enter Product name"
                />

                {{-- Product Slug --}}
                <x-form.input.text
                    label="Product Slug"
                    id="prod_slug"
                    name="slug"
                    placeholder="Automatically generated from Product Name"
                />

                {{-- Category & Brand --}}
                <div class="grid grid-cols-2 gap-6">
                     <x-form.input.select
                        label="Category"
                        name="category_id"
                        id="category_select"
                        :options="$categories"
                        optionValue="id"
                        optionLabel="name"
                        :optionData="['code' => 'code']"
                    />


                    <x-form.input.select 
                        label="Brand"
                        name="brand_id"
                        :options="$brands"
                    />
                </div>

                {{-- Description --}}
                <x-form.form-elements.text-area-inputs />

                {{-- Main Image --}}
                <x-form.form-elements.file-input-example name="image" />
            </div>
        </x-common.component-card>

        {{-- Right Card --}}
        <x-common.component-card>
            <div class="space-y-6">
                
                {{-- Gallery Images --}}
                <x-form.form-elements.dropzone/>

                {{-- Pricing, SKU, Stock, Featured, Shipping --}}
                <div class="grid grid-cols-2 gap-6">
                    <x-form.input.text
                        label="Reg Price"
                        name="regular_price"
                        placeholder="Enter Regular Price"
                    />
                    <x-form.input.text
                        label="Sale Price"
                        name="sale_price"
                        placeholder="Enter Sale Price"
                    />

                    {{-- SKU (readonly, auto-preview) --}}
                    <x-form.input.text
                        label="SKU"
                        name="SKU"
                        id="sku_input"
                        placeholder="SKU preview will appear here"
                        readonly
                    />

                    {{-- Stock Status --}}
                    <x-form.input.select 
                        label="Stock"
                        name="stock_status"
                        id="stock_status"
                        :options="['instock' => 'In Stock', 'outofstock' => 'Out of Stock', 'preorder' => 'Preorder']"
                    />

                    {{-- Quantity (hidden initially) --}}
                    <div id="quantity_wrapper" style="display: none;">
                        <x-form.input.text
                            label="Quantity"
                            name="quantity"
                            id="quantity_input"
                            placeholder="Enter quantity"
                        />
                    </div>

                    {{-- Featured --}}
                    <x-form.input.select 
                        label="Featured"
                        name="featured"
                        :options="['0' => 'No', '1' => 'Yes']"
                    />

                    {{-- Shipping Class --}}
                    <x-form.input.select
                        label="Shipping Class"
                        name="shipping_class_id"
                        :options="$shippingClassOptions"
                        id="shipping_class"
                    />

                    {{-- Shipping Units --}}
                    <x-form.input.select
                        label="Shipping Units"
                        name="shipping_units"
                        :options="[]"
                        id="shipping_units"
                    />
                </div>

                {{-- Submit Button --}}
                <span>
                    <x-ui.button size="sm" type="submit">Save</x-ui.button>
                </span>

            </div>
        </x-common.component-card>

    </div>
</form>     
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // --- Slug generation ---
    const nameInput = document.getElementById("prod_name");
    const slugInput = document.getElementById("prod_slug");

    if (nameInput && slugInput) {
        nameInput.addEventListener("input", function () {
            slugInput.value = stringToSlug(this.value);
        });
    }

    // --- Stock / Quantity logic ---
    const stockSelect = document.getElementById('stock_status');
    const quantityWrapper = document.getElementById('quantity_wrapper');
    const quantityInput = document.getElementById('quantity_input');
    const quantityLabel = quantityWrapper.querySelector('label');

    function updateQuantity() {
        const stockStatus = stockSelect.value;

        if (stockStatus === 'instock') {
            quantityWrapper.style.display = 'block';
            quantityLabel.textContent = 'Quantity Available';
        } else if (stockStatus === 'preorder') {
            quantityWrapper.style.display = 'block';
            quantityLabel.textContent = 'Pre-order Limit';
        } else { // outofstock
            quantityWrapper.style.display = 'none';
            quantityInput.value = '';
        }
    }

    stockSelect.addEventListener('change', updateQuantity);
    updateQuantity(); // initialize

    // --- Shipping class and units logic ---
    const shippingClasses = @json($shippingClasses);
    const shippingClassSelect = document.getElementById('shipping_class');
    const shippingUnitsSelect = document.getElementById('shipping_units');
    const selectedUnit = "{{ $product->shipping_unit ?? '' }}";

    function populateUnits(classId) {
        shippingUnitsSelect.innerHTML = '<option value="">Select Units</option>';
        shippingUnitsSelect.disabled = true;

        if (!classId) return;

        const selectedClass = shippingClasses.find(cls => cls.id == classId);
        if (!selectedClass) return;

        for (let i = selectedClass.min_units; i <= selectedClass.max_units; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;

            if (i == selectedUnit) option.selected = true;

            shippingUnitsSelect.appendChild(option);
        }
        shippingUnitsSelect.disabled = false;
    }

    if (shippingClassSelect.value) populateUnits(shippingClassSelect.value);

    shippingClassSelect.addEventListener("change", function () {
        populateUnits(this.value);
    });

   document.getElementById('category_select').addEventListener('change', function () {
    const option = this.selectedOptions[0];
    if (!option) return;

    const code = option.dataset.code;
    if (!code) return;

    const year = new Date().getFullYear();
    const random = Math.floor(1000 + Math.random() * 9000);

    document.getElementById('sku_input').value =
        `KMW-${code}-${year}-${random}`;
});

    // --- Utility: Slug generator ---
    function stringToSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-');
    }
});
</script>
@endpush
