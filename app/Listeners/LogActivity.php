<?php

namespace App\Listeners;

use App\Events\LinkCreated;
use App\Events\LinkUpdated;
use App\Events\LinkDeleted;
use App\Events\LinkShared;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogActivity
{
    public function __construct()
    {
        //
    }

    public function handle(object $event): void
    {
        $userId = auth()->id();
        $linkId = $event->link->id ?? null;
        $action = $event->action;
        $description = $this->getActionDescription($event);

        ActivityLog::create([
            'user_id' => $userId,
            'link_id' => $linkId,
            'action' => $action,
            'description' => $description,
            'details' => json_encode([
                'link_title' => $event->link->title ?? null,
                'link_url' => $event->link->url ?? null,
                'shared_with' => $event->sharedWith->name ?? null,
                'permission' => $event->permission ?? null,
            ]),
        ]);
    }

    private function getActionDescription(object $event): string
    {
        return match ($event::class) {
            LinkCreated::class => "Created link: {$event->link->title}",
            LinkUpdated::class => "Updated link: {$event->link->title}",
            LinkDeleted::class => "Deleted link: {$event->link->title}",
            LinkShared::class => "Shared link '{$event->link->title}' with {$event->sharedWith->name} ({$event->permission})",
            default => 'Unknown action',
        };
    }
}
