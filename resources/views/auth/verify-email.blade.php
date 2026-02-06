@extends('admin.layouts.fullscreen-layout')

@section('content')
<div class="relative z-10 flex h-screen w-full flex-col lg:flex-row bg-white dark:bg-gray-900">
    <!-- Left Panel: Form -->
    <div class="flex w-full flex-1 flex-col justify-center px-6 sm:px-12 lg:w-1/2">
        <div class="mx-auto w-full max-w-md">
            <a href="/" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-6">
                <svg class="stroke-current mr-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>                
                Back to dashboard
            </a>
            <div class="mb-8 text-center">
                <h1 class="text-title-sm sm:text-title-md font-semibold text-gray-800 dark:text-white/90 mb-2">
                    Verify Your Email
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    We sent a verification link to your email. Please click the link to verify your account.
                </p>
            </div>

            <!-- Success Message -->
            @if (session('status') == 'verification-link-sent')
            <div class="mb-5 rounded-lg border-l-4 border-green-500 bg-green-50 p-4 text-green-700 dark:bg-gray-800 dark:text-green-400 flex items-center gap-3 animate-fadeIn">
                <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <p>Verification link has been resent! Check your email.</p>
            </div>
            @endif

            <!-- Resend Form -->
            <form x-data="{ loading: false }" x-on:submit="loading = true" method="POST" action="{{ route('verification.send') }}" class="space-y-5">
                @csrf
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                        Didn't receive the email? You can resend it:
                    </p>
                    <button type="submit" 
                            :disabled="loading"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg x-show="loading" class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
                        </svg>
                        <span x-text="loading ? 'Sending...' : 'Resend Verification Email'"></span>
                    </button>
                </div>
            </form>

            <!-- Logout -->
            <div class="mt-6 text-center">
                <form method="GET" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 underline">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Panel: Visual -->
    <div class="relative hidden h-full w-full items-center justify-center bg-brand-950 lg:flex lg:w-1/2 dark:bg-white/5">
        <div class="flex flex-col items-center">
            <x-common.common-grid-shape />
           <a href="/" class="mb-4 block">
                            {{-- <img class="dark:hidden w-40 h-auto" src="/images/logo/logo.png" alt="Logo" /> --}}
                            <img class="dark:block w-40 h-auto" src="/images/logo/logo-dark.png" alt="Logo" />
                        </a>
                        <p class="text-center text-gray-400 dark:text-white/60">
                            Your one-stop shop for premium musical instruments and accessories.
                        </p>
        </div>
    </div>
</div>
@endsection
