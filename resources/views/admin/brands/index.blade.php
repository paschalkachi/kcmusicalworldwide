@extends('admin.layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Brands" />

    <div class="space-y-6">
        <x-common.component-card>

            {{-- ======================= --}}
            {{-- MOBILE TITLE + ACTIONS (< lg) --}}
            {{-- ======================= --}}
            <div class="lg:hidden flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Latest Brands</h2>

                {{-- Mobile action button --}}
                <a href="{{ route('brands.create') }}"
                    class="group inline-flex items-center gap-2 rounded-lg border 
                                bg-white dark:bg-gray-700 px-4 py-2.5 text-sm font-medium 
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
            {{-- DESKTOP TITLE + TABLE (lg+) --}}
            {{-- ======================= --}}
            <div class="hidden lg:block overflow-x-auto">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Latest Brands</h2>

                <div class="min-w-full overflow-x-auto">
                    <x-tables.basic-tables.basic-tables-three 
                        title="Latest Brands"
                        :columns="['Name','Slug','Products','Action']"
                        >
                        @forelse ($brands as $brand)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <!-- Name -->
                                <td class="px-4 py-4 border-r border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <img 
                                            src="{{ $brand->image ? asset('uploads/brands/' . $brand->image) : asset('images/logo/logo-icon.png') }}"
                                            class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $brand->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $brand->slug }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 border-r border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">{{ $brand->slug }}</td>
                                <td class="px-4 py-4 border-r border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white">{{ $brand->products_count ?? 0 }}</td>

                                {{-- Actions --}}
                                <td class="px-4 py-4 text-center border-r border-gray-200 dark:border-gray-700 last:border-r-0">
                                    <x-common.table-dropdown>
                                        <x-slot name="button">
                                            <button class="text-xl text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">⋮</button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <a href="{{ route('brands.edit', $brand) }}"
                                                class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 text-gray-700 dark:text-gray-300 transition-colors duration-150">
                                                Edit
                                            </a>

                                            <form action="{{ route('brands.destroy', $brand) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="delete w-full text-left px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                                                    Delete
                                                </button>
                                            </form>
                                        </x-slot>
                                    </x-common.table-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500 dark:text-gray-400">
                                    No brands found.
                                </td>
                            </tr>
                        @endforelse

                        <x-slot name="actions">
                            <a href="{{ route('brands.create') }}"
                                class="group inline-flex items-center gap-2 rounded-lg border 
                                bg-white dark:bg-gray-700 px-4 py-2.5 text-sm font-medium 
                                transition-all duration-200 ease-in-out
                                border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 
                                hover:bg-gray-50 dark:hover:bg-gray-600
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
                            {{ $brands->links() }}
                        </x-slot>

                    </x-tables.basic-tables.basic-tables-three>
                </div>
            </div>

            {{-- ======================= --}}
            {{-- MOBILE CARDS (< lg) --}}
            {{-- ======================= --}}
            <div class="lg:hidden space-y-4">

                @forelse ($brands as $brand)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-category-white dark:bg-category-dark p-4 shadow-sm hover:shadow-md transition">

                        {{-- Header --}}
                        <div class="flex items-start gap-3">
                            <img 
                                src="{{ $brand->image ? asset('uploads/brands/' . $brand->image) : asset('images/logo/logo-icon.png') }}"
                                class="w-14 h-14 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white leading-tight truncate" title="{{ $brand->name }}">
                                    {{ $brand->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate" title="{{ $brand->slug }}">
                                    {{ $brand->slug }}
                                </p>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 pt-3">
                            <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Slug</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $brand->slug }}</span></div>
                            <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Products</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $brand->products_count ?? 0 }}</span></div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <a href="{{ route('brands.edit', $brand) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-blue-600 text-blue-600 dark:text-blue-400 py-2 text-sm font-medium hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                                Edit
                            </a>

                            <form action="{{ route('brands.destroy', $brand) }}" method="POST" class="inline-block w-full">
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
                    <p class="text-center text-gray-500 dark:text-gray-400 py-6">No brands found.</p>
                @endforelse

                {{-- Mobile Pagination --}}
                @if ($brands->hasPages())
                    <div class="mt-6">
                        {{ $brands->links() }}
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
                    if (confirm("Are you sure you want to delete this brand? This action cannot be undone.")) {
                        // Submit the parent form
                        const form = this.closest('form');
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush