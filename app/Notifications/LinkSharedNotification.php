<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkSharedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Link $link,
        public User $sharedBy,
        public string $permission
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🔗 Link Shared: {$this->link->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("{$this->sharedBy->name} has shared a link with you:")
            ->line("**Title:** {$this->link->title}")
            ->line("**URL:** {$this->link->url}")
            ->line("**Permission:** " . ucfirst($this->permission))
            ->line("**Category:** {$this->link->category->name}")
            ->action('View Link', route('links.show', $this->link))
            ->line('You can access this link from your dashboard.')
            ->line('Thank you for using Odin 2!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'link_id' => $this->link->id,
            'link_title' => $this->link->title,
            'shared_by' => $this->sharedBy->name,
            'permission' => $this->permission,
            'message' => "{$this->sharedBy->name} shared '{$this->link->title}' with you ({$this->permission} permission)",
        ];
    }
}
