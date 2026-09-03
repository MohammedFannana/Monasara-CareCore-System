<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreAdValidatedRequest;
use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Ad::paginate(8);
        return view('admins.ads.index' , compact('ads'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdValidatedRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('ad')) {
            $file = $request->file('ad');
            $path = $file->store("images/ads", 'public');
            $validated['ad'] = $path;
        }

        Ad::create($validated);
        return redirect()->route('admin.ad.index')->with('success' , 'تم إضافة الإعلان بنجاح');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        $ad->delete();
        if ($ad->ad && Storage::disk('public')->exists($ad->ad)) {
            Storage::disk('public')->delete($ad->ad);
        }
        return redirect()->route('admin.ad.index')->with('success' , 'تم حذف الإعلان بنجاح');
    }
}
