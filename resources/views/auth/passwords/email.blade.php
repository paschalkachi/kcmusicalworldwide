@extends('layouts.auth')

@section('title', $title ?? 'Reset Password')

@section('content')
<div class="container mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">
        <!-- Left Panel: Form -->
        <div class="px-10 py-20 md:py-0">
            <div class="max-w-md mx-auto">
                <!-- Header -->
                <div class="mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reset Your Password</h1>
                    <p class="text-gray-600 mt-2 dark:text-gray-400">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                </div>

                <!-- Status Message -->
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-800 rounded-lg">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            value="{{ old('email') }}"
                            required 
                            autocomplete="email" 
                            autofocus 
                            class="w-full px-4 py-3 border @error('email') border-red-500 @enderror border-gray-300 rounded-lg bg-transparent text-gray-900 focus:ring-primary-500 focus:border-primary-500 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-brand-500 hover:bg-brand-600 text-white font-medium py-3 px-4 rounded-lg transition duration-300 ease-in-out"
                    >
                        Send Password Reset Link
                    </button>
                </form>

                <!-- Sign In Link -->
                <div class="mt-6">
                    <p class="text-center text-sm font-normal text-gray-700 dark:text-gray-400">
                        Remembered your password? 
                        <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400">
                            Sign In
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Panel: Visual -->
        <div class="bg-brand-950 relative hidden h-full w-full items-center md:flex md:w-1/2 dark:bg-white/5">
            <div class="z-1 flex items-center justify-center">
                <!-- ===== Common Grid Shape Start ===== -->
                <x-common.common-grid-shape />
                <div class="flex max-w-xs flex-col items-center">
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
    </div>
</div>
@endsection