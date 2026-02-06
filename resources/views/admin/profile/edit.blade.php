@extends('admin.layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Admin Profile" />
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Profile</h3>
  
            <div
                class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
                <div class="px-2 pr-14">
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                        Edit Personal Information
                    </h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                        Update your details to keep your profile up-to-date.
                    </p>
                    
                    @if(session('status'))
                        <div class="alert alert-success mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <form class="flex flex-col" enctype="multipart/form-data" action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    <div class="custom-scrollbar h-[458px] overflow-y-auto p-2">
                        <div>
                            <h5 class="mb-5 text-lg font-medium text-gray-800 dark:text-white/90 lg:mb-6">
                                Profile Image
                            </h5>

                            <div class="mb-6 flex flex-col items-center">
                                <div class="w-24 h-24 rounded-full overflow-hidden mb-4" id="imagePreviewContainer">
                                    @if(auth()->user()->profile_image)
                                        <img src="{{ asset('uploads/profile/' . auth()->user()->profile_image) }}" alt="Profile Image" class="w-full h-full object-cover" id="imagePreview">
                                    @else
                                        <div class="bg-gray-200 border-2 border-dashed rounded-full w-full h-full flex items-center justify-center text-gray-500" id="imagePreview">
                                            No Image
                                        </div>
                                    @endif
                                </div>
                                
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Upload New Profile Picture
                                </label>
                                <input type="file" name="profile_image" id="profile_image" accept="image/*"
                                    class="dark:bg-dark-900 w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-500 file:text-white hover:file:bg-brand-600" onchange="previewImage(event)" />
                                
                                @error('profile_image')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <h5 class="mb-5 text-lg font-medium text-gray-800 dark:text-white/90 lg:mb-6">
                                Social Links
                            </h5>

                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Facebook
                                    </label>
                                    <input type="text" name="facebook"
                                        value="{{ auth()->user()->social_links['facebook'] ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('facebook')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        X.com (Twitter)
                                    </label>
                                    <input type="text" name="twitter"
                                        value="{{ auth()->user()->social_links['twitter'] ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('twitter')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Linkedin
                                    </label>
                                    <input type="text" name="linkedin"
                                        value="{{ auth()->user()->social_links['linkedin'] ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('linkedin')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Instagram
                                    </label>
                                    <input type="text" name="instagram"
                                        value="{{ auth()->user()->social_links['instagram'] ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('instagram')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        WhatsApp
                                    </label>
                                    <input type="text" name="whatsapp"
                                        value="{{ auth()->user()->social_links['whatsapp'] ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('whatsapp')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-7">
                            <h5 class="mb-5 text-lg font-medium text-gray-800 dark:text-white/90 lg:mb-6">
                                Business Address
                            </h5>

                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                <div class="col-span-2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Business Address
                                    </label>
                                    <input type="text" name="address" 
                                        value="{{ auth()->user()->address['business_address'] ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('address')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-7">
                            <h5 class="mb-5 text-lg font-medium text-gray-800 dark:text-white/90 lg:mb-6">
                                Personal Information
                            </h5>

                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        First Name
                                    </label>
                                    <input type="text" name="first_name"
                                        value="{{ explode(' ', auth()->user()->name)[0] ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('first_name')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 lg:col-span-1">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Last Name
                                    </label>
                                    <input type="text" name="last_name"
                                        value="{{ implode(' ', array_slice(explode(' ', auth()->user()->name), 1)) ?: '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('last_name')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 lg:col-span-1">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Email Address
                                    </label>
                                    <input type="text" name="email" value="{{ auth()->user()->email }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('email')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 lg:col-span-1">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Phone
                                    </label>
                                    <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('phone')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Bio
                                    </label>
                                    <input type="text" name="bio" value="{{ auth()->user()->bio ?? '' }}"
                                        class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    
                                    @error('bio')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <a href="{{ route('admin.profile.index') }}"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
    </div>
    
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            
            reader.onload = function() {
                if (!imagePreview.src) {
                    // Create new img element if one doesn't already exist
                    const imgElement = document.createElement('img');
                    imgElement.id = 'imagePreview';
                    imgElement.className = 'w-full h-full object-cover';
                    imgElement.src = reader.result;
                    imagePreviewContainer.innerHTML = '';
                    imagePreviewContainer.appendChild(imgElement);
                } else {
                    imagePreview.src = reader.result;
                }
            }
            
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
