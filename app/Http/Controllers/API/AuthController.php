<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Akun;
// use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class   AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $akun = Akun::where('email', $request->email)->first();
        $token = $akun->createToken('flutter-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'akun' => $akun
        ]);
    }

    public function index(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout succesful']);
    }

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'new_password' => 'required|min:6|confirmed', // password_confirmation harus dikirim juga
    ]);

    $akun = Akun::where('email', $request->email)->first();
    if (!$akun) {
        return response()->json(['message' => 'Email tidak ditemukan'], 404);
    }

    $akun->password = Hash::make($request->new_password);
    $akun->save();

    return response()->json(['message' => 'Password berhasil direset'], 200);
}
}