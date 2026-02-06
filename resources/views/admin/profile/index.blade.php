@extends('admin.layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Admin Profile" />
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Profile</h3>
            <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-start space-x-4">
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            <img
                                src="{{ auth()->user()->profile_image ? asset('uploads/profile/' . auth()->user()->profile_image) : asset('images/logo/logo-icon.png') }}"
                                alt="Profile Image"
                                class="w-16 h-16 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600"
                            >
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                                Personal Information
                            </h4>

                            <div class="grid grid-cols-1 gap-4 md:gap-6 lg:gap-7 2xl:gap-x-32">
                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">First Name</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90 info-first-name break-words overflow-hidden text-ellipsis">
                                        {{ explode(' ', auth()->user()->name)[0] ?? '' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">Last Name</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90 info-last-name break-words overflow-hidden text-ellipsis">
                                        {{ implode(' ', array_slice(explode(' ', auth()->user()->name), 1)) ?: '' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">
                                        Email address
                                    </p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90 info-email break-words overflow-hidden text-ellipsis">
                                        {{ auth()->user()->email }}
                                    </p>
                                </div>

                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">Phone</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90 info-phone break-words overflow-hidden text-ellipsis">
                                        {{ auth()->user()->phone ?? '' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">Bio</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90 info-bio break-words overflow-hidden text-ellipsis">
                                        {{ auth()->user()->bio ?? 'Bio not set' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">Business Address</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90 info-business-address break-words overflow-hidden text-ellipsis">
                                        {{ auth()->user()->address['business_address'] ?? 'Address not set' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                                    Social Media
                                </h4>
                                
                                <div class="grid grid-cols-1 gap-4 md:gap-6 lg:gap-7 2xl:gap-x-32">
                                    <div>
                                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">Facebook</p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90 break-words overflow-hidden text-ellipsis">
                                            @if(auth()->user()->social_links['facebook'] ?? null)
                                                <a href="{{ auth()->user()->social_links['facebook'] }}" target="_blank" class="text-blue-500 hover:underline break-all">
                                                    Facebook Link
                                                </a>
                                            @else
                                                <span class="text-gray-500">Not set</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div>
                                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">Twitter</p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90 break-words overflow-hidden text-ellipsis">
                                            @if(auth()->user()->social_links['twitter'] ?? null)
                                                <a href="{{ auth()->user()->social_links['twitter'] }}" target="_blank" class="text-blue-500 hover:underline break-all">
                                                    Twitter Link
                                                </a>
                                            @else
                                                <span class="text-gray-500">Not set</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div>
                                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">LinkedIn</p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90 break-words overflow-hidden text-ellipsis">
                                            @if(auth()->user()->social_links['linkedin'] ?? null)
                                                <a href="{{ auth()->user()->social_links['linkedin'] }}" target="_blank" class="text-blue-500 hover:underline break-all">
                                                    LinkedIn Link
                                                </a>
                                            @else
                                                <span class="text-gray-500">Not set</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div>
                                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">Instagram</p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90 break-words overflow-hidden text-ellipsis">
                                            @if(auth()->user()->social_links['instagram'] ?? null)
                                                <a href="{{ auth()->user()->social_links['instagram'] }}" target="_blank" class="text-blue-500 hover:underline break-all">
                                                    Instagram Link
                                                </a>
                                            @else
                                                <span class="text-gray-500">Not set</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div>
                                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400 break-words">WhatsApp</p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90 break-words overflow-hidden text-ellipsis">
                                            @if(auth()->user()->social_links['whatsapp'] ?? null)
                                                <a href="{{ auth()->user()->social_links['whatsapp'] }}" target="_blank" class="text-blue-500 hover:underline break-all">
                                                    WhatsApp Link
                                                </a>
                                            @else
                                                <span class="text-gray-500">Not set</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a class="edit-button flex items-center justify-center gap-2" href="{{ route('admin.profile.edit', auth()->user()->id) }}">
                        <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                                fill="" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>
   
@endsection
