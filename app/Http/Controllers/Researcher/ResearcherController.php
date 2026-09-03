<?php

namespace App\Http\Controllers\Researcher;

use App\Http\Requests\StoreResearcherOrphanValidatedRequest;
use App\Models\Orphan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResearcherController extends Controller
{
    public function index(Request $request){

        $researcher = auth('researcher')->user();

        $orphans = Orphan::where('association_id', $researcher->association_id)
        ->where(function ($query) {
            $query->where('role', \App\Enums\OrphanRole::CANDIDATE->value)
                  ->orWhere('role', \App\Enums\OrphanRole::AUDITOR->value);
        })
        ->whereNotNull('guardian_name')
        ->whereHas('profile', function ($query) {
            $query->whereNotNull('guardian_whats_phone');
        })
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })
        // eager-load association and profile to avoid N+1 when view accesses association->name and Gate checks profile
        ->with(['association', 'profile'])
        ->paginate(10);
        // $researcher = auth('researcher')->user();
        return view('researchers.index' , compact('orphans'));
    }

    public function registeredOrphan(Request $request)
    {
        $researcher = auth('researcher')->user();
                  // dd($associaton_id);

        $orphans = Orphan::with('profile')
        ->where('association_id', $researcher->association_id)
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

    public function view(Orphan $orphan){

        if(auth('researcher')->check()){
            $researcher = auth('researcher')->user();

            if ($orphan->association_id != $researcher->association_id) {
                abort(403, 'غير مسموح لك بالوصول لهذا اليتيم.');
            }

        }elseif(auth('association')->check()){
            $association_id = auth('association')->user()->id;

            if ($orphan->association_id != $association_id) {
                abort(403, 'غير مسموح لك بالوصول لهذا اليتيم.');
            }
        }

        $orphan = $orphan->load(['profile' , 'sibling']);
        return view('researchers.orphan-view' , compact('orphan'));
    }

    public function create(){
        return view('researchers.create');

    }

    public function store(StoreResearcherOrphanValidatedRequest $request){

        if(Auth::guard('researcher')->check()){

            $association_id = Auth::guard('researcher')->user()->association->id;

            $request->merge([
                'association_id' => $association_id,
            ]);

            $validated = $request->validated();

            $validated['role'] = \App\Enums\OrphanRole::CANDIDATE->value;
            $validated['password'] =  Hash::make($validated['id_number']) ;


            Orphan::create($validated);
            return redirect()->back()->with('success' , 'تم إضافة البيانات بنجاح');

        }else{
            abort(403, 'غير مصرح بالدخول لهذه الصفحة');
        }

    }
}
