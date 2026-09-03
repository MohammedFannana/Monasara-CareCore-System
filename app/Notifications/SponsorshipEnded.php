<?php

namespace App\Notifications;

use App\Models\Sponsorship;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SponsorshipEnded extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $sponsorship;
    protected $message;

    public function __construct(Sponsorship $sponsorship, string $message)
    {
        $this->sponsorship = $sponsorship;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database' , 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'تذكير بدفع الكفالة :',
            'message' => $this->message,
            'sponsorship_id' => $this->sponsorship->id,
            'orphan_id' => $this->sponsorship->orphan->id,
            'status' => 'active',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تذكير بدفع الكفالة')
            ->line($this->message)
            ->line('رقم الكفالة: ' . $this->sponsorship->id)
            ->line('اسم اليتيم: ' . $this->sponsorship->orphan->name)
            ->action(
                'عرض تفاصيل الكفالة',
                url('/sponsor/sponsorships/' . $this->sponsorship->id)
            )
            ->salutation('مع الشكر، ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
