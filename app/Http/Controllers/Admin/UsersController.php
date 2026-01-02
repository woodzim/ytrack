<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter_role = $request->input('role', '');

        $users = User::query();

        // Apply search filter
        if ($search) {
            $users->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }

        // Apply role filter
        if ($filter_role !== '') {
            $users->whereHas('roles', function ($q) use ($filter_role) {
                $q->where('name', $filter_role);
            });
        }

        // Get all roles for filter dropdown
        $roles = \Spatie\Permission\Models\Role::all();

        $users = $users
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(request()->query());

        return view('pages.admin.users.index', compact('users', 'search', 'filter_role', 'roles'));
    }

    /**
     * Update password for a perusahaan user
     */
    public function updatePassword(Request $request, $id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Only allow resetting password for perusahaan role
        if (!$user->hasRole('perusahaan')) {
            return redirect()->back()->with('error', 'Password hanya dapat direset untuk akun dengan role perusahaan');
        }

        // Validate password
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        // Update password
        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()->back()->with('success', 'Password untuk ' . $user->name . ' berhasil diatur ulang');
    }
}
