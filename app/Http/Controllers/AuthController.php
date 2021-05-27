<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request) 
    {
        //add try catch
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $access_token = $user->createToken('conexeds')->plainTextToken;

        $response = [
            'user' => $user,
            'access_token' => $access_token,
            'message' => 'Registration Successful'
        ];

        return response($response, 201);
    }

    public function login(Request $request) 
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        
        // Check email
        $user = User::where('email',$fields['email'])->first();

        // Check password
        if(!$user || Hash::check(bcrypt($fields['password']),$user->password)) {
            return response(['message' => 'Invalid Credientals'], 401);
        }

        $access_token = $user->createToken('conexeds')->plainTextToken;

        $response = [
            'user' => $user,
            'access_token' => $access_token
        ];

        return response(['user' => $user, 'access_token' => $access_token, 'message' => 'Login successfully'], 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response(['message' => 'Logged out'], 200);
    }
}
