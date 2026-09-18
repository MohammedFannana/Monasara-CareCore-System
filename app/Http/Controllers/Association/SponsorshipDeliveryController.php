<?php

namespace App\Http\Controllers\Association;

use App\Http\Controllers\Controller;
use App\Imports\SponsorshipDeliveryImport;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SponsorshipDeliveryController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m'],
            'to' => ['nullable', 'date_format:Y-m'],
            'delivery' => ['nullable', 'in:done,not done'],
        ]);

        $associationId = auth('association')->id();
        $from = $request->input('from');
        $to = $request->input('to');

        $sponsorships = Sponsorship::query()
            ->with(['orphan.profile', 'sponsor'])
            ->whereHas('orphan', function ($query) use ($associationId, $request) {
                $query->where('association_id', $associationId)
                    ->when($request->filled('orphan'), fn ($query) => $query->where('name', 'like', '%' . $request->input('orphan') . '%'));
            })
            ->when($request->filled('sponsor'), fn ($query) => $query->whereHas('sponsor', fn ($sponsor) => $sponsor->where('name', 'like', '%' . $request->input('sponsor') . '%')))
            ->when($request->filled('delivery'), fn ($query) => $query->where('sponsorship_delivery', $request->input('delivery')))
            ->when($from, fn ($query) => $query->where('created_at', '>=', Carbon::createFromFormat('Y-m', $from)->startOfMonth()))
            ->when($to, fn ($query) => $query->where('created_at', '<=', Carbon::createFromFormat('Y-m', $to)->endOfMonth()))
            ->orderBy('orphan_id')
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('associations.sponsorship-delivery', compact('sponsorships', 'from', 'to'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ], [
            'file.required' => 'يرجى اختيار ملف الإكسل.',
            'file.mimes' => 'يجب أن يكون الملف بصيغة Excel أو CSV.',
            'file.max' => 'حجم الملف يجب ألا يتجاوز 10 ميغابايت.',
        ]);

        $import = new SponsorshipDeliveryImport(auth('association')->id());
        Excel::import($import, $request->file('file'));

        return redirect()->route('association.sponsorship-delivery.create')->with([
            'success' => "تم تحويل {$import->updated} كفالة إلى حالة تم التسليم.",
            'delivery_import' => [
                'updated' => $import->updated,
                'already_done' => $import->alreadyDone,
                'unmatched' => $import->unmatched,
                'ambiguous' => $import->ambiguous,
            ],
        ]);
    }

    public function markAsDelivered(Request $request)
    {
        $validated = $request->validate([
            'sponsorship_ids' => ['required', 'array', 'min:1'],
            'sponsorship_ids.*' => ['integer', 'exists:sponsorships,id'],
        ], [
            'sponsorship_ids.required' => 'يرجى اختيار كفالة واحدة على الأقل.',
        ]);

        $updated = Sponsorship::query()
            ->whereIn('id', $validated['sponsorship_ids'])
            ->where('sponsorship_delivery', '!=', 'done')
            ->whereHas('orphan', fn ($query) => $query->where('association_id', auth('association')->id()))
            ->update(['sponsorship_delivery' => 'done']);

        return redirect()->back()->with('success', "تم تحويل {$updated} كفالة إلى مدفوع.");
    }
}