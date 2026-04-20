<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel("chat." . $this->message->parent_id)];
    }

    /**
     * Data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            "id" => $this->message->id,
            "pesan" => $this->message->pesan,
            "is_from_admin" => $this->message->is_from_admin,
            "time" => $this->message->created_at->format("H:i"),
            "parent_id" => $this->message->parent_id,
        ];
    }
}
