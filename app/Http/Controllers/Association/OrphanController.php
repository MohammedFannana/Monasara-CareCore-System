<?php

namespace App\Http\Controllers\Association;

use App\Http\Controllers\Controller;
use App\Models\Orphan;
use Illuminate\Http\Request;

class OrphanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function registeredOrphan(Request $request)
    {
        $association_id =auth('association')->user()->id;
                  // dd($associaton_id);

        $orphans = Orphan::with('profile')
        ->where('association_id' , $association_id)
        ->candidate()
        ->whereNull('guardian_name')
        ->where(function ($query) {
        $query->whereDoesntHave('profile') // لا يملك profile
              ->orWhereHas('profile', function ($q) {
                  $q->whereNull('guardian_whats_phone'); // يملك profile لكن الرقم null
              });
        })
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->paginate(10);


        return view('associations.orphans.register-orphan' , compact('orphans'));
    }

    public function candidateOrphan(Request $request)
    {
        $association_id =auth('association')->user()->id;

        $orphans = Orphan::with('profile')->where('association_id' , $association_id)
        ->candidate()
        ->whereNotNull('guardian_name')
        ->whereHas('profile', function ($query) {
            $query->whereNotNull('guardian_whats_phone');
        })
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->paginate(10);


        return view('associations.orphans.candidate-orphan' , compact('orphans'));
    }


    public function auditorOrphan(Request $request)
    {
        $association_id =auth('association')->user()->id;
                  // dd($associaton_id);

        $orphans = Orphan::where('association_id' , $association_id)
        ->auditor()
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->paginate(10);


        return view('associations.orphans.auditor-orphan' , compact('orphans'));
    }


    public function certifiedOrphan(Request $request)
    {
        $association_id =auth('association')->user()->id;
                  // dd($associaton_id);

        $orphans = Orphan::where('association_id' , $association_id)
        ->certified()
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->paginate(10);


        return view('associations.orphans.certified-orphan' , compact('orphans'));
    }


    public function waitingOrphan(Request $request)
    {
        $association_id =auth('association')->user()->id;
                  // dd($associaton_id);

        $orphans = Orphan::where('association_id' , $association_id)
        ->waiting()
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->paginate(10);


        return view('associations.orphans.waiting-orphan' , compact('orphans'));
    }


    public function sponsoredOrphan(Request $request)
    {
        $association_id =auth('association')->user()->id;
                  // dd($associaton_id);

        $orphans = Orphan::where('association_id', $association_id)
            ->sponsored()
            ->when($request->search, function ($builder, $value) { //from search input
                $builder->where('name', 'LIKE', "%{$value}%");
            })
            ->with(['sponsorships', 'activeSponsorships.sponsor'])
            ->paginate(15);


        return view('associations.orphans.sponsored-orphan' , compact('orphans'));
    }

    public function archivedOrphan(Request $request)
    {
        $associationId = auth('association')->user()->id;

        $orphans = Orphan::where('association_id', $associationId)
            ->archived()
            ->when($request->search, function ($builder, $value) {
                $builder->where('name', 'LIKE', "%{$value}%");
            })
            ->paginate(15);

        return view('associations.orphans.archived-orphan', compact('orphans'));
    }


    public function SponsorshipView(Orphan $orphan){

        $association = auth('association')->user();

        if ($orphan->association_id  != $association->id) {
            abort(403, 'غير مسموح لك بالوصول لهذا الصفحة');
        }

        $orphan = $orphan->load([
            'sponsorships' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
        ]);

        return view('associations.orphans.sponsorship-view' ,compact('orphan'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('associations.orphans.create');
    }


    /**
     * Display the specified resource.
     */
    public function show(Orphan $orphan)
    {
        $association = auth('association')->user();

        if ($orphan->association_id != $association->id) {
            abort(403, 'غير مسموح لك بالوصول لهذا الصفحة');
        }

        $orphan = $orphan->load(['profile', 'firstReview' , 'sibling']);

        return view('associations.orphans.view-candidate' ,compact('orphan'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
