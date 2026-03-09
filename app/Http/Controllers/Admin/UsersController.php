<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog; // <-- TAMBAHAN 1: Import Model Log
use App\Mail\CompanyValidationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHAN 2: Import Auth

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
        if ($request->filled('search')) {
            $users->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Apply role filter
        if ($request->filled('role')) {
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

        if ($request->ajax()) {
            return view('pages.admin.users.table', compact('users', 'search', 'filter_role', 'roles'))->render();
        }

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

        // --- TAMBAHAN LOG: UPDATE PASSWORD ---
        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'UPDATE',
            'subject_type' => User::class,
            'subject_id'   => $user->id,
            'description'  => "Admin mereset password untuk akun perusahaan: {$user->name} ({$user->email})",
            'ip_address'   => $request->ip(),
        ]);
        // -------------------------------------

        return redirect()->back()->with('success', 'Password untuk ' . $user->name . ' berhasil diatur ulang');
    }

    /**
     * Validate a perusahaan user account
     */
    public function validateUser($id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Only allow validating perusahaan role
        if (!$user->hasRole('perusahaan')) {
            return redirect()->back()->with('error', 'Hanya akun dengan role perusahaan yang dapat divalidasi');
        }

        // Check if company has submitted documents
        if (!$user->perusahaan || $user->perusahaan->berkas !== 'submitted_multi_flow') {
            return redirect()->back()->with('error', 'Perusahaan belum mengirimkan permohonan validasi berkas');
        }

        // Update is_validated to true
        $user->update([
            'is_validated' => true,
        ]);

        // --- TAMBAHAN LOG: VALIDASI ---
        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'UPDATE', // Bisa juga dianggap sebagai APPROVE
            'subject_type' => User::class,
            'subject_id'   => $user->id,
            'description'  => "Admin menyetujui (validasi) akun perusahaan: {$user->name}",
            'ip_address'   => request()->ip(),
        ]);
        // ------------------------------

        // Send validation email
        try {
            Mail::to($user->email)->send(new CompanyValidationMail($user, true));
        } catch (\Exception $e) {
            Log::error('Failed to send validation email to company: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Akun ' . $user->name . ' berhasil divalidasi');
    }

    /**
     * Reject/Unvalidate a perusahaan user account
     */
    public function rejectUser(Request $request, $id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Only allow rejecting perusahaan role
        if (!$user->hasRole('perusahaan')) {
            return redirect()->back()->with('error', 'Hanya akun dengan role perusahaan yang dapat ditolak');
        }

        // Check if company has submitted documents
        if (!$user->perusahaan || $user->perusahaan->berkas !== 'submitted_multi_flow') {
            return redirect()->back()->with('error', 'Perusahaan belum mengirimkan permohonan validasi berkas');
        }

        // Update is_validated to false
        $user->update([
            'is_validated' => false,
        ]);

        $reason = null;
        if ($user->perusahaan) {
            $reason = $request->input('alasan_tolak');
            // If rejected, set status berkas to rejected and save the reason
            $user->perusahaan->update([
                'berkas' => 'rejected',
                'alasan_tolak' => $reason
            ]);
        }

        // --- TAMBAHAN LOG: REJECT ---
        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => 'UPDATE', // Bisa juga dianggap sebagai REJECT
            'subject_type' => User::class,
            'subject_id'   => $user->id,
            'description'  => "Admin menolak/membatalkan validasi akun perusahaan: {$user->name}",
            'ip_address'   => request()->ip(),
        ]);
        // ----------------------------

        // Send rejection email
        try {
            Mail::to($user->email)->send(new CompanyValidationMail($user, false, $reason));
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email to company: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Akun ' . $user->name . ' status validasi berhasil diperbarui');
    }
}