<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show()
    {
        $users = User::select('id','name','email')->get();
        return response()->json($users);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json('User deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return response()->json('User updated successfully');
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        return response()->json('User created successfully');
    }

}
