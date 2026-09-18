<?php

namespace App\Exports;

use App\Models\Sponsor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SponsorReportExport implements FromCollection, WithHeadings
{

    public function __construct(protected ?int $associationId = null)
    {
    }

    public function collection()
    {
        return Sponsor::when($this->associationId, function ($query) {
            $query->where(function ($query) {
                $query->whereHas('sponsorships.orphan', fn ($query) => $query->where('association_id', $this->associationId))
                    ->orWhereHas('gifts.orphan', fn ($query) => $query->where('association_id', $this->associationId));
            });
        })->select('name' , 'email' , 'phone' ,'country' ,'address')->get();
    }

    public function headings(): array
    {
        return ['اسم الكافل', 'البريد الالكتروني' , 'رقم الهاتف' , 'الدولة' , 'العنوان'];
    }
}
