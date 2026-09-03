<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Association;
use App\Models\Orphan;
use Illuminate\Http\Request;

class OrphanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orphans = Orphan::whereIn('role', [\App\Enums\OrphanRole::SPONSORED->value, \App\Enums\OrphanRole::WAITING->value, \App\Enums\OrphanRole::CERTIFIED->value])
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->with(['association', 'latestSponsorship'])->paginate(15);

        return view('admins.orphans.index' , compact('orphans'));
    }

     public function CertifiedOrphan(Request $request){

        $orphans = Orphan::certified()->
        when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->with(['association', 'latestSponsorship'])->paginate(6);

        return view('admins.orphans.certified-index' , compact('orphans'));

    }

    public function SponsoredOrphan(Request $request){

        $orphans = Orphan::sponsored()->
        when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->with(['association', 'latestSponsorship'])->paginate(6);

        return view('admins.orphans.sponsored-index' , compact('orphans'));
    }

    public function ArchivedOrphan(Request $request)
    {
        $orphans = Orphan::archived()
            ->when($request->search, function ($builder, $value) {
                $builder->where('name', 'LIKE', "%{$value}%");
            })
            ->with('association')
            ->paginate(15);

        return view('admins.orphans.archived-index', compact('orphans'));
    }

    public function UnsponsoredOrphan(Request $request){

        $orphans = Orphan::waiting()->
        when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->with(['association', 'latestSponsorship'])->paginate(6);

        return view('admins.orphans.unsponsored-index' , compact('orphans'));

    }

    public function generateWaiting(Request $request){

        $orphanIds = explode(',', $request->orphan_ids);

        $orphans = Orphan::whereIn('id', $orphanIds)->get();


        if ($orphans->isEmpty()) {
            return back()->with('danger', 'لا يوجد أيتام متاحين');
        }

        // تحديث جماعي بدلاً من foreach إن أمكن
        Orphan::whereIn('id', $orphanIds)->update(['role' => \App\Enums\OrphanRole::WAITING->value]);

        return redirect()->route('admin.orphan.UnsponsoredOrphan')->with('success', 'تم تحديث حالة الأيتام بنجاح');

    }

    public function sponsorshipDetails(Orphan $orphan){
        $sponsorships = $orphan->sponsorships()->latest()->paginate(6);
        return view('admins.orphans.sponsorship-details' ,compact('sponsorships'));
    }

    public function orphanTransfer(Orphan $orphan){
        $expenses = $orphan->expenses()->where('status' , 'active')->latest()->paginate(6);
        // to sum all expenses not only expenses show in paginate
        // $expenseAmount = $orphan->expenses()->sum('bail_amount');
        //  $expenseAmount = $orphan->expenses()->where('status' , 'active')->sum(function ($expense) {
        //     return $expense->duration * $expense->bail_amount;
        // });

        $allExpenses = $orphan->expenses()->where('status', 'active')->get();

        $expenseAmount = $allExpenses->sum(function ($expense) {
            return $expense->duration * $expense->bail_amount;
        });
        return view('admins.orphans.transfers_view' , compact(['expenses' ,'expenseAmount']));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orphan = New Orphan();
        $associations = Association::pluck('name', 'id')->toArray();;
        return view('admins.orphans.create' ,compact('orphan' , 'associations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //us ethe store in OrphanController
    }

    /**
     * Display the specified resource.
     */
    public function show(Orphan $orphan)
    {

        $orphan = $orphan->load(['profile' , 'sibling']);
        return view('admins.orphans.view' , compact('orphan'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orphan $orphan)
    {
        $orphan = $orphan->load('profile');
        $associations = Association::pluck('name', 'id')->toArray();;
        return view('admins.orphans.edit' , compact('orphan' , 'associations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orphan $orphan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orphan $orphan)
    {
        $orphan->delete();
        return redirect()->back()->with('success' , 'تم حذف اليتيم بنجاح');

    }
}
