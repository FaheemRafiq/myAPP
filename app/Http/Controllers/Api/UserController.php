<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function show()
    {
        $users = User::select('id','name','email')->get();
        return response()->json($users);
    }

    public function delete($id)
    {
        if (!User::find($id)) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }
        User::find($id)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|min:6|max:255|unique:users,name,'.$id,
                'email' => 'required|string|email|min:10|max:255|unique:users,email,'.$id,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }
        $user = User::find($id);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return response()->json('User updated successfully');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|min:6|max:255|unique:users',
                'email' => 'required|string|email|min:10|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->remember_token = Str::random(10);
        $user->save();
        return response()->json([
            'status' => 200,
            'message' => 'User created successfully',
            'name' => $user->name,
            'email' => $user->email,
        ]);
        
    }

}
