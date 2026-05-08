<?php

namespace App\Events;

use App\Models\Permission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Permission $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function broadcastOn(): array
    {
        // Channel privat per santri — hanya wali santri tersebut yg berhak menerima
        return [new PrivateChannel("santri.{$this->permission->student_id}")];
    }

    public function broadcastAs(): string
    {
        return 'PermissionStatusUpdated';
    }

    public function broadcastWith(): array
    {
        $p = $this->permission;
        return [
            'id'              => (int) $p->id,
            'student_id'      => (int) $p->student_id,
            'jenis'           => $p->jenis,
            'tanggal_mulai'   => optional($p->tanggal_mulai)->format('Y-m-d'),
            'tanggal_selesai' => optional($p->tanggal_selesai)->format('Y-m-d'),
            'keterangan'      => $p->keterangan,
            'status'          => $p->status,
            'approved_by'     => $p->approved_by,
            'approved_at'     => optional($p->approved_at)->toIso8601String(),
            'catatan'         => $p->catatan,
            'created_at'      => optional($p->created_at)->toIso8601String(),
            'updated_at'      => optional($p->updated_at)->toIso8601String(),
        ];
    }
}
