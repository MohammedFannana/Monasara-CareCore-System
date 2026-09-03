<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orphan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Enums\OrphanRole;

class ImportOrphans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-orphans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '2048M');

        $filePath = storage_path('app/x.xlsx');

        if (!file_exists($filePath)) {
            $this->error('❌ الملف غير موجود: ' . $filePath);
            return;
        }

        try {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->getRowIterator();

            DB::beginTransaction();

            $header = [];
            $batchData = [];
            $batchSize = 500;
            $counter = 0;

            foreach ($rows as $index => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(true);

                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = trim($cell->getValue());
                }

                // أول صف هو رؤوس الأعمدة
                if ($index === 1) {
                    $header = $cells;
                    dd($header);

                    continue;
                }

                if (count($cells) !== count($header)) {
                    continue;
                }

                $data = array_combine($header, $cells);

                // معالجات خفيفة وسريعة
                $birthDate = null;
                if (!empty($data['تاريخ الميلاد'])) {
                    try {
                        $birthDate = is_numeric($data['تاريخ الميلاد'])
                            ? Date::excelToDateTimeObject($data['تاريخ الميلاد'])->format('Y-m-d')
                            : Carbon::parse($data['تاريخ الميلاد'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $birthDate = null;
                    }
                }

                $batchData[] = [
                    'name' => $data['اسم اليتيم-السجل المدني'] ?? null,
                    'birth_date' => $birthDate,
                    'gender' => $data['الجنس'] ?? null,
                    'id_number' => $data['رقم هوية اليتيم'] ?? null,
                    'password' => isset($data['رقم هوية اليتيم']) ? Hash::make($data['رقم هوية اليتيم']) : null,
                    'role' => OrphanRole::CANDIDATE->value,
                    'association_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $counter++;

                // إدخال دفعة بيانات
                if (count($batchData) >= $batchSize) {
                    Orphan::insert($batchData);
                    $batchData = [];
                }
            }

            // إدخال الدفعة الأخيرة
            if (!empty($batchData)) {
                Orphan::insert($batchData);
            }

            DB::commit();
            $this->info("✅ تم استيراد {$counter} صف بنجاح.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

}


