<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends BaseController
{

    public function index()
    {
        $users = User::all();

        return view('admin.user.index', compact('users'));
    }
}
