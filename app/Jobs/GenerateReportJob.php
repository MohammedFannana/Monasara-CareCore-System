<?php

namespace App\Jobs;

use App\Exports\FinancialReportExport;
use App\Exports\GiftReportExport;
use App\Exports\OrphanReportExport;
use App\Exports\SponsorReportExport;
use App\Exports\SponsorshipReportExport;
use App\Models\Gift;
use App\Models\Orphan;
use App\Models\Report;
use App\Models\Sponsor;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $type,
        public string $date,
        public ?string $dateTo = null,
        public ?string $status = null,
        public string $format = 'excel',
        public array $filters = []
    ) {
    }

    public function handle(): void
    {
        $date = Carbon::parse($this->date);
        $dateTo = $this->dateTo ? Carbon::parse($this->dateTo) : $date;
        $folder = $date->format('m-Y');

        switch ($this->type) {
            case 'sponsor':
                $fileName = 'sponsor_report_' . $folder . '.' . $this->format;
                $filePath = 'reports/sponsors/' . $fileName;

                if ($this->format === 'pdf') {
                    $sponsors = Sponsor::query()->select(['id', 'name', 'email', 'phone', 'country', 'address'])->get();
                    $pdf = Pdf::loadView('admins.reports.pdf.sponsor', compact('sponsors', 'date'));
                    Storage::disk('public')->put($filePath, $pdf->output());
                } else {
                    Excel::store(new SponsorReportExport, $filePath, 'public');
                }

                $this->createReportRecord('sponsor', $filePath, $date, $dateTo);
                break;

            case 'sponsorship':
                $fileName = 'sponsorship_report_' . $folder . '.' . $this->format;
                $filePath = 'reports/sponsorships/' . $fileName;

                if ($this->format === 'pdf') {
                    $sponsorships = Sponsorship::query()
                        ->when($this->status && $this->status !== 'all', fn ($query) => $query->where('status', $this->status))
                        ->with(['orphan', 'sponsor'])
                        ->select(['id', 'orphan_id', 'sponsor_id', 'status', 'sponsorship_date', 'duration', 'bail_amount', 'total'])
                        ->get();
                    $pdf = Pdf::loadView('admins.reports.pdf.sponsorship', compact('sponsorships', 'date'));
                    Storage::disk('public')->put($filePath, $pdf->output());
                } else {
                    Excel::store(new SponsorshipReportExport($this->status), $filePath, 'public');
                }

                $this->createReportRecord('sponsorship', $filePath, $date, $dateTo);
                break;

            case 'orphan':
                $fileName = 'orphan_report_' . $folder . '.' . $this->format;
                $filePath = 'reports/orphans/' . $fileName;

                $query = Orphan::query();
                $searchBys = $this->filters['search_by'] ?? [];
                $conditions = $this->filters['condition'] ?? [];
                $values = $this->filters['search_value'] ?? [];

                $isSearch = collect($values)->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty();

                if ($isSearch) {
                    foreach ($searchBys as $index => $field) {
                        $condition = $conditions[$index] ?? '==';
                        $value = $values[$index] ?? null;

                        if ($value !== null && $value !== '' && $condition === '==') {
                            $query->where($field, $value);
                        }
                    }
                }

                $orphans = $query
                    ->with([
                        'profile',
                        'association',
                        'sponsorships' => fn ($q) => $q->latest('sponsorship_date'),
                        // eager-load the active sponsorship and its sponsor to prevent nested N+1 in PDF view
                        'activeSponsorships.sponsor'
                    ])
                    ->get();

                if ($this->format === 'pdf') {
                    $pdf = Pdf::loadView('admins.reports.pdf.orphan', compact('orphans', 'date'));
                    Storage::disk('public')->put($filePath, $pdf->output());
                } else {
                    Excel::store(new OrphanReportExport($orphans), $filePath, 'public');
                }

                $this->createReportRecord('orphan', $filePath, $date, $dateTo);
                break;

            case 'gift':
                $fileName = 'gift_report_' . $folder . '.' . $this->format;
                $filePath = 'reports/gifts/' . $fileName;

                if ($this->format === 'pdf') {
                    $gifts = Gift::query()->with(['orphan', 'sponsor'])->select(['id', 'orphan_id', 'sponsor_id', 'gift_date', 'duration', 'amount', 'total', 'notes'])->get();
                    $pdf = Pdf::loadView('admins.reports.pdf.gift', compact('gifts', 'date'));
                    Storage::disk('public')->put($filePath, $pdf->output());
                } else {
                    Excel::store(new GiftReportExport($this->status), $filePath, 'public');
                }

                $this->createReportRecord('gift', $filePath, $date, $dateTo);
                break;

            case 'financial':
                $fileName = 'financial_report_' . $folder . '.' . $this->format;
                $filePath = 'reports/financials/' . $fileName;

                if ($this->format === 'pdf') {
                    $gifts = Gift::query()->with(['orphan', 'sponsor'])->get()->map(function ($gift) {
                        return [
                            'date' => $gift->gift_date,
                            'owner' => $gift->sponsor?->name,
                            'orphan' => $gift->orphan?->name,
                            'amount' => $gift->amount,
                            'duration' => $gift->duration ?? 1,
                            'total' => $gift->total,
                            'type' => 'هدية',
                        ];
                    });

                    $sponsorships = Sponsorship::query()
                        ->with(['orphan', 'sponsor'])
                        ->whereNull('payment_received')
                        ->whereNotNull('bail_amount')
                        ->get()
                        ->map(function ($sponsorship) {
                            return [
                                'date' => $sponsorship->created_at,
                                'owner' => $sponsorship->sponsor?->name,
                                'orphan' => $sponsorship->orphan?->name,
                                'amount' => $sponsorship->bail_amount,
                                'duration' => $sponsorship->duration,
                                'total' => $sponsorship->total,
                                'type' => 'كفالة',
                            ];
                        });

                    $financial = $gifts->merge($sponsorships);
                    $pdf = Pdf::loadView('admins.reports.pdf.financial', compact('financial', 'date', 'dateTo'));
                    Storage::disk('public')->put($filePath, $pdf->output());
                } else {
                    Excel::store(new FinancialReportExport($this->status), $filePath, 'public');
                }

                $this->createReportRecord('financial', $filePath, $date, $dateTo);
                break;
        }
    }

    protected function createReportRecord(string $type, string $filePath, Carbon $date, Carbon $dateTo): void
    {
        Report::create([
            'type' => $type,
            'report' => $filePath,
            'date' => $date->startOfMonth(),
            'date_to' => $dateTo->startOfMonth(),
        ]);
    }
}
