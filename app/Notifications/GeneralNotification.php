<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->data['title'] ?? 'Notifikasi PringKost',
            'message' => $this->data['message'] ?? '',
            'icon' => $this->data['icon'] ?? 'fa-bell',
            'type' => $this->data['type'] ?? 'info', // success, warning, danger, info
            'url' => $this->data['url'] ?? null,
        ];
    }
}
