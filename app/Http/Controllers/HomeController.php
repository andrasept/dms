<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\User;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() 
    {
        $departments = Department::all();
        $users = User::all();
        $user_id = auth()->user()->id;
        $dept_id = auth()->user()->dept_id;
        return view('home.index', compact('departments', 'users', 'user_id', 'dept_id'));
    }
}
