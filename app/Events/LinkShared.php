<?php

namespace App\Events;

use App\Models\Link;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkShared
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Link $link,
        public User $sharedWith,
        public string $permission,
        public string $action = 'shared'
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
