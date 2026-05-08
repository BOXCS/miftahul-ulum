<?php

namespace App\Events;

use App\Models\Announcement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnnouncementCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Announcement $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function broadcastOn(): array
    {
        // Channel publik — semua wali santri menerima pengumuman
        return [new Channel('pengumuman')];
    }

    public function broadcastAs(): string
    {
        return 'AnnouncementCreated';
    }

    public function broadcastWith(): array
    {
        $a = $this->announcement;
        return [
            'id'           => (int) $a->id,
            'judul'        => $a->judul,
            'isi'          => $a->konten,         // mobile pakai field 'isi'
            'konten'       => $a->konten,
            'kategori'     => $a->kategori,
            'foto'         => null,
            'tgl_mulai'    => optional($a->published_at)->toIso8601String(),
            'tgl_selesai'  => null,
            'is_published' => (bool) $a->is_published,
            'published_at' => optional($a->published_at)->toIso8601String(),
            'created_at'   => optional($a->created_at)->toIso8601String(),
            'updated_at'   => optional($a->updated_at)->toIso8601String(),
        ];
    }
}
