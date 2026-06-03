<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Google\Client as GoogleClient;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json(['token' => $token]);
    }

   //login
    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required'
        ]);

        $client = new GoogleClient([
            'client_id' => env('GOOGLE_CLIENT_ID')
        ]);

        $payload = $client->verifyIdToken($request->id_token);

        if (!$payload) {
            return response()->json([
                'message' => 'Token de Google inválido'
            ], 401);
        }

        $email = $payload['email'];
        $name = $payload['name'] ?? 'Usuario';

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(uniqid()),
            ]);
        }

        $token = $user->createToken('google-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function changePassword(Request $request)
{
    $request->validate([
        'password' => 'required|min:8|confirmed',
    ]);

    $user = $request->user();

    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json([
        'message' => 'Contraseña actualizada correctamente'
    ]);
}
}