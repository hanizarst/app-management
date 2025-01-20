<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    function index()
    {
        $users = User::all();
        return view('pages.users-list', compact('users'));
    }
}
