<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
   public function profile(Request $request)
   {
         return new UserResource($request->user());
   }

   public function getAllUsers()
   {
       $users = User::where('is_active', 1)->get();
       return UserResource::collection($users);
   }

   public function addUser(Request $request)
   {    
         $request->validate([
              'fullName' => 'required|string',
              'username' => 'required|string|unique:users,username',
              'password' => 'required|string|min:6',
              'role' => 'required|string',
         ]);
    
         $user = new User();
         $user->fullName = $request->fullName;
         $user->username = $request->username;
         $user->password = Hash::make($request->password);
         $user->role = $request->role;
         $user->modified_at = now(); // keep this if you use modified_at as updated_at
         $user->save();
    
         return response()->json([
              "status" => "success",
              "message" => "User added successfully",
              "user" => new UserResource($user)
         ], 201);
    }

    public function editUser(Request $request, $id)
    {
        $request->validate([
            'fullName' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->fullName = $request->fullName;
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->modified_at = now();
        $user->save();

        return response()->json([
            "status" => "success",
            "message" => "User updated successfully",
            "user" => new UserResource($user)
        ]);
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = 0;
        $user->save();

        return response()->json([
            'message' => 'User deactivated successfully.',
            'user' => new UserResource($user)
        ]);
    }

    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->modified_at = now();
        $user->save();

        return response()->json([
            "status" => "success",
            "message" => "User activated successfully",
            "user" => new UserResource($user)
        ]);
    }

    public function getDeactivatedUsers()
    {
        $users = User::where('is_active', 0)
            ->get();
        return UserResource::collection($users);
    }
}

