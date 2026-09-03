<?php

namespace App\Imports;

use Exception;
use Carbon\Carbon;
// use App\Models\Image;
// use App\Models\Phone;
// use App\Models\Family;
use App\Models\Orphan;
use App\Models\Sponsor;
// use App\Models\Profile;
// use App\Models\Guardian;
// use App\Models\Marketing;
// use App\Models\Sponsorship;
// use Illuminate\Support\Str;
use App\Models\Sponsorship;
use Illuminate\Support\Facades\DB;
// use App\Models\CertifiedOrphanExtra;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Enums\OrphanRole;

class OrphansImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '1024M');


           // تجاهل الصفوف الفارغة تمامًا
        if (collect($row)->filter()->isEmpty()) {
            Log::info('تم تجاهل صف فارغ', $row);
            return null;
        }

        // تجاهل الصف إذا لم يحتوي على اسم يتيم
        if (empty($row['asm_alytym_alsgl_almdny'])) {
            Log::warning('اسم اليتيم مفقود، تم تجاهل الصف', $row);
            return null;
        }

        try {
            DB::beginTransaction();

            $birthDate = null;

            if (!empty($row['tarykh_almylad'])) {
                if (is_numeric($row['tarykh_almylad'])) {
                    $birthDate = Carbon::instance(Date::excelToDateTimeObject($row['tarykh_almylad']));
                } else {
                    try {
                        $birthDate = Carbon::createFromFormat('d/m/Y', trim($row['tarykh_almylad']));
                    } catch (\Exception $e) {
                        try {
                            $birthDate = Carbon::createFromFormat('Y-m-d', trim($row['tarykh_almylad']));
                        } catch (\Exception $e) {
                            try {
                                $birthDate = Carbon::parse(trim($row['tarykh_almylad']));
                            } catch (\Exception $e) {
                                $birthDate = null;
                            }
                        }
                    }
                }
            }



            //  if (empty($row['asm_alytym'])) {
            //     dd($row['asm_alytym']);
            // Log::error('اسم اليتيم مفقود في صف:', $row);
            // throw new \Exception('الاسم مفقود في ملف الاستيراد');
            // }



            $orphan = Orphan::where('id_number', $row['rkm_hoy_alytym'])->first();

            if (!$orphan) {
                $orphan = Orphan::create([
                    'name' => $row['asm_alytym_alsgl_almdny'],
                    'birth_date' => $birthDate ? $birthDate->format('Y-m-d') : null,
                    'gender' => $row['algns'],
                    'id_number' => $row['rkm_hoy_alytym'],
                    'password' =>  Hash::make($row['rkm_hoy_alytym']),
                    'role' => OrphanRole::CANDIDATE->value,
                    'association_id' => 1,
                ]);
            }


            // نحاول نجيب الكافل بالبريد الإلكتروني
            $sponsor = Sponsor::where('email', $row['albryd_alalktrony_llkafl'])->first();

            if (!$sponsor) {
                // إذا مش موجود، أنشئه
                $sponsor = Sponsor::create([
                    'orphan_id' => $orphan->id, // هذا يمكن الاستغناء عنه لو العلاقة many
                    'name' => $row['asm_alkafl_althlathy'],
                    'email' => $row['albryd_alalktrony_llkafl'],
                    'password' => Hash::make('s2025s2025'), // تأكد من أنه نص وليس مفتاح بالملف
                ]);
            }

            // أنشئ الكفالة بين اليتيم والكافل الموجود أو الجديد
            Sponsorship::create([
                'orphan_id' => $orphan->id,
                'sponsor_id' => $sponsor->id,
            ]);




            // Image::create([
            //     'orphan_id' => $orphan->id,
            //     'birth_certificate' => $row['shhad_almylad'] ?? null,
            //     'father_death_certificate' => $row['shhad_ofa_alab'] ?? null,
            //     'mother_death_certificate' => $row['shhad_ofa_alam'] ?? null,
            //     'mother_card'=> $row['btak_alosy'] ?? null,
            //     'orphan_image_4_6' => $row['sor_shkhsy46'] ?? null,
            //     'orphan_image_9_12' => $row['sor_shkhsy912'] ?? null,
            //     'school_benefit' => $row['alafad_almdrsy'] ?? null,
            //     'medical_report' => $row['altkryr_altby'] ?? null,
            //     'social_research' => $row['albhth_alagtmaaay'] ?? null,
            //     'guardianship_decision' => $row['akrar_blosay'] ?? null,
            //     'data_validation' => $row['akrar_bsh_albyanat'] ?? null,
            //     'agricultural_holding' => $row['hyaz_zraaay'] ?? null,
            // ]);

            // Family::create([
            //     'family_number' => is_numeric($row['aadd_alafrad']) ? (int)$row['aadd_alafrad'] : null,
            //     'orphan_id' => $orphan->id,
            // ]);

            // $numberPhones = explode('/', $row['arkam_altlyfon']);




            // foreach ($numberPhones as $phone) {
            //     if (Str::contains($phone, '\\')) {
            //         $instandNumberPhones = explode('\\', trim($phone));
            //         foreach ($instandNumberPhones as $value) {
            //             Phone::create([
            //         'phone_number' => trim($value),
            //         'orphan_id' => $orphan->id,
            //     ]);
            //         }
            //     }else{

            //         Phone::create([
            //             'phone_number' => trim($phone),
            //             'orphan_id' => $orphan->id,
            //         ]);
            //     }
            // }

            // Profile::create([
            //     // 'mother_name' => $row['asm_alam'],
            //     'full_address' => $row['alaanoan'],
            //     // 'governorate' => $row['almhafth'],
            //     'orphan_id' => $orphan->id,
            // ]);

            // Guardian::create([
            //     'guardian_name' => $row['alos'],
            //     // 'guardian_relationship' => $row['almlahthat'],
            //     'guardian_national_id' => $row['alrkm_alkom'],
            //     'orphan_id' => $orphan->id,
            // ]);

            //   Marketing::create([
            //     'orphan_id' => $orphan->id,
            //     'supporter_id' => 4,
            //     'marketing_date' => now(),
            //     'status' => 'marketing',
            // ]);

            // Sponsorship::create([
            //     'orphan_id' => $orphan->id,
            //     'supporter_id' => 4,
            //     'external_code' => $row['rkm_alytym'],
            //     'status' => 'sponsored',
            //     'sponsorship_date' => now(),
            // ]);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
