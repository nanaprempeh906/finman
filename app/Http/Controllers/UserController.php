<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users for the current company.
     */
    public function index()
    {
        // Only admins can view user management
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can manage team members.');
        }

        $company = Auth::user()->company;
        $users = $company->users()->with('company')->orderBy('created_at', 'desc')->get();

        return view('users.index', compact('users', 'company'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Only admins can create users
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can add team members.');
        }

        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Only admins can create users
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can add team members.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,admin'],
        ]);

        $company = Auth::user()->company;

        // Check if email already exists in this company
        $existingUser = User::where('email', $request->email)
                           ->where('company_id', $company->id)
                           ->first();

        if ($existingUser) {
            return back()->withErrors(['email' => 'This email is already registered in your company.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $company->id,
            'is_active' => true,
            'email_verified_at' => now(), // Auto-verify for team members added by admin
        ]);

        return redirect()->route('users.index')->with('success', 'Team member added successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Only admins can view user details, or users can view their own profile
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'You can only view your own profile.');
        }

        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            abort(403, 'User not found in your company.');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Only admins can edit other users, or users can edit their own profile
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'You can only edit your own profile.');
        }

        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            abort(403, 'User not found in your company.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Only admins can edit other users, or users can edit their own profile
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'You can only edit your own profile.');
        }

        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            abort(403, 'User not found in your company.');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ];

        // Only admins can change roles and activate/deactivate users
        if (Auth::user()->isAdmin() && Auth::id() !== $user->id) {
            $rules['role'] = ['required', 'in:user,admin'];
            $rules['is_active'] = ['boolean'];
        }

        // Password is optional for updates
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Only admins can update role and status for other users
        if (Auth::user()->isAdmin() && Auth::id() !== $user->id) {
            $updateData['role'] = $request->role;
            $updateData['is_active'] = $request->boolean('is_active');
        }

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        $redirectRoute = Auth::user()->isAdmin() ? 'users.index' : 'profile.edit';
        $message = Auth::user()->isAdmin() ? 'Team member updated successfully!' : 'Profile updated successfully!';

        return redirect()->route($redirectRoute)->with('success', $message);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Only admins can delete users
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can remove team members.');
        }

        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            abort(403, 'User not found in your company.');
        }

        // Prevent admin from deleting themselves
        if (Auth::id() === $user->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        // Prevent deleting the last admin
        $adminCount = User::where('company_id', Auth::user()->company_id)
                         ->where('role', 'admin')
                         ->count();

        if ($user->role === 'admin' && $adminCount <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last administrator. Promote another user to admin first.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Team member removed successfully.');
    }
}
