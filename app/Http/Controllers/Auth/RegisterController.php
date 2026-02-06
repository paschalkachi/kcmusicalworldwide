<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show the signup form
     */
    public function showRegistrationForm()
    {
        return view('auth.signup', ['title' => 'Sign Up']);
    }

//     public function register(Request $request)
// {
//     // Validate the request
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|email|unique:users,email',
//         'password' => 'required|string|min:6|confirmed',
//     ]);

//     // Combine names
   

//     // Create the user
//     $user = User::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => Hash::make($request->password), // hashed automatically
//     ]);

    // Debug: make sure it's created
    // dd($user);

    // Login user
    //Auth::login($user);

    // Redirect to email verification
//     return redirect()->route('verification.notice');
// }


    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $name = $request->first_name . ' ' . $request->last_name;

        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);        
        

        // ✅ Assign default role safely
        $role = Role::where('slug', 'user')->first();

        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        // ✅ Fire email verification
        event(new Registered($user));

        // ✅ Login user
        Auth::login($user);

        // ✅ Redirect to verification notice
        return redirect()->route('verification.notice');
    }
}
