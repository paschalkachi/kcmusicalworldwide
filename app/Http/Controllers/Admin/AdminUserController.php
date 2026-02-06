<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load('roles'); // Load roles for the specific user
        $roles = Role::all(); // Get all available roles
        return view('admin.users.show', compact('user', 'roles'));
    }
    
    /**
     * Update the role of the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,id'
        ]);

        // Detach all current roles
        $user->roles()->detach();
        
        // Attach the new role
        $user->roles()->attach($request->role);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }
}