<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ErpNotification extends Notification
{
    use Queueable;
    private $notify;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notify)
    {
        $this->notify = $notify;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $multi_params = $this->notify['multi_params'] ?? false;
        $multi_params_url = $this->notify['url'] . '&id=' . $this->notify['id'];
        $single_params_url = $this->notify['url'] . '?id=' . $this->notify['id'];
        return [
            'id' => $this->notify['id'],
            'type' => $this->notify['type'],
            'user' => $this->notify['user'],
            'model' => $this->notify['model'],
            'message' => $this->notify['message'],
            'url' => $multi_params ? $multi_params_url : $single_params_url,
        ];
    }
}
