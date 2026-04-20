<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Announcement;
use App\Models\ChatMessage;
use App\Models\Attendance;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $parent = ParentModel::where("user_id", $user->id)->first();

            if ($parent) {
                return response()->json([
                    "success" => true,
                    "token" => "dummy-token-" . $user->id,
                    "akun" => [
                        "id_akun" => $parent->id,
                        "email" => $user->email,
                        "username" => $user->name,
                        "hak_akses" => "orang_tua",
                    ],
                ]);
            }
        }

        return response()->json(
            ["success" => false, "message" => "Email atau password salah."],
            401,
        );
    }

    public function pengumuman()
    {
        return response()->json(Announcement::latest()->get());
    }

    public function santriByOrtu($id)
    {
        $students = Student::where("parent_id", $id)
            ->get()
            ->map(function ($s) {
                return [
                    "id_santri" => (string) $s->id,
                    "nama_santri" => $s->name,
                    "kelas" => $s->class,
                    "nis" => $s->nis,
                    "foto" => $s->photo,
                ];
            });
        return response()->json(["success" => true, "data" => $students]);
    }

    public function kehadiranMingguan($id)
    {
        // Example implementation for weekly attendance
        $attendance = Attendance::where("student_id", $id)
            ->whereBetween("tanggal", [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->get();

        return response()->json(["success" => true, "data" => $attendance]);
    }

    public function kehadiranSummary($id)
    {
        $totalHadir = Attendance::where("student_id", $id)
            ->where("status", "hadir")
            ->count();
        $totalIzin = Attendance::where("student_id", $id)
            ->where("status", "izin")
            ->count();

        return response()->json([
            "success" => true,
            "data" => [
                "hadir" => $totalHadir,
                "izin" => $totalIzin,
            ],
        ]);
    }

    public function perizinan($id)
    {
        $permissions = Permission::where("student_id", $id)->get();
        return response()->json(["success" => true, "data" => $permissions]);
    }

    public function chatHistory($parentId)
    {
        $messages = ChatMessage::where("parent_id", $parentId)
            ->oldest()
            ->get()
            ->map(
                fn($m) => [
                    "pesan" => $m->pesan,
                    "is_from_admin" => $m->is_from_admin,
                    "created_at" => $m->created_at->toIso8601String(),
                ],
            );
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $parentId)
    {
        $msg = ChatMessage::create([
            "parent_id" => $parentId,
            "pesan" => $request->pesan,
            "is_from_admin" => false,
            "is_read" => false,
        ]);

        broadcast(new \App\Events\MessageSent($msg))->toOthers();

        return response()->json(["success" => true, "message" => $msg]);
    }
}
