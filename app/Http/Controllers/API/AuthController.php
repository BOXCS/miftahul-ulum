<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
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
        return response()->json(['message' => 'Logout successful']);
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

        // Generate 6 digit token untuk mobile verification
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan token ke tabel password_resets dengan expired time
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token), // Hash token untuk keamanan
                'created_at' => Carbon::now()
            ]
        );

        // Kirim email dengan token 6 digit
        try {
            Mail::send('emails.reset-password-mobile', [
                'nama' => $akun->nama_lengkap ?? 'User', // Nama penerima email
                'token' => $token, // Token reset password
                'resetLink' => route('password.reset', ['email' => $request->email, 'token' => $token]) // Link reset password
            ], function ($message) use ($request) {
                $message->to($request->email)
                ->subject('Kode Verifikasi Reset Password - Miftahul Ulum');
            });


            return response()->json([
                'success' => true,
                'message' => 'Kode verifikasi telah dikirim ke email Anda.',
                // 'debug_token' => $token // Hanya untuk testing, hapus di production
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string|size:6', // Token 6 digit
            'password' => 'required|min:8'
        ]);

        // Cari reset token
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset tidak ditemukan.'
            ], 400);
        }

        // Verifikasi token (bandingkan dengan hash)
        if (!Hash::check($request->token, $reset->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi tidak valid.'
            ], 400);
        }

        // Cek expired (5 menit untuk mobile token)
        if (Carbon::parse($reset->created_at)->addMinutes(5)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi sudah kedaluwarsa.'
            ], 400);
        }

        // Cari user
        $akun = Akun::where('email', $request->email)->first();

        if (!$akun) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        // Update password
        $akun->password = Hash::make($request->password);
        $akun->save();

        // Hapus token setelah berhasil
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Log activity (optional)
        \Log::info('Password reset successful for email: ' . $request->email);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset.'
        ], 200);
    }

    // Method untuk handle verifikasi dari web (ketika user klik link di email)
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return response()->json(['message' => 'Token atau email tidak valid'], 400);
        }

        // Verifikasi token masih valid
        $reset = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['message' => 'Token tidak valid atau sudah kadaluarsa'], 400);
        }

        // Return HTML form atau redirect ke mobile app
        return view('reset-password-form', compact('token', 'email'));
    }

    // Method untuk proses reset dari web form
    public function processWebReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $result = $this->resetPassword($request);

        if ($result->status() === 200) {
            return redirect()->route('reset.success')->with('message', 'Password berhasil direset. Silakan login dengan password baru Anda.');
        } else {
            return back()->withErrors(['error' => 'Gagal mereset password. Silakan coba lagi.']);
        }
    }

    // Method untuk resend token (optional)
    public function resendToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return $this->sendResetLink($request);
    }
}
