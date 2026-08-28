<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show all users (Figure 6.13).
     */
    public function index(Request $request)
    {
        $query = User::where('role_id', 2); // only show account holders, not other admins

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->get();

        $totalUsers = User::where('role_id', 2)->count();
        $totalActiveUsers = User::where('role_id', 2)->where('status', 'active')->count();

        return view('admin.index', compact('users', 'totalUsers', 'totalActiveUsers'));
    }

    /**
     * Activate or deactivate a user (Figure 6.14).
     */
    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        $message = $user->status === 'active'
            ? 'User activated successfully.'
            : 'User deactivated successfully.';

        return redirect()->route('admin.index')->with('success', $message);
    }

    /**
     * Delete a user (Figure 6.15).
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }
}