<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\ContactMessageValidatedRequest;
use App\Models\Ad;
use App\Models\User;
use App\Models\Orphan;
use App\Models\Sponsor;
use App\Models\Question;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ContactMailMessageNotification;
// use Symfony\Component\Mime\Part\Text\HtmlPart;


class FrontController extends Controller
{
    public function index(){
        $questions = Cache::remember('landing.questions', now()->addMinutes(5), function () {
            return Question::query()
                ->select(['id', 'question', 'answer'])
                ->limit(5)
                ->get();
        });

        $ads = Cache::remember('landing.ads', now()->addMinutes(5), function () {
            return Ad::query()
                ->select(['id', 'ad'])
                ->get();
        });

        $statistics = Cache::remember('landing.statistics', now()->addMinutes(5), function () {
            return [
                'orphansCount' => Orphan::count(),
                'orphanSponsorCount' => Orphan::sponsored()->count(),
                'sponsorsCount' => Sponsor::count(),
                'sponsorshipsCount' => Sponsorship::count(),
            ];
        });

        return view('index', array_merge(
            compact('questions', 'ads'),
            $statistics
        ));
    }

    public function showOrphanToSponsored(){
        $orphans = Orphan::waiting()->with('latestSponsorship')->paginate(8);
        return view('front.show-orphan-to-sponsorship' , compact('orphans'));
    }

    public function send(ContactMessageValidatedRequest $request){

        $validated = $request->validated();


        $admin = User::first(); // أو role=admin
        $admin->notify(new ContactMailMessageNotification($validated));

        return back()->with('success', 'تم إرسال رسالتك بنجاح!');
    }

    public function aboutUs(){
        return view('front.about_us');
    }
}
