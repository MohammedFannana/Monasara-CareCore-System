<?php

namespace App\Imports;

use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SponsorshipDeliveryImport implements ToCollection, WithHeadingRow
{
    public int $updated = 0;
    public int $alreadyDone = 0;
    public int $unmatched = 0;
    public int $ambiguous = 0;

    public function __construct(private readonly int $associationId)
    {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $orphanIdNumber = $this->value($row, [
                'رقم هوية اليتيم', 'هوية اليتيم', 'orphan_id_number', 'rkm_hoy_alytym',
            ]);
            $guardianIdNumber = $this->value($row, [
                'رقم هوية الوصي', 'هوية الوصي', 'guardian_id_number', 'rkm_hoy_alwsy',
            ]);
            $sponsorshipDate = $this->date($this->value($row, [
                'تاريخ بدء الكفالة', 'تاريخ بداية الكفالة', 'sponsorship_date',
            ]));
            $paymentDate = $this->date($this->value($row, [
                'تاريخ دفع الكفالة', 'تاريخ الدفع', 'payment_date', 'created_at',
            ]));

            if (!$orphanIdNumber || !$guardianIdNumber || !$sponsorshipDate || !$paymentDate) {
                $this->unmatched++;
                continue;
            }

            $matches = Sponsorship::query()
                ->whereDate('sponsorship_date', $sponsorshipDate)
                ->whereDate('created_at', $paymentDate)
                ->whereHas('orphan', function ($query) use ($orphanIdNumber, $guardianIdNumber) {
                    $query->where('association_id', $this->associationId)
                        ->where('id_number', $orphanIdNumber)
                        ->whereHas('profile', fn ($profile) => $profile->where('guardian_id_number', $guardianIdNumber));
                })
                ->get();

            if ($matches->count() !== 1) {
                $matches->count() > 1 ? $this->ambiguous++ : $this->unmatched++;
                continue;
            }

            $sponsorship = $matches->first();
            if ($sponsorship->sponsorship_delivery === 'done') {
                $this->alreadyDone++;
                continue;
            }

            $sponsorship->update(['sponsorship_delivery' => 'done']);
            $this->updated++;
        }
    }

    private function value(Collection $row, array $aliases): ?string
    {
        $aliases = array_map(fn ($alias) => $this->normalizeHeader($alias), $aliases);

        foreach ($row as $key => $value) {
            if (in_array($this->normalizeHeader((string) $key), $aliases, true)) {
                return $this->normalizeNumber($value);
            }
        }

        return null;
    }

    private function date(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            $date = is_numeric($value)
                ? Carbon::instance(Date::excelToDateTimeObject($value))
                : Carbon::parse((string) $value);

            return $date->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizeHeader(string $value): string
    {
        return Str::of($value)->lower()->replace([' ', '_', '-', '/', '\\'], '')->toString();
    }

    private function normalizeNumber(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return strtr(trim((string) $value), [
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);
    }
}
