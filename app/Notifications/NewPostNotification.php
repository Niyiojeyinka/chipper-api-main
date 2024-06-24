<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Post from ' . $this->post->user->name)
                    ->line('Your favored user ' . $this->post->user->name . ' has created a new post.')
                    ->action('View Post', url('/posts/' . $this->post->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'post_body' => $this->post->body,
            'user_id' => $this->post->user->id,
            'user_name' => $this->post->user->name,
        ];
    }
}
