@extends('admin.layouts.app')
@section('content')
<x-common.page-breadcrumb pageTitle="Location Information" />
<x-common.component-card>
        <form action="{{ isset($lagosLocation) ? route('lagos-locations.update', $lagosLocation) : route('lagos-locations.store') }}" method="POST"  enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($lagosLocation))
                @method('PUT')
            @endif
            
            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-6">            
                <x-form.input.text
                    label="Location Name" id="cat_name" name="name" :value="isset($lagosLocation) ? $lagosLocation->name : ''" placeholder="Enter Location Name" required
                />

                <x-form.input.text
                    label="Location Shipping Price" name="shipping_price" :value="isset($lagosLocation) ? $lagosLocation->shipping_price : ''" placeholder="Enter Location Shipping Price" required
                />
                </div>
                
                <div class="space-y-6">
                    <span>
                    <x-ui.button size="sm" type="submit">{{ isset($lagosLocation) ? 'Update' : 'Save' }}</x-ui.button>
                    </span>
                </div>
            </div>
        </form>     

</x-common.component-card>
@endsection