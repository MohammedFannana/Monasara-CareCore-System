<?php

namespace App\Http\Controllers\Sponsor;

use Exception;
use Carbon\Carbon;
use App\Http\Requests\StoreSponsorshipValidatedRequest;
use App\Models\Orphan;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class SponserController extends Controller
{

    public function waitingIndex(Request $request){
        $orphans = Orphan::waiting()
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })
        // eager-load latestSponsorship to avoid loading full sponsorship collections per orphan
        ->with('latestSponsorship')
        ->paginate(10);


        return view('Sponsers.waiting-index' , compact('orphans'));
    }

    public function waitingView(Orphan $orphan){

        if (! $orphan->isWaiting()) {
            abort(403, 'غير مسموح لك بالوصول لهذا اليتيم');
        }

        return view('Sponsers.waiting-view' , compact('orphan'));

    }




    public function create(Request $request)
    {
        $orphanIds = $request->input('orphans_ids', []);

        if (is_string($orphanIds)) {
            $orphanIds = explode(',', $orphanIds);
        }

        $orphans = Orphan::whereIn('id', (array)$orphanIds)->get();
        $orphans_count = $orphans->count();

        return view('Sponsers.create-sponsership', compact('orphans' , 'orphans_count'));

    }





    public function store(StoreSponsorshipValidatedRequest $request){
        $ids = $request->orphan_ids;
        $sponsorId = auth('sponsor')->id();
        $validated = $request->validated();

        foreach($ids as $orphanId){


            $orphan = Orphan::findOrFail($orphanId);

            // تحديد تاريخ بداية الكفالة بناءً على آخر كفالة نشطة
            // $lastActive = Sponsorship::where('orphan_id', $orphanId)
            //                 ->where('status','active')
            //                 ->orderByDesc('sponsorship_date')
            //                 ->first();
            // $startDate = $lastActive ? Carbon::parse($lastActive->sponsorship_date)->addMonths($lastActive->duration) : now();

            // جلب آخر كفالة لليتيم بناءً على تاريخ الكفالة الأحدث، بغض النظر عن حالتها
            $lastSponsorship = Sponsorship::where('orphan_id', $orphanId)
                                ->orderByDesc('sponsorship_date')
                                ->first();

            // تحديد تاريخ البداية:
            // إذا وجدت كفالة سابقة: تاريخ البداية = (تاريخ تلك الكفالة + مدتها)
            // إذا لم توجد كفالة سابقة: تاريخ البداية = الآن
            $startDate = $lastSponsorship
                ? Carbon::parse($lastSponsorship->sponsorship_date)->addMonths((int)$lastSponsorship->duration)
                : now();


            $data = [
                'orphan_id' => $orphanId,
                'sponsor_id' => $sponsorId,
                'duration' => $validated['duration'],
                'bail_amount' => $validated['bail_amount'],
                'sponsorship_date' => $startDate->format('Y-m-d'),
                'status' => 'active',
                'total' => $validated['duration'] * $validated['bail_amount'],
                'currency' => "دينار بحريني",
            ];

            if($request->hasFile('payment_received')){
                $file = $request->file('payment_received');
                $path = $file->store("images/orphans/{$orphan->name}", 'public');
                $data['payment_received'] = $path;
            }

            DB::beginTransaction();
            try {

                if ($validated['type'] === 'sponsorship') {

                    Sponsorship::create($data);
                    $orphan->update(['role' => \App\Enums\OrphanRole::SPONSORED->value]);

                } elseif ($validated['type'] === 'gift') {

                    Gift::create([
                        'orphan_id' => $orphanId,
                        'sponsor_id' => $sponsorId,
                        'amount' => $validated['bail_amount'],
                        'currency' => "دينار بحريني",
                        'gift_date' => now(),
                        'payment_received' => $data['payment_received'] ?? null,
                        'notes' => $validated['notes'] ?? null,
                    ]);

                }

                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('danger','فشل في تسجيل العملية. يرجى المحاولة مرة أخرى.');
            }

        }

        return redirect()->back()->with('success','تم كفالة الأيتام المحددين بنجاح');
    }


    // public function sponsorIndex(Request $request){

    //     $sponsorId = auth('sponsor')->id();

    //     $orphans = Orphan::where('role' , 'sponsored')
    //     ->whereHas('sponsorships', function ($query) use ($sponsorId) {
    //         $query->where('sponsor_id', $sponsorId);
    //     })
    //     ->when($request->search, function ($builder, $value) { //from search input
    //         $builder->where('name', 'LIKE', "%{$value}%");
    //     })
    //     ->with('activeSponsorships')
    //     ->paginate(10);

    //     $allOrphanIds = Orphan::where('role' , 'sponsored')
    //     ->whereHas('sponsorships', function ($query) use ($sponsorId) {
    //         $query->where('sponsor_id', $sponsorId);
    //     })
    //     ->pluck('id');


    //     return view('Sponsers.sponser-index' , compact('orphans' , 'allOrphanIds'));
    // }

    public function sponsorIndex(Request $request) {
        $sponsorId = auth('sponsor')->id();

        $orphans = Orphan::sponsored()
            ->whereHas('sponsorships', function ($query) use ($sponsorId) {
                $query->where('sponsor_id', $sponsorId);
            })
            ->when($request->search, function ($builder, $value) {
                $builder->where('name', 'LIKE', "%{$value}%");
            })
            // eager-load latestSponsorship to avoid loading full sponsorship collections per orphan
            ->with('latestSponsorship')
            ->paginate(10);

        // ملاحظة: يفضل إضافة البحث هنا أيضاً لكي تتطابق الـ IDs مع النتائج المعروضة
        $allOrphanIds = Orphan::sponsored()
            ->whereHas('sponsorships', function ($query) use ($sponsorId) {
                $query->where('sponsor_id', $sponsorId);
            })
            ->when($request->search, function ($builder, $value) {
                $builder->where('name', 'LIKE', "%{$value}%");
            })
            ->pluck('id');

        return view('Sponsers.sponser-index', compact('orphans', 'allOrphanIds'));
    }

    public function sponsorView(Orphan $orphan){
        $sponsor = auth('sponsor')->user();

        $orphan->loadMissing('activeSponsorships.sponsor');
        $activeSponsorship = $orphan->sponsorships()
            ->where('sponsor_id', $sponsor->id)
            ->where('status', 'active')
            ->latest('sponsorship_date')
            ->first();

        if (! $activeSponsorship) {
            abort(403, 'غير مسموح لك بالوصول لهذا اليتيم');
        }

        return view('Sponsers.sponsor-view', compact('orphan'));
    }


    public function sponsorshipView(Orphan $orphan){

        // $sponsor = auth('sponsor')->user();

        // if (!$orphan->activeSponsorships || $orphan->activeSponsorships->sponsor_id != $sponsor->id) {
        //     abort(403, 'غير مسموح لك بالوصول لهذا اليتيم');
        // }

        $sponsorships = $orphan->sponsorships()->latest()->paginate(15);
        return view('Sponsers.sponsorship-view' ,compact('sponsorships'));

    }


    public function giftView(Orphan $orphan){

        $sponsor = auth('sponsor')->user();

        if (
            !$orphan->gifts ||
            !$orphan->gifts()->where('sponsor_id', $sponsor->id)->exists()
        ) {
            abort(403, 'غير مسموح لك بالوصول لهذا اليتيم');
        }


        $gifts = $orphan->gifts()->latest()->paginate(8);
        return view('Sponsers.gift-view' ,compact('gifts'));

    }

    function media(Orphan $orphan){

        $medias = $orphan->media()->latest()->get();

        return view('Sponsers.media', compact('medias'));
    }

    public function orphanPayments(Orphan $orphan){

        // $sponsor = auth('sponsor')->user();

        // if (!$orphan->activeSponsorships || $orphan->activeSponsorships->sponsor_id != $sponsor->id) {
        //     abort(403, 'غير مسموح لك بالوصول لهذا اليتيم');
        // }

        $expenses = $orphan->expenses()->where('status' , 'active')->latest()->paginate(8);
        // $expenseAmount = $orphan->expenses()->sum('bail_amount');

        $allExpenses = $orphan->expenses()->where('status', 'active')->get();

        $expenseAmount = $allExpenses->sum(function ($expense) {
            return $expense->duration * $expense->bail_amount;
        });


        return view('Sponsers.orphan-payments-view' ,compact('expenses'  , 'expenseAmount'));

    }

}
























