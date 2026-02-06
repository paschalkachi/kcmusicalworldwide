@extends('admin.layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Users" />
<div class="space-y-6">
    <x-common.component-card title="All Users">
        {{-- ======================= --}}
        {{-- DESKTOP TITLE + TABLE (lg+) --}}
        {{-- ======================= --}}
        <div class="hidden lg:block">
            <x-tables.basic-tables.basic-tables-three 
                title="Users List"
                :columns="['Name','Email','Joined Date','Roles','Actions']"
                >
                @forelse($users as $user)
                    <tr>
                        <!-- Name -->
                        <td class="px-4 py-4 whitespace-nowrap border-r border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $user->name }}
                            </span>
                        </td>

                        <!-- Email -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                            {{ $user->email }}
                        </td>

                        <!-- Joined Date -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>

                        <!-- Roles -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    No Role
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                            <a href="{{ route('users.show', $user->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-brand-500 to-brand-700 rounded-md hover:from-brand-600 hover:to-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse

                <x-slot name="pagination">
                    {{ $users->links() }}
                </x-slot>
            </x-tables.basic-tables.basic-tables-three>
        </div>
        
        {{-- ======================= --}}
        {{-- MOBILE CARDS (< lg) --}}
        {{-- ======================= --}}
        <div class="lg:hidden space-y-4">
            @forelse($users as $user)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-category-white dark:bg-category-dark p-4 shadow-sm hover:shadow-md transition">

                    {{-- Header --}}
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white leading-tight">
                                {{ $user->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate" title="{{ $user->email }}">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 pt-3">
                        <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Joined</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $user->created_at->format('M d, Y') }}</span></div>
                        <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Roles</span>
                            <span class="text-gray-900 dark:text-white font-medium">
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 mr-1">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        None
                                    </span>
                                @endif
                            </span></div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 grid grid-cols-1 gap-3">
                        <a href="{{ route('users.show', $user->id) }}" 
                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-brand-500 to-brand-700 rounded-md hover:from-brand-600 hover:to-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-6">No users found.</p>
            @endforelse

            {{-- Mobile Pagination --}}
            @if ($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </x-common.component-card>
</div>
@endsection