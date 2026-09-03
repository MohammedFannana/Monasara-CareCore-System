<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrphanMediaValidatedRequest;
use App\Models\Orphan;
use App\Models\OrphanMedia;
use Illuminate\Http\Request;

class OrphanMediaController extends Controller
{
    public function index(Request $request)
    {

        $orphans = Orphan::where('association_id', auth('researcher')->user()->association_id)->get();
        return view('researchers.media', compact('orphans'));

    }


    public function store(StoreOrphanMediaValidatedRequest $request)
    {

        $orphan = Orphan::findOrFail($request->orphan_id);
        // تحقق أن الباحث من نفس الجمعية
        if ($orphan->association_id !== auth('researcher')->user()->association_id) {
            abort(403, 'غير مصرح لك');
        }

        // تحقق أن اليتيم مكفول من نفس الجمعية
        if (!$orphan->sponsorships()->where('status', 'active')->exists()) {
            abort(403, 'اليتيم غير مكفول');
        }

        $request->validated();

        $file = $request->file('media');
        $type = str_starts_with($file->getMimeType(), 'image') ? 'image' : 'video';

        $path = $file->store('orphans/media', 'public');

        OrphanMedia::create([
            'orphan_id'      => $orphan->id,
            'association_id' => auth()->user()->association_id,
            'researcher_id'  => auth()->id(),
            'type'           => $type,
            'file_path'      => $path,
            'note'           => $request->note,
        ]);

        return back()->with('success', 'تم رفع الملف بنجاح');
    }

}
