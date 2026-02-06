@extends('admin.layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Lagos Locations" />

    <div class="space-y-6">
        <x-common.component-card title="Latest Locations">
            {{-- ======================= --}}
            {{-- DESKTOP TITLE + TABLE (lg+) --}}
            {{-- ======================= --}}
            <div class="hidden lg:block">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Lagos Locations</h2>

                <div class="overflow-x-auto">
                    <x-tables.basic-tables.basic-tables-three 
                        title="Latest Locations"
                        :columns="['Name','Shipping Price','Actions']"
                        >
                        
                        @forelse ($locations as $location)
                            <tr>
                                
                                <!-- Name -->
                                <td class="px-4 py-4 whitespace-nowrap border-r border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                       

                                        <!-- Locations Name -->
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $location->name }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Shipping Price -->
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                                    {{ $location->shipping_price }}
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
                                            <a href="{{ route('lagos-locations.edit', $location) }}"
                                                class="block px-3 py-2 text-sm hover:bg-gray-100">
                                                Edit
                                            </a>

                                            <form action="{{ route('lagos-locations.destroy', $location) }}" method="POST">
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
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                    No Locations found.
                                </td>
                            </tr>
                        @endforelse

                        <x-slot name="actions">
                            <a href="{{ route('lagos-locations.create') }}"
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
                            {{ $locations->links() }}
                        </x-slot>

                    </x-tables.basic-tables.basic-tables-three>
                </div>
            </div>
            
            {{-- ======================= --}}
            {{-- MOBILE TITLE + ACTIONS (< lg) --}}
            {{-- ======================= --}}
            <div class="lg:hidden flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Lagos Locations</h2>

                {{-- Mobile action button --}}
                <a href="{{ route('lagos-locations.create') }}"
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

                @forelse ($locations as $location)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-category-white dark:bg-category-dark p-4 shadow-sm hover:shadow-md transition">

                        {{-- Header --}}
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white leading-tight truncate" title="{{ $location->name }}">
                                    {{ $location->name }}
                                </p>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 pt-3">
                            <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Shipping Price</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $location->shipping_price }}</span></div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <a href="{{ route('lagos-locations.edit', $location) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-blue-600 text-blue-600 dark:text-blue-400 py-2 text-sm font-medium hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                                Edit
                            </a>

                            <form action="{{ route('lagos-locations.destroy', $location) }}" method="POST" class="inline-block w-full">
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
                    <p class="text-center text-gray-500 dark:text-gray-400 py-6">No locations found.</p>
                @endforelse

                {{-- Mobile Pagination --}}
                @if ($locations->hasPages())
                    <div class="mt-6">
                        {{ $locations->links() }}
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

            // Confirm deletion
            if (confirm("Are you sure you want to delete this location?")) {
                // Submit the parent form
                const form = this.closest('form');
                form.submit();
            }
        });
    });
});
</script>
@endpush