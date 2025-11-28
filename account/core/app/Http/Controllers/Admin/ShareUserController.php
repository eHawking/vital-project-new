<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ShareUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = User::where('is_share', 1);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                  ->orWhere('firstname', 'like', "%$search%")
                  ->orWhere('lastname', 'like', "%$search%");
            });
        }

        $sharedUsers = $query->paginate(20);
        $pageTitle = "Manage Shared Users";

        return view('admin.share_users.index', compact('sharedUsers', 'pageTitle', 'search'));
    }

    public function searchUser(Request $request)
    {
        $search = $request->input('username');
        $user = User::where('username', 'like', "%$search%")
                    ->orWhere('firstname', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->first();

        return response()->json($user ? [
            'found' => true,
            'id' => $user->id,
            'name' => $user->firstname . ' ' . $user->lastname,
            'username' => $user->username,
            'is_shared' => (bool) $user->is_share
        ] : ['found' => false]);
    }

    public function updateShareStatus(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'is_share' => 'required|boolean'
            ]);

            $user = User::findOrFail($request->user_id);
            $user->is_share = $request->is_share;
            $user->save();

            $status = $request->is_share ? 'enabled' : 'disabled';
            $notify[] = ['success', "Sharing status for {$user->username} has been $status successfully."];

        } catch (\Exception $e) {
            $notify[] = ['error', 'Failed to update sharing status.'];
        }

        return back()->withNotify($notify);
    }
}