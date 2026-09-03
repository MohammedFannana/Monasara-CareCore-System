<?php

namespace App\Imports;

use App\Models\Gift;
use App\Models\Orphan;
use App\Models\Sponsor;
use Maatwebsite\Excel\Concerns\ToModel;

class GiftsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       if ($row[0] == 'اسم اليتيم')
         { return null; }

        $orphan = Orphan::where('name', trim($row[0]))->first();

        $sponsor = Sponsor::where('name', trim($row[1]))->first();


        if (!$orphan || !$sponsor)
             { return null; }

        return new Gift([
            'orphan_id' => $orphan->id,
            'sponsor_id' => $sponsor->id,
            'gift_date' => $row[2],
            'amount' => $row[3],
            'notes' => $row[4] ?? null,
            ]);
    }
}
