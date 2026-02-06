@extends('admin.layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Shipping Classes" />
    <div class="space-y-6">
        <x-common.component-card title="Shipping Classes">
            <!-- Alert for potential sequence issues -->
            @php
                $classes = $shippingClasses->sortBy('min_units');
                $hasIssues = false;
                $prevMax = -1;
                $prevLoadFactor = 0;
                $issues = [];
                
                foreach($classes as $class) {
                    if($class->min_units <= $prevMax) {
                        $hasIssues = true;
                        $issues[] = "Overlap detected: {$class->name} ({$class->min_units}-{$class->max_units}) overlaps with previous range ending at {$prevMax}";
                    } elseif($class->min_units > $prevMax + 1 && $prevMax != -1) {
                        $hasIssues = true;
                        $issues[] = "Gap detected: Between range ending at {$prevMax} and {$class->name} starting at {$class->min_units}";
                    }
                    
                    if($class->load_factor < $prevLoadFactor) {
                        $hasIssues = true;
                        $issues[] = "Load factor issue: {$class->name} ({$class->load_factor}) is less than previous class ({$prevLoadFactor})";
                    }
                    
                    $prevMax = $class->max_units;
                    $prevLoadFactor = $class->load_factor;
                }
            @endphp
            
            @if($hasIssues)
                <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700">
                    <p class="font-bold">Sequence Issues Detected:</p>
                    <ul class="list-disc pl-5">
                        @foreach($issues as $issue)
                            <li>{{ $issue }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-2">Consider reviewing these ranges to maintain a continuous sequence without overlaps.</p>
                    <form method="POST" action="{{ route('shipping-classes.close-gaps') }}" class="mt-2 inline">
                        @csrf
                        <x-ui.button size="sm" type="submit">Close Gaps</x-ui.button>
                    </form>
                </div>
            @endif

            {{-- ======================= --}}
            {{-- DESKTOP TITLE + TABLE (lg+) --}}
            {{-- ======================= --}}
            <div class="hidden lg:block overflow-x-auto">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Shipping Classes</h2>

                <div class="min-w-full overflow-x-auto">
                    <x-tables.basic-tables.basic-tables-three 
                        title="Shipping Classes"
                        :columns="['Name', 'Min Units', 'Max Units', 'Load Factor', 'Action']"
                        >

                        @forelse ($shippingClasses->sortBy('min_units') as $shippingClass)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap border-r border-gray-200 dark:border-gray-700">
                                    <div class="name">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $shippingClass->name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">{{ $shippingClass->min_units }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">{{ $shippingClass->max_units }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                                    <span class="@if($loop->index > 0 && $shippingClass->load_factor < $shippingClasses->sortBy('min_units')->values()[$loop->index - 1]->load_factor) text-red-600 @else text-green-600 @endif">
                                        {{ $shippingClass->load_factor }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-4 text-sm text-center">
                                    <x-common.table-dropdown>
                                        <x-slot name="button">
                                            <button type="button" class="flex item-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                ⋮
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <a href="{{ route('shipping-classes.edit', $shippingClass) }}"
                                                class="block px-3 py-2 text-sm hover:bg-gray-100">
                                                Edit
                                            </a>

                                            <form action="{{ route('shipping-classes.destroy', $shippingClass) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="delete w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                    Delete
                                                </button>
                                            </form>
                                        </x-slot>
                                    </x-common.table-dropdown>
                                </td>                            
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    No shipping classes found.
                                </td>
                            </tr>
                        @endforelse

                        <x-slot name="actions">
                            <a href="{{ route('shipping-classes.create') }}"
                            class="group inline-flex items-center gap-2 rounded-lg border 
                                    bg-white px-4 py-2.5 text-sm font-medium 
                                    transition-all duration-200 ease-in-out
                                    border-gray-300 text-gray-700 hover:bg-gray-100
                                    hover:shadow-md hover:text-brand-600
                                    focus:outline-none focus:ring-2 focus:ring-brand-500/40">

                            <!-- Plus Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 transition-transform duration-200 group-hover:rotate-90"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                                Add New
                            </a>
                        </x-slot>
                        
                        <x-slot name="pagination">
                            {{ $shippingClasses->links() }}
                        </x-slot>

                    </x-tables.basic-tables.basic-tables-three>
                </div>
            </div>
            
            {{-- ======================= --}}
            {{-- MOBILE TITLE + ACTIONS (< lg) --}}
            {{-- ======================= --}}
            <div class="lg:hidden flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Shipping Classes</h2>

                {{-- Mobile action button --}}
                <a href="{{ route('shipping-classes.create') }}"
                    class="group inline-flex items-center gap-2 rounded-lg border 
                                bg-category-white dark:bg-category-dark px-4 py-2.5 text-sm font-medium 
                                transition-all duration-200 ease-in-out
                                border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 
                                hover:bg-gray-50 dark:hover:bg-gray-600
                                hover:shadow-md hover:text-brand-600
                                focus:outline-none focus:ring-2 focus:ring-brand-500/40">

                    <!-- Plus Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transition-transform duration-200 group-hover:rotate-90" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New
                </a>
            </div>
            
            {{-- ======================= --}}
            {{-- MOBILE CARDS (< lg) --}}
            {{-- ======================= --}}
            <div class="lg:hidden space-y-4">
                @forelse ($shippingClasses->sortBy('min_units') as $shippingClass)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-category-white dark:bg-category-dark p-4 shadow-sm hover:shadow-md transition">

                        {{-- Header --}}
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white leading-tight">
                                    {{ $shippingClass->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Load Factor: 
                                    <span class="@if($loop->index > 0 && $shippingClass->load_factor < $shippingClasses->sortBy('min_units')->values()[$loop->index - 1]->load_factor) text-red-600 @else text-green-600 @endif">
                                        {{ $shippingClass->load_factor }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 pt-3">
                            <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Min Units</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $shippingClass->min_units }}</span></div>
                            <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Max Units</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $shippingClass->max_units }}</span></div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <a href="{{ route('shipping-classes.edit', $shippingClass) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-blue-600 text-blue-600 dark:text-blue-400 py-2 text-sm font-medium hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                                Edit
                            </a>

                            <form action="{{ route('shipping-classes.destroy', $shippingClass) }}" method="POST" class="inline-block w-full">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="delete w-full inline-flex items-center justify-center rounded-lg border border-red-600 text-red-600 dark:text-red-400 py-2 text-sm font-medium hover:bg-red-50 dark:hover:bg-gray-700 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-6">No shipping classes found.</p>
                @endforelse

                {{-- Mobile Pagination --}}
                @if ($shippingClasses->hasPages())
                    <div class="mt-6">
                        {{ $shippingClasses->links() }}
                    </div>
                @endif
            </div>
          
        </x-common.component-card>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Select all delete buttons
    const deleteButtons = document.querySelectorAll(".delete");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault(); // prevent immediate form submit

            const form = this.closest("form"); // get the parent form

            // Show SweetAlert confirmation
            swal({
                title: "Are you sure?",
                text: "You want to delete this record?",
                icon: "warning",
                buttons: ["No", "Yes"],
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    form.submit(); // submit the form if confirmed
                }
            });
        });
    });
});
</script>
@endpush