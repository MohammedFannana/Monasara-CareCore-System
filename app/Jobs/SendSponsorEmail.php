<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

// app/Jobs/SendSponsorEmail.php
class SendSponsorEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $email,
        public string $subject,
        public string $message
    ) {}

    public function handle(): void
    {
        Mail::raw($this->message, function ($mail) {
            $mail->to($this->email)
                ->subject($this->subject)
                ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }
}
