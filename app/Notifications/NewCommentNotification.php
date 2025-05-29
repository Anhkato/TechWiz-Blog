<?php

namespace App\Notifications;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewCommentNotification extends Notification
{
    use Queueable;
    public Comment $comment;


    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $post = $this->comment->post;
        $commenter = $this->comment->user;
        $postUrl = route('posts.show', $post);

        return (new MailMessage)
                    ->subject('Thông báo Mới đây : ' . $post->title)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line($commenter->name . ' bạn có 1 comment mới: "' . $post->title . '".')
                    ->line('Comment: "' . Str::limit($this->comment->body, 100) . '"')
                    ->action('xem comment', $postUrl)
                    ->line('Thank you !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $post = $this->comment->post;
        $commenter = $this->comment->user;

        return [
            'comment_id' => $this->comment->id,
            'comment_body' => Str::limit($this->comment->body, 100),
            'commenter_name' => $commenter->name,
            'post_id' => $post->id,
            'post_title' => $post->title,
            'post_slug' => $post->slug,
            'message' => $commenter->name . ' commented on your post: ' . $post->title,
        ];
    }

}
