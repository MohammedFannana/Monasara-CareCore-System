<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function OrphanNotification(){
        $user = auth('orphan')->user();
        $notifications = $user->notifications()
            ->whereIn('type', [
                'App\\Notifications\\SponsorshipEndingSoon',
                'App\\Notifications\\SponsorshipEnded',
            ])
            ->where('created_at', '>=', now()->subDays(8))
            ->latest()
            ->get();

        $this->makeReadNotification($user);
        return view('notification', compact('notifications'));
    }

    public function SponsorNotification(){
        $user = auth('sponsor')->user();
        $notifications = $user->notifications()
            ->whereIn('type', [
                'App\\Notifications\\SponsorshipEndingSoon',
                'App\\Notifications\\SponsorshipEnded',
            ])
            ->where('created_at', '>=', now()->subDays(8))
            ->latest()
            ->get();

        $this->makeReadNotification($user);
        return view('notification', compact('notifications'));
    }

    public function AdminNotification(){
        $user = auth('web')->user();
        $notifications = $user->notifications()
            ->whereIn('type', [
                'App\\Notifications\\SponsorshipEndingSoon',
                'App\\Notifications\\SponsorshipEnded',
                'App\\Notifications\\NewSponsorNotification',
            ])
            ->where('created_at', '>=', now()->subDays(8))
            ->latest()
            ->get();

        $this->makeReadNotification($user);
        return view('notification', compact('notifications'));
    }

    public function AssociationNotification(){
        $user = auth('association')->user();
        $notifications = $user->notifications()
            ->whereIn('type', [
                'App\\Notifications\\SponsorshipEndingSoon',
                'App\\Notifications\\SponsorshipEnded',
            ])
            ->where('created_at', '>=', now()->subDays(8))
            ->latest()
            ->get();

        $this->makeReadNotification($user);
        return view('notification', compact('notifications'));
    }

    protected function makeReadNotification($user){
        if (! $user) {
            return;
        }

        $user->unreadNotifications()->update(['read_at' => now()]);
        return;
    }
}
