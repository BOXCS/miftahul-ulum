<?php

namespace App\Events;

use App\Models\Permission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionSubmitted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Permission $permission;

    public function __construct(Permission $permission)
    {
        // Pastikan relasi student ter-load agar bisa dikirim ke admin
        $permission->loadMissing('student');
        $this->permission = $permission;
    }

    public function broadcastOn(): array
    {
        // Channel admin — semua admin yang authenticated bisa subscribe
        return [new PrivateChannel('permissions-admin')];
    }

    public function broadcastAs(): string
    {
        return 'PermissionSubmitted';
    }

    public function broadcastWith(): array
    {
        $p = $this->permission;
        return [
            'id'         => (int) $p->id,
            'santri'     => $p->student?->name ?? 'N/A',
            'avatar'     => strtoupper(substr($p->student?->name ?? 'N', 0, 2)),
            'kelas'      => $p->student?->class ?? '-',
            'jenis'      => ucfirst($p->jenis),
            'tanggal'    => optional($p->tanggal_mulai)->format('d/m/Y') . ' - ' . optional($p->tanggal_selesai)->format('d/m/Y'),
            'keterangan' => $p->keterangan,
            'status'     => ucfirst($p->status),
            'diajukan'   => 'Wali',
            'tglAjuan'   => $p->created_at?->diffForHumans(),
            'approved_by' => $p->approved_by,
            'catatan'    => $p->catatan,
        ];
    }
}
