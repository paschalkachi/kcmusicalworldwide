@extends('admin.layouts.app')
@section('content')
<x-common.page-breadcrumb pageTitle="Location Information" />
<x-common.component-card>
        <form action="{{ isset($state) ? route('states.update', $state) : route('states.store') }}" method="POST"  enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($state))
                @method('PUT')
            @endif
            
            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-6">            
                <x-form.input.text
                    label="State Name" id="name" name="name" :value="isset($state) ? $state->name : ''" placeholder="Enter State Name" readonly
                />

                <x-form.input.text
                    label="State Shipping Price" name="base_shipping_price" :value="isset($state) ? $state->base_shipping_price : ''" placeholder="Enter State Shipping Price" required
                />
                </div>

                <div class="space-y-6">
                    <span>
                    <x-ui.button size="sm" type="submit">{{ isset($state) ? 'Update' : 'Save' }}</x-ui.button>
                    </span>
                </div>
            </div>
        </form>     

</x-common.component-card>
@endsection