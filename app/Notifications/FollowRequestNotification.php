<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class FollowRequestNotification extends Notification
{
    use Queueable;

    public $fromUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $fromUser)
    {
        $this->fromUser = $fromUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'from_user_avatar' => $this->fromUser->avatar_url ?? null,
            'message' => e($this->fromUser->name) . 'さんからフォロー申請が届きました',
            'profile_url' => route('profile.show', $this->fromUser),
        ];
    }
}
