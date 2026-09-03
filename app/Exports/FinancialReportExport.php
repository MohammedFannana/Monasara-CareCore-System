<?php
namespace App\Exports;

use App\Models\Gift;
use App\Models\Sponsorship;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialReportExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {

        $gifts = Gift::with(['orphan', 'sponsor'])
            ->get()
            ->map(function ($gift) {
                return [
                    'date'        => $gift->gift_date,
                    'owner'       => $gift->sponsor?->name ,
                    'orphan'      => $gift->orphan?->name,
                    'amount'      => $gift->amount,
                    'duration'    => $gift->duration ?? 1,
                    'total'       => $gift->total,
                    'type'        => 'هدية',
                ];
            });


        $sponsorships = Sponsorship::with(['orphan', 'sponsor'])
            ->whereNull('payment_received')
            ->whereNotNull('bail_amount')
            ->get()
            ->map(function ($sponsorship) {
                return [
                    'date'        => $sponsorship->created_at,
                    'owner'       => $sponsorship->sponsor?->name,
                    'orphan'      => $sponsorship->orphan?->name,
                    'amount'      => $sponsorship->bail_amount,
                    'duration'    => $sponsorship->duration,
                    'total'       => $sponsorship->total,
                    'type'        => 'كفالة',
                ];
            });


        return $gifts->merge($sponsorships);
    }

    public function headings(): array
    {
        return [
            'التاريخ',
            'اسم صاحب العملية',
            'اسم اليتيم',
            'المبلغ',
            'المدة',
            'إجمالي المبلغ',
            'النوع'
        ];
    }
}
