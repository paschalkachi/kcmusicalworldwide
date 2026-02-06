@extends('admin.layouts.app')
@section('content')
<x-common.page-breadcrumb pageTitle="Product Information" />
        <form action="{{ route('products.update', $product->id) }}" method="POST"  enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <x-common.component-card>
                <div class="space-y-6">            
                    <x-form.input.text
                        label="Product Name" id="prod_name" name="name" placeholder="Enter Product name"
                        :value="$product->name"
                    />

                    <x-form.input.text
                        label="Product Slug" id="prod_slug" name="slug" placeholder="Automatically generated from your Product Name"
                        :value="$product->slug"
                    />

                    <div class="grid grid-cols-2 gap-6">
                        <x-form.input.select
                            label="Category"
                            name="category_id"
                            id="category_select"
                            :options="$categories"
                            optionValue="id"
                            optionLabel="name"
                            :optionData="['code' => 'code']"
                            :value="$product->category_id"
                        />

                        <x-form.input.select 
                            label="Brand"
                            name="brand_id"
                            :options="$brands"
                            :value="$product->brand_id"
                        />
                    </div>

                    <x-form.form-elements.text-area-inputs :short="$product->short_description" :description="$product->description" />
                    
                    <x-form.form-elements.file-input-example 
                    name="image" 
                    :current="$product->image"
                    folder="products" 
                    />                    
                </div>

                </x-common.component-card>

                <x-common.component-card>
                <div class="space-y-6">

                    {{-- Field to upload gallery Images --}}
                    <x-form.form-elements.dropzone 
                        name="images"
                        :existing="json_decode($product->images ?? '[]', true)"
                    />

                     <div class="grid grid-cols-2 gap-6">
                        <x-form.input.text
                             label="Regular Price" name="regular_price" placeholder="Enter Regular Price" :value="$product->regular_price"
                    />
                        <x-form.input.text
                             label="Sale Price" name="sale_price" placeholder="Enter Sale Price" :value="$product->sale_price"
                    />

                      <x-form.input.text
                            label="SKU"
                            name="SKU"
                            id="sku_input"
                            placeholder="SKU will be auto-generated"
                            :value="$product->SKU"
                            readonly
                        />

    
                        <x-form.input.select 
                            label="Stock"
                            name="stock_status"
                            id="stock_status"
                            :options="[
                                'instock' => 'In Stock',
                                'outofstock' => 'Out of Stock',
                                'preorder' => 'Preorder'
                            ]"
                            :value="$product->stock_status"
                        />

                         <!-- Quantity Input Wrapper (hidden initially) -->
                        <div id="quantity_wrapper" style="display: none;">
                            <x-form.input.text
                                label="Quantity"
                                name="quantity"
                                id="quantity_input"
                                :value="$product->quantity"
                                placeholder="Enter quantity"
                            />
                        </div>

                        <x-form.input.select 
                            label="Featured"
                            name="featured"
                            :options="['0' => 'No', '1' => 'Yes']"
                            :value="$product->featured"
                        />

                        {{-- Shipping Class --}}
                        <x-form.input.select
                            label="Shipping Class"
                            name="shipping_class_id"
                            :options="$shippingClassOptions"
                            :value="$product->shipping_class_id"
                            id="shipping_class"
                        />

                        {{-- Shipping Units --}}
                        <x-form.input.select
                            label="Shipping Units"
                            name="shipping_units"
                            :options="[]"
                            id="shipping_units"
                            :value="$product->shipping_unit"
                            {{-- disabled --}}
                        />
                    </div>
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
window.shippingClasses = @json($shippingClasses);

document.addEventListener("DOMContentLoaded", function () {

    /* ---------------- SLUG ---------------- */
    const nameInput = document.getElementById("prod_name");
    const slugInput = document.getElementById("prod_slug");

    if (nameInput && slugInput) {
        nameInput.addEventListener("input", function () {
            slugInput.value = stringToSlug(this.value);
        });
    }

    /* ------------- SHIPPING -------------- */
    const shippingClassSelect = document.querySelector('select[name="shipping_class_id"]');
    const shippingUnitsSelect = document.querySelector('select[name="shipping_units"]');
    const selectedUnit = "{{ $product->shipping_unit ?? '' }}";

    function populateUnits(classId) {
        shippingUnitsSelect.innerHTML = '<option value="">Select Units</option>';
        shippingUnitsSelect.disabled = true;

        if (!classId) return;

        const selectedClass = window.shippingClasses.find(cls => cls.id == classId);
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
    shippingClassSelect.addEventListener("change", () => populateUnits(shippingClassSelect.value));

    /* -------- STOCK / QUANTITY ------------ */
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
        } else {
            quantityWrapper.style.display = 'none';
            quantityInput.value = '';
        }
    }

    stockSelect.addEventListener('change', updateQuantity);
    updateQuantity();

    /* -------------- SKU PREVIEW ------------ */
    const categorySelect = document.getElementById('category_select');
    const skuInput = document.getElementById('sku_input');
    const originalSku = skuInput.value; // preserve existing SKU

    categorySelect.addEventListener('change', function () {
        const option = this.selectedOptions[0];
        if (!option) return;

        const code = option.dataset.code;
        if (!code) return;

        const year = new Date().getFullYear();
        const rand = Math.floor(1000 + Math.random() * 9000);

        skuInput.value = `${code}-${year}-${rand}`;
    });

});

/* ----------- UTIL ---------------- */
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
