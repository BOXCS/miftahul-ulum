<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event yang di-broadcast ke panel admin saat:
 * - Enrollment fingerprint selesai (success/failed)
 * - Scan absensi terjadi (untuk popup live di /attendance)
 */
class IotScanResult implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $kind;        // 'enroll' | 'attendance'
    public array  $payload;

    public function __construct(string $kind, array $payload)
    {
        $this->kind    = $kind;
        $this->payload = $payload;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('iot-admin')];
    }

    public function broadcastAs(): string
    {
        return 'IotScanResult';
    }

    public function broadcastWith(): array
    {
        return array_merge(['kind' => $this->kind], $this->payload);
    }
}
