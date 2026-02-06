<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;

class AdminProfileController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function index()
    {
        $admin = Auth::user(); // Changed from guard('web')->user() to standard auth
        return view('admin.profile.index', compact('admin'));
    }
    
    public function edit($id)
    {
        $admin = Auth::user(); // Changed from guard('web')->user() to standard auth
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user(); // Changed from guard('web')->user() to standard auth
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'whatsapp' => 'nullable|url',
            'address' => 'nullable|string|max:100',
            'profile_image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fullName = trim($request->first_name . ' ' . $request->last_name);

        // Update user profile information
        $admin->update([
            'name' => $fullName,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'social_links' => [
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'linkedin' => $request->linkedin,
                'instagram' => $request->instagram,
                'whatsapp' => $request->whatsapp,
            ],
            'address' => [
               'business_address' => $request->address,
            ]
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($admin->profile_image && File::exists(public_path('uploads/profile/' . $admin->profile_image))) {
                File::delete(public_path('uploads/profile/' . $admin->profile_image));
            }

            $image = $request->file('profile_image');
            $file_name = time() . '.' . $image->extension();

            $this->generateProfileThumbnail($image, $file_name);
            $admin->update(['profile_image' => $file_name]);
        }

        return redirect()->route('admin.profile.index')->with('status', 'Profile updated successfully.');
    }
    
    protected function generateProfileThumbnail(string $filePath, string $imageName): void
    {
        $destinationPath = public_path('uploads/profile');

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $img = $this->imageManager
            ->read($filePath)
            ->cover(124, 124);

        $img->save(
            $destinationPath . '/' . $imageName,
            quality: 90
        );
    }
}