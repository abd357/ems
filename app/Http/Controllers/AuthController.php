<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required | email | exists:users,email',
            'password' => 'required'
            
        ]);
        
        $user = User::where('email', $request->email)->first();
        $role = $user->roles()->pluck('name');

        if (!$user || !Hash::check($request->password, $user->password)) {
            return 
            [
                'errors' => [
                    'email' => ['Invalid Credentials'],
                    'password' => ['Invalid Credentials']
                ]
            ];
        }
        
        $token = $user->createToken($user->name);

        return response()->json([
            'user' => $user,
            'role' => $role,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        
        $request->user()->tokens()->delete();
        return response()->json([
            'messages' => 'logged out'
        ]);
    }
    
}
