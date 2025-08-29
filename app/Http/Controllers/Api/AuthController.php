<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * 
 * Controlador para manejar la autenticación de usuarios
 * de momento no tiene logout ni habilitado el registro (esto es commentado en routes/api.php)
 * Implementar cuando sea necesario
 */
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();
        if(! $user || Hash::check($data['password'], $user->password) === false) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token, 
            'user' => $data]);
    }
    public function logout(Request $request){}
    /** implementar cuando sea necesario */ 
    
}
