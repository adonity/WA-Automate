<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function index(Request $request) {
        $user_super = User::admin()->where("role", 2)->get();
        $users = User::admin();

        if($request->super)
            $users = $users->where('parent', $request->super);
        
        $users = $users->get();
        return view('pages.users.index', compact('users', 'user_super'));
    }

    public function create(Request $request) {
        $users = User::admin()->where("role", 2)->get();
        return view('pages.users.create', compact('users'));
    }

    public function edit($id) {
        $users = User::admin()->where("role", 2)->get();
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('users','user'));
    }

    public function show($id) {
        $user = User::findOrFail($id);
        $devices = Device::where('id_users', $id)->get();
        return view('pages.users.show', compact('devices','user'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'email',
            'password' => 'required|confirmed|min:6',
        ]);

        // dd($request);exit();

        if(User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'parent' => $request->role == 3? $request->parent: null,
            'password' => $request->password,
        ]))
            return redirect()
                    ->route("users.index")
                    ->with([
                        'success' => 'New User has been created successfully'
                    ]);
        else
            return redirect()
                    ->back()
                    ->with([
                        'success' => false
                    ]);
    }
    
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'email',
        ]);

        $user = User::findOrFail($id);
        $update = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'parent' => $request->role == 3? $request->parent: null
        ];

        if($request->password){
            if($request->password)
                $update['password'] = $request->password;
        }

        if($user->update($update))
            return redirect()
                    ->route("users.index")
                    ->with([
                        'success' => 'User has been Edited successfully'
                    ]);
        else
            return redirect()
                    ->back()
                    ->with([
                        'success' => false
                    ]);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        
        $users = User::where('parent',$id);
        $users->delete();
        
        return redirect()
            ->back()
            ->with([
                'success' => 'Post has been deleted successfully'
            ]);
    }
}