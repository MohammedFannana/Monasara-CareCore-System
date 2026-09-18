<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssociationValidatedRequest;
use App\Http\Requests\UpdateAssociationValidatedRequest;
use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AssociationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $associations = Association::
        when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->paginate(6);
        return view('admins.associations.index' ,compact('associations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $association = New Association();
        return view('admins.associations.create' ,compact('association'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssociationValidatedRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'association';
        $validated['parent_association_id'] = null;
        Association::create($validated);
        return redirect()->route('admin.association.index')->with('success' , 'تم إضافة الجمعية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Association $association)
    {
        // dd($association);
        return view('admins.associations.view' , compact('association'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Association $association)
    {
        return view('admins.associations.edit' , compact('association'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssociationValidatedRequest $request, Association $association)
    {

        $validated = $request->validated();

        $association->update($validated);
        return redirect()->route('admin.association.index')->with('success' , 'تم تعديل بيانات الجمعية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Association $association)
    {

        $association->delete();
        return redirect()->route('admin.association.index')->with('success' , 'تم حذف الجمعية بنجاح');

    }
}
