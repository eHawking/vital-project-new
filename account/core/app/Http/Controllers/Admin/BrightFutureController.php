<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BrightFutureController extends Controller
{
    public function activeUsers()
    {
        $pageTitle = 'Bright Future Plan Users';
        $users = User::where('bright_future_plan', 1)->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.bright_future.index', compact('pageTitle', 'users'));
    }
}
