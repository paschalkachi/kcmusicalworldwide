@extends('admin.layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="User Details" />

<div class="mb-6">
    <a href="{{ route('users.index') }}"
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
        ← Back to Users
    </a>
</div>

<div class="space-y-6">
    {{-- User Profile Card --}}
    <x-common.component-card title="User Profile">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <h4 class="font-semibold text-gray-800 dark:text-white/90">Personal Information</h4>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Name:</span> {{ $user->name }}</p>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Email:</span> {{ $user->email }}</p>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Email Verified:</span> 
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($user->email_verified_at) 
                            bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 
                        @else 
                            bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 
                        @endif">
                        @if($user->email_verified_at) Yes @else No @endif
                    </span>
                </p>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Member Since:</span> {{ $user->created_at->format('M d, Y h:i A') }}</p>
            </div>

            <div class="space-y-2">
                <h4 class="font-semibold text-gray-800 dark:text-white/90">Roles & Permissions</h4>
                @if($user->roles->count() > 0)
                    @foreach($user->roles as $role)
                        <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Current Role:</span> 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ ucfirst($role->name) }}
                            </span>
                        </p>
                    @endforeach
                @else
                    <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Role:</span> No role assigned</p>
                @endif
            </div>
        </div>
    </x-common.component-card>

    {{-- Update User Role Card --}}
    <x-common.component-card title="Update User Role">
        <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-brand-25 dark:bg-gray-800 rounded-lg p-6 border border-brand-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Current Role</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            User currently has:
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $user->getPrimaryRoleAttribute() }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-grow">
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Update Role
                            </label>
                            
                            <select id="role" name="role" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-base dark:bg-gray-800 dark:text-white dark:border-gray-700 border-2 border-gray-300 dark:border-gray-700 py-3 px-4 transition-all duration-200 focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->hasRole($role->slug) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" 
                                class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-700 text-white font-semibold rounded-lg hover:from-brand-600 hover:to-brand-800 focus:outline-none focus:ring-4 focus:ring-brand-500 focus:ring-opacity-50 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                Update Role
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-common.component-card>

    {{-- User Activity Card --}}
    <x-common.component-card title="User Activity">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <h4 class="font-semibold text-gray-800 dark:text-white/90">Recent Orders</h4>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Total Orders:</span> 
                    {{ $user->orders->count() ?? 0 }}
                </p>
            </div>

            <div class="space-y-2">
                <h4 class="font-semibold text-gray-800 dark:text-white/90">Last Activity</h4>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Last Login:</span> 
                    @if($user->updated_at)
                        {{ $user->updated_at->format('M d, Y h:i A') }}
                    @else
                        Never
                    @endif
                </p>
            </div>
        </div>
    </x-common.component-card>

</div>
@endsection