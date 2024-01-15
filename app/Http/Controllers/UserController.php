<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::All();
        $ruangans = "Cek data";
        $userRole = auth()->user()->role;
        return view('users/index', compact('users', 'ruangans', 'userRole'));
    }

    public function create()
    {
        $roles = Role::All();
        return view('users/create', compact('roles'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,dev,owner,user',
        ]);

        $data = $request->all();
        // dd($data);
        $check = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role']
        ]);

        return redirect()->route('users.index')->withSuccess('Great! You have Successfully loggedin');
    }

    public function edit(User $user)
    {
        $roles = Role::All();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required|in:admin,dev,owner,user',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        if (!empty($request->password)) $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('users.index')->withSuccess('Great! You have Successfully loggedin');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'user has been deleted successfully');
    }
}
