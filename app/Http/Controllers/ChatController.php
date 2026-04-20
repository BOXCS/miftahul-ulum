<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ParentModel;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $parents = ParentModel::with("students")->get();

        $parentsArray = $parents
            ->map(function ($p) {
                $lastMessage = $p->messages()->latest()->first();
                $studentNames = $p->students->pluck("name")->implode(", ");
                $studentClass = $p->students->first()?->class ?? "-";

                return [
                    "id" => $p->id,
                    "name" => $p->name,
                    "child" => $studentNames ?: "N/A",
                    "kelas" => $studentClass,
                    "initials" => strtoupper(substr($p->name, 0, 2)),
                    "lastMessage" => $lastMessage?->pesan
                        ? substr($lastMessage->pesan, 0, 40) . "..."
                        : "Belum ada pesan",
                    "time" => $lastMessage
                        ? $lastMessage->created_at->diffForHumans()
                        : "-",
                    "unread" => $p
                        ->messages()
                        ->where("is_read", false)
                        ->where("is_from_admin", false)
                        ->count(),
                    "online" => false,
                    "av" => "av" . (($p->id % 6) + 1), // Assign a random color class 1-6
                ];
            })
            ->toArray();

        $total_unread = array_sum(array_column($parentsArray, "unread"));
        $activeParentId = $request->query("active");

        $messages = [];
        $activeParent = null;

        if ($activeParentId) {
            $activeParent = ParentModel::with("students")->findOrFail(
                $activeParentId,
            );
            $messages = ChatMessage::where("parent_id", $activeParentId)
                ->oldest("created_at")
                ->get()
                ->map(
                    fn($m) => [
                        "id" => $m->id,
                        "is_from_admin" => $m->is_from_admin,
                        "pesan" => $m->pesan,
                        "time" => $m->created_at->format("H:i"),
                        "is_read" => $m->is_read,
                    ],
                )
                ->toArray();
        }

        return view(
            "chat.index",
            compact(
                "parentsArray",
                "total_unread",
                "activeParent",
                "activeParentId",
                "messages",
            ),
        );
    }

    public function show(int $parentId)
    {
        ParentModel::findOrFail($parentId);
        return redirect()->route("chat.index", ["active" => $parentId]);
    }

    public function send(Request $request, int $parentId)
    {
        $parent = ParentModel::findOrFail($parentId);

        $validated = $request->validate([
            "pesan" => "required|string|max:1000",
        ]);

        $message = ChatMessage::create([
            "parent_id" => $parentId,
            "pesan" => $validated["pesan"],
            "is_from_admin" => true,
            "is_read" => true,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return redirect()
            ->route("chat.index", ["active" => $parentId])
            ->with("success", "Pesan berhasil dikirim.");
    }
}
