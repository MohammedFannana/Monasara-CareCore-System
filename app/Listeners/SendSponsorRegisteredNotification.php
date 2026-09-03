<?php

namespace App\Listeners;

use App\Events\SponsorRegistered;
use App\Models\User;
use App\Notifications\NewSponsorNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSponsorRegisteredNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SponsorRegistered $event): void
    {
        $admins = User::where('role', 'admin')->get(); // حسب نظامك

        foreach ($admins as $admin) {
            $admin->notify(new NewSponsorNotification($event->sponsor));
        }
    }
}
