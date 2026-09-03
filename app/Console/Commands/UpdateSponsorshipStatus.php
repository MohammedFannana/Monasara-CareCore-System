<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Orphan;
use App\Models\Sponsorship;
use Illuminate\Console\Command;
use App\Notifications\SponsorshipEnded;
use App\Notifications\SponsorshipEndingSoon;

class UpdateSponsorshipStatus extends Command
{
    protected $signature = 'sponsorships:update-status';

    protected $description = 'تحديث حالة الكفالات بناءً على انتهاء المدة أو بلوغ اليتيم 18 سنة';

    protected int $daysLeft = 0;

    public function handle()
    {
        $today = Carbon::today(); // تاريخ اليوم بدون وقت

        $sponsorships = Sponsorship::where('status', 'active')
            ->with(['orphan', 'sponsor', 'orphan.association'])
            ->get();

        foreach ($sponsorships as $sponsorship) {

            $duration = (int) $sponsorship->duration;
            // حساب تاريخ انتهاء الكفالة مع تصفير الوقت
            $endDate = Carbon::parse($sponsorship->sponsorship_date)
                        ->addMonths($duration)
                        ->startOfDay();

            $this->daysLeft = $today->diffInDays($endDate, false);

            $orphan = $sponsorship->orphan;

            // تحقق من عمر اليتيم، إذا بلغ 18 سنة يتم إنهاء الكفالة وتغيير الحالة
            if ($orphan && $orphan->birth_date) {
                $age = Carbon::parse($orphan->birth_date)->age;
                if ($age >= 18) {
                    $sponsorship->update(['status' => 'finished']);
                    $orphan->update(['role' => \App\Enums\OrphanRole::ARCHIVED->value]);
                    $this->info("كافلنا الكريم، لقد بلغ يتيمك المكفول {$orphan->name} سن 18 عاماً، وبذلك تنتهي كفالته حسب قانون الجمعية. نتمنى استمرار عطائكم بكفالة يتيم آخر من قائمة الانتظار في الموقع." );
                    $this->notifyAboutSponsorship($sponsorship, 'ended');
                    continue; // انتقل للكفالة التالية
                }
            }

            // تحقق إذا انتهت الكفالة اليوم
            if ($today->greaterThanOrEqualTo($endDate)) {
                $sponsorship->update(['status' => 'finished']);
                $this->info("كافلنا الكريم، نتمنى منكم دفع كفالة طفلكم المكفول {$orphan->name}، فقد حان وقت دفع الكفالة. جزاكم الله خيراً.");
                $this->notifyAboutSponsorship($sponsorship, 'finish');
            }


            // إشعارات قبل انتهاء الكفالة بـ 30, 14, أو 3 أيام
            elseif (in_array($this->daysLeft, [14, 7, 3, 1])) {
                    $this->info("كافلنا الكريم، متبقٍ لحلول موعد دفع كفالتكم لطفلكم اليتيم {$orphan->name} {$this->daysLeft} يوماً. نتمنى منكم دفع الكفالة، جزاكم الله خيراً.");
                    $this->notifyAboutSponsorship($sponsorship, 'soon');
            }
        }

        // تحديث حالة الأيتام الذين ليس لديهم كفالة نشطة
        // Orphan::with('sponsorships')->each(function ($orphan) {
        //     $hasActive = $orphan->sponsorships()->where('status', 'active')->exists();
        //     if (!$hasActive && $orphan->role !== 'waiting') {
        //         $orphan->update(['role' => 'waiting']);
        //         $this->info("⏳ تم تحديث حالة اليتيم {$orphan->name} إلى انتظار.");
        //     }
        // });

        $this->info('✅ تمت معالجة جميع الكفالات والأيتام بنجاح.');
    }

    protected function notifyAboutSponsorship(Sponsorship $sponsorship, string $type = 'soon'): void
    {
        $message = match ($type) {
            'ended' => "كافلنا الكريم، لقد بلغ يتيمك المكفول {$sponsorship->orphan->name} سن 18 عاماً، وبذلك تنتهي كفالته حسب قانون الجمعية. نتمنى استمرار عطائكم بكفالة يتيم آخر من قائمة الانتظار في الموقع.",
            'finish' => "كافلنا الكريم، نتمنى منكم دفع كفالة طفلكم المكفول {$sponsorship->orphan->name}، فقد حان وقت دفع الكفالة. جزاكم الله خيراً.",
            'soon' => "كافلنا الكريم، متبقٍ لحلول موعد دفع كفالتكم لطفلكم اليتيم {$sponsorship->orphan->name} {$this->daysLeft} يوماً. نتمنى منكم دفع الكفالة، جزاكم الله خيراً.",
        };

        $notification = $type === 'ended' || $type === 'finish'
            ? new SponsorshipEnded($sponsorship, $message)
            : new SponsorshipEndingSoon($sponsorship, $message);


        // إشعار الكافل
        $sponsorship->sponsor?->notify($notification);
        // إشعار الجمعية المرتبطة باليتيم
        $sponsorship->orphan?->association?->notify($notification);

        // إشعار مسؤول النظام (يمكن تعديل ليصل لمدير محدد حسب الحاجة)
        User::first()?->notify($notification);

        // إشعار اليتيم في حالة الانتهاء أو قبل 3 أيام من الانتهاء
        if ($type === 'ended' || $type === 'finish' || $this->daysLeft === 3) {
            $sponsorship->orphan?->notify($notification);
        }
    }
}
