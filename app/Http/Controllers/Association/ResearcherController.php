<?php

namespace App\Http\Controllers\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResearcherValidatedRequest;
use App\Http\Requests\UpdateResearcherValidatedRequest;
use App\Models\Researcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResearcherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $association = Auth::guard('association')->user()->id;
        $researchers = Researcher::where('association_id' , $association)
        ->when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->paginate(15);
        return view('associations.researchers.index' ,compact('researchers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $researcher = new Researcher();
        return view('associations.researchers.create' ,compact('researcher'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResearcherValidatedRequest $request)
    {
        $request->merge([
            'association_id' => auth('association')->user()->id,
        ]);

        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        Researcher::create($validated);
        return redirect()->route('association.researcher.index')->with('success' , ' تم إضافة الباحث بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(Researcher $researcher)
    {
        $associationId = auth('association')->id();

        if ($researcher->association_id != $associationId) {
            abort(403, 'غير مسموح لك بتعديل هذا الباحث.');
        }
        return view('associations.researchers.view' ,compact('researcher'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Researcher $researcher)
    {
        $associationId = auth('association')->id();

        if ($researcher->association_id != $associationId) {
            abort(403, 'غير مسموح لك بتعديل هذا الباحث.');
        }
        return view('associations.researchers.edit' , compact('researcher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResearcherValidatedRequest $request, Researcher $researcher)
    {
        $associationId = auth('association')->id();

        if ($researcher->association_id != $associationId) {
            abort(403, 'غير مسموح لك بتعديل هذا الباحث.');
        }

        $validated = $request->validated();

        $researcher->update($validated);
        return redirect()->back()->with('success' , 'تم تحديث بيانات الباحث بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Researcher $researcher)
    {
        $associationId = auth('association')->id();

        if ($researcher->association_id != $associationId) {
            abort(403, 'غير مسموح لك بتعديل هذا الباحث.');
        }

        $researcher->delete();
        return redirect()->route('association.researcher.index')->with('success' , 'تم حذف الباحث بنجاح');
    }
}
