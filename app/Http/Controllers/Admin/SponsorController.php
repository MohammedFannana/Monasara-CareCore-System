<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSponsorValidatedRequest;
use App\Http\Requests\UpdateSponsorValidatedRequest;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;



class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sponsors = Sponsor::
        when($request->search, function ($builder, $value) { //from search input
            $builder->where('name', 'LIKE', "%{$value}%");
        })->paginate(15);
        return view('admins.sponsors.index' ,compact('sponsors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sponsor = new Sponsor();
        return view('admins.sponsors.create' , compact('sponsor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSponsorValidatedRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        Sponsor::create($validated);
        return redirect()->route('admin.sponsor.index')->with('sucess' , 'تم إضافة الكافل بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sponsor $sponsor)
    {
        return view('admins.sponsors.view' , compact('sponsor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sponsor $sponsor)
    {
        return view('admins.sponsors.edit' ,compact('sponsor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSponsorValidatedRequest $request, Sponsor $sponsor)
    {
        $validated = $request->validated();

        $sponsor->update($validated);
        return redirect()->route('admin.sponsor.edit' , $sponsor->id)->with('success' ,'تم تعديل بيانات الكافل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sponsor $sponsor)
    {
        $sponsor->delete();
        return redirect()->route('admin.sponsor.index')->with('success' , 'تم حذف الكافل بنجاح');
    }
}
