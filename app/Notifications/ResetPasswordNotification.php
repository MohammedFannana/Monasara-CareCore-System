<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;
    public $guard;

    public function __construct($token, $guard)
    {
        $this->token = $token;
        $this->guard = $guard;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
            'guard' => $this->guard, // ← هنا نمرر guard
        ], false));

        return (new MailMessage)
            ->subject('استعادة كلمة المرور')
            ->greeting('أهلاً بك!')
            ->line('لقد استلمت هذا الإيميل لأننا استقبلنا طلبًا لاستعادة كلمة مرور حسابك.')
            ->action('استعادة كلمة المرور', $url)
            ->line('ستنتهي صلاحية الرابط خلال 60 دقيقة.')
            ->line('إذا لم تطلب استعادة كلمة المرور، لا حاجة لأي إجراء.');
    }

}
