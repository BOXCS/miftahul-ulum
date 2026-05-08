<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use Carbon\Carbon;
// use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $akun = Akun::where('email', $request->email)->first();

        if (!$akun) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        $token = Str::random(60);

        // Simpan token ke tabel password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Kirim email manual (atau gunakan Mailable jika ingin)
        Mail::raw("Gunakan token berikut untuk reset password Anda: $token", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Password');
        });

        return response()->json(['message' => 'Token reset telah dikirim ke email.'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['message' => 'Token tidak valid atau kadaluarsa.'], 400);
        }

        $akun = Akun::where('email', $request->email)->first();

        if (!$akun) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        $akun->password = Hash::make($request->password);
        $akun->save();

        // Hapus token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil direset.'], 200);
    }
}
