<?php

namespace App\Providers;

use App\Models\Orphan;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\DatabaseNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->usePublicPath(base_cpath('public_html'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

         View::composer(['layouts.app', 'layouts.guest', 'layouts.main'], function ($view) {
            $user = null;
            $unreadSponsorCount = 0;
            $unreadCountNotification = 0;

            if (Auth::guard('sponsor')->check()) {
                $user = Auth::guard('sponsor')->user();
                $unreadSponsorCount = DatabaseNotification::where('notifiable_type', 'App\\Models\\Sponsor')
                    ->where('notifiable_id', $user->id)
                    ->whereNull('read_at')
                    ->where('type', 'App\\Notifications\\OrphanMessage')
                    ->where('status', 'active')
                    ->count();

                $unreadCountNotification = $user->unreadNotifications()
                    ->whereIn('type', ['App\\Notifications\\SponsorshipEndingSoon', 'App\\Notifications\\SponsorshipEnded'])
                    ->count();
            }
            elseif (Auth::guard('orphan')->check()) {
                $user = Auth::guard('orphan')->user();
                $unreadCountNotification = $user->unreadNotifications()
                    ->whereIn('type', ['App\\Notifications\\SponsorshipEndingSoon', 'App\\Notifications\\SponsorshipEnded'])
                    ->count();
            }
            elseif (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                $unreadSponsorCount = DatabaseNotification::where(function ($query) {
                        $query->where('notifiable_type', 'App\\Models\\Sponsor')
                            ->orWhere('notifiable_type', 'App\\Models\\User');
                    })
                    ->where('notifiable_id', $user->id)
                    ->whereNull('read_at')
                    ->where('type', 'App\\Notifications\\OrphanMessage')
                    ->where('status', 'active')
                    ->count();

                $unreadCountNotification = $user->unreadNotifications()
                    ->whereIn('type', ['App\\Notifications\\SponsorshipEndingSoon', 'App\\Notifications\\SponsorshipEnded'])
                    ->count();
            }
            elseif (Auth::guard('association')->check()) {
                $unreadSponsorCount = DatabaseNotification::whereNull('read_at')
                    ->where('type', 'App\\Notifications\\OrphanMessage')
                    ->where('status', 'inactive')
                    ->count();

                $user = Auth::guard('association')->user();
                $unreadCountNotification = $user->unreadNotifications()
                    ->whereIn('type', ['App\\Notifications\\SponsorshipEndingSoon', 'App\\Notifications\\SponsorshipEnded'])
                    ->count();
            }

            $view->with('unreadSponsorCount', $unreadSponsorCount)
                ->with('unreadCountNotification', $unreadCountNotification);
        });

        Gate::define('complete-orphan-data', function ($user, Orphan $orphan) {
            // use optional() to avoid errors when profile is missing; controllers that render lists
            // should eager-load 'profile' to avoid N+1
            return empty($orphan->guardian_name)
                || empty(optional($orphan->profile)->guardian_whats_phone);
        });

        Gate::define('view-reports', function ($user) {
            return $user->role === 'accountant'
                || $user->role === 'admin'
                || $user->role === 'association'
                || $user->role === 'association_staff';
        });

        Gate::define('view-all-reports', function ($user) {
            return $user->role === 'accountant'
                || $user->role === 'admin'
                || $user->role === 'association_staff';
        });

        Gate::define('show-admin', function ($user) {
            return $user->role === 'admin';
        });

        // Gate::define('add-payments', function ($user) {
        //     return in_array($user->role, ['accountant', 'association']);
        // });

        // Gate::define('show-association', function ($user) {
        //     return in_array($user->role, ['association']);
        // });


    }
}
