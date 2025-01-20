<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // user list

    public function index()
    {
        // get all users
        $users = User::all();
        return response()->json([
            'message' => 'Liste des utilisateurs',
            'users' => $users,
        ]);
    }

    // user creation
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'erreur de validation',
                'errors' => $validator->errors()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
        ]);
        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'erreur de validation',
                'errors' => $validator->errors()
            ], 401);
        }

        if(!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'identifiant incorrect',
            ], 422);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;
        
        return response()->json([
            'message' => "connexion reussie",
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
