<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SponsorshipDeliveryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'orphan' => ['nullable', 'string'],
            'sponsor' => ['nullable', 'string'],
            'from' => ['nullable', 'date_format:Y-m'],
            'to' => ['nullable', 'date_format:Y-m'],
            'delivery' => ['nullable', 'in:done,not done'],
        ]);

        $from = $request->input('from');
        $to = $request->input('to');

        $sponsorships = Sponsorship::query()
            ->with(['orphan.profile', 'sponsor'])
            ->when($request->filled('orphan'), fn ($query) => $query->whereHas(
                'orphan',
                fn ($orphan) => $orphan->where('name', 'like', '%' . $request->input('orphan') . '%')
            ))
            ->when($request->filled('sponsor'), fn ($query) => $query->whereHas(
                'sponsor',
                fn ($sponsor) => $sponsor->where('name', 'like', '%' . $request->input('sponsor') . '%')
            ))
            ->when($request->filled('delivery'), fn ($query) => $query->where('sponsorship_delivery', $request->input('delivery')))
            ->when($from, fn ($query) => $query->where('created_at', '>=', Carbon::createFromFormat('Y-m', $from)->startOfMonth()))
            ->when($to, fn ($query) => $query->where('created_at', '<=', Carbon::createFromFormat('Y-m', $to)->endOfMonth()))
            ->latest('created_at')
            ->get();

        return view('admins.sponsorship-delivery', compact('sponsorships', 'from', 'to'));
    }
}
