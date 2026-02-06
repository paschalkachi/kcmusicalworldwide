@extends('admin.layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Shipping Class" />

<div class="space-y-6">
    <!-- Display other shipping classes -->
    @if(isset($existingClasses) && $existingClasses->count() > 0)
    <x-common.component-card title="Other Shipping Classes">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Range</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Load Factor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($existingClasses as $class)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->min_units }} - {{ $class->max_units }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->load_factor }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-2 text-sm text-blue-600">If your updated shipping class range overlaps with existing ranges, the system will automatically adjust the overlapping ranges.</p>
    </x-common.component-card>
    @endif

    <x-common.component-card title="Edit Shipping Class">
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('shipping-classes.update', $shippingClass) }}" id="shipping-class-form">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-4">
                    <x-form.input.text
                        label="Name"
                        name="name"
                        placeholder="Enter shipping class name"
                        value="{{ old('name', $shippingClass->name) }}"
                        required
                    />
                    
                  
                </div>
                
                <div class="space-y-4">
                    <x-form.input.text
                        label="Load Factor"
                        name="load_factor"
                        type="number"
                        step="0.01"
                        placeholder="Enter load factor"
                        value="{{ old('load_factor', $shippingClass->load_factor) }}"
                        required
                        id="load-factor-input"
                        :disabled="old('min_units') && old('max_units') ? false : true"
                    />
                    <p class="text-sm text-gray-500 mt-1">Load factor will be enabled after setting the unit range</p>
                    
                </div>
                
                <div class="space-y-4">
                    <x-form.input.text
                        label="Min Units"
                        name="min_units"
                        type="number"
                        placeholder="Enter minimum units"
                        value="{{ old('min_units', $shippingClass->min_units) }}"
                        required
                        id="min-units-input"
                        oninput="toggleLoadFactorField()"
                        onchange="checkPrecedingLoadFactor()"
                    />
                    
                   
                </div>
                
                <div class="space-y-4">
                    <x-form.input.text
                        label="Max Units"
                        name="max_units"
                        type="number"
                        placeholder="Enter maximum units"
                        value="{{ old('max_units', $shippingClass->max_units) }}"
                        required
                        id="max-units-input"
                        oninput="toggleLoadFactorField()"
                        onchange="checkPrecedingLoadFactor()"
                    />
                    
                   
                </div>
            </div>
            
            <div class="flex gap-4">
                <x-ui.button size="sm" type="submit">Update</x-ui.button>
                <x-ui.button size="sm" type="reset">Reset</x-ui.button>
                
                <a href="{{ route('shipping-classes.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </x-common.component-card>
</div>

<script>
    // Initialize state on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleLoadFactorField();
        checkPrecedingLoadFactor();
    });
    
    function toggleLoadFactorField() {
        const minUnitsInput = document.getElementById('min-units-input');
        const maxUnitsInput = document.getElementById('max-units-input');
        const loadFactorInput = document.getElementById('load-factor-input');
        
        // Get the current values
        const minUnitsValue = minUnitsInput.value.trim();
        const maxUnitsValue = maxUnitsInput.value.trim();
        
        // Enable/disable the load factor field based on whether both min and max units have values
        if (minUnitsValue && maxUnitsValue) {
            loadFactorInput.disabled = false;
            // Make sure it's visible
            loadFactorInput.style.display = 'block';
        } else {
            loadFactorInput.disabled = true;
            // Optionally hide it when disabled
            loadFactorInput.style.display = 'none';
        }
    }
    
    function checkPrecedingLoadFactor() {
        const minUnits = parseInt(document.getElementById('min-units-input').value);
        const loadFactorInput = document.getElementById('load-factor-input');
        
        // Only perform validation if the load factor field is enabled
        if (loadFactorInput.disabled) {
            return;
        }
        
        if (isNaN(minUnits)) {
            return;
        }
        
        // Find the preceding class with the highest max_units that is less than our min_units
        @foreach($existingClasses as $class)
            if ({{ $class->max_units }} < minUnits) {
                const currentMinLoadFactor = {{ $class->load_factor }};
                
                // Update the placeholder to show the minimum required value
                loadFactorInput.placeholder = `Minimum: ${currentMinLoadFactor}, Enter load factor`;
                
                // Check if current value is less than required
                if (parseFloat(loadFactorInput.value) < currentMinLoadFactor && loadFactorInput.value !== '') {
                    // Show a warning to the user
                    alert(`Load factor (${loadFactorInput.value}) cannot be less than the preceding class ({{ $class->name }}) load factor (${currentMinLoadFactor})`);
                    loadFactorInput.focus();
                }
            }
        @endforeach
    }
</script>
@endsection