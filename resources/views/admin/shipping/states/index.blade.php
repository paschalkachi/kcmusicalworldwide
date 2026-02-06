@extends('admin.layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="States & Prices" />

<div class="space-y-6">
        <x-common.component-card>

            {{-- ======================= --}}
            {{-- MOBILE TITLE + ACTIONS (< lg) --}}
            {{-- ======================= --}}
            <div class="lg:hidden flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-900">States Shipping Prices</h2>

                {{-- Mobile action button --}}
                <a href="{{ route('states.create') }}"
                    class="group inline-flex items-center gap-2 rounded-lg border 
                                bg-white px-4 py-2.5 text-sm font-medium 
                                transition-all duration-200 ease-in-out
                                border-gray-300 text-gray-700 hover:bg-gray-100
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
            {{-- DESKTOP TITLE + TABLE (lg+) --}}
            {{-- ======================= --}}
            <div class="hidden lg:block">
                <h2 class="text-lg font-semibold mb-4 text-gray-900">States Shipping Prices</h2>

                <div class="overflow-x-auto">
                    <x-tables.basic-tables.basic-tables-three 
                        title="States Shipping Prices"
                        :columns="['Name','Code','Base Shipping Price','Is Lagos?','Actions']"
                        >
                        @forelse ($states as $state)
                            <tr>
                                
                                <!-- Name -->
                                <td class="px-4 py-4 border-r">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $state->name }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 border-r">{{ $state->code }}</td>
                                <td class="px-4 py-4 border-r">{{ $state->base_shipping_price }}</td>
                                <td class="px-4 py-4 border-r">
                                    @if($state->is_lagos)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <svg class="size-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16Zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5Z" clip-rule="evenodd" />
                                            </svg>
                                            Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                            <svg class="size-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16Zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5Z" clip-rule="evenodd" />
                                            </svg>
                                            No
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-4 text-center">
                                    <x-common.table-dropdown>
                                        <x-slot name="button">
                                            <button class="text-xl">⋮</button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <a href="{{ route('states.edit', $state) }}"
                                                class="block px-3 py-2 text-sm hover:bg-gray-100">
                                                Edit
                                            </a>

                                            <form action="{{ route('states.destroy', $state) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button
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
                                <td colspan="5" class="py-6 text-center text-gray-500">
                                    No States found.
                                </td>
                            </tr>
                        @endforelse

                        <x-slot name="actions">
                            <a href="{{ route('states.create') }}"
                                class="group inline-flex items-center gap-2 rounded-lg border 
                                bg-white px-4 py-2.5 text-sm font-medium 
                                transition-all duration-200 ease-in-out
                                border-gray-300 text-gray-700 hover:bg-gray-100
                                hover:shadow-md hover:text-brand-600
                                focus:outline-none focus:ring-2 focus:ring-brand-500/40">

                                <!-- Plus Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform duration-200 group-hover:rotate-90" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add New
                            </a>
                        </x-slot>

                        <x-slot name="pagination">
                            {{ $states->links() }}
                        </x-slot>

                    </x-tables.basic-tables.basic-tables-three>
                </div>
            </div>

            {{-- ======================= --}}
            {{-- MOBILE CARDS (< lg) --}}
            {{-- ======================= --}}
            <div class="lg:hidden space-y-4">

                @forelse ($states as $state)
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition">

                        {{-- Header --}}
                        <div class="flex items-start gap-3">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 leading-tight">
                                    {{ $state->name }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Code: {{ $state->code }}
                                </p>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700">
                            <div><span class="text-gray-500 text-xs uppercase tracking-wide">Base Shipping Price</span>:
                                {{ $state->base_shipping_price }}</div>
                            <div><span class="text-gray-500 text-xs uppercase tracking-wide">Is Lagos?</span>:
                                @if($state->is_lagos)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        <svg class="size-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16Zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5Z" clip-rule="evenodd" />
                                        </svg>
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                        <svg class="size-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16Zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5Z" clip-rule="evenodd" />
                                        </svg>
                                        No
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <a href="{{ route('states.edit', $state) }}"
                                class="inline-flex items-center justify-center gap-1 rounded-lg border border-blue-600 text-blue-600 py-2 text-sm font-medium hover:bg-blue-50 transition">
                                Edit
                            </a>

                            <form action="{{ route('states.destroy', $state) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="delete w-full inline-flex items-center justify-center gap-1 rounded-lg border border-red-600 text-red-600 py-2 text-sm font-medium hover:bg-red-50 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">No States found.</p>
                @endforelse

                {{-- Mobile Pagination --}}
                @if ($states->hasPages())
                    <div class="mt-6">
                        {{ $states->links() }}
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
            if (confirm("Are you sure you want to delete this state?")) {
                // Submit the parent form
                const form = this.closest('form');
                form.submit();
            }
        });
    });
});
</script>
@endpush