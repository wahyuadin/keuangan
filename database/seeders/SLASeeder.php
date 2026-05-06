<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Sla;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SLASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private function syncMonthlyReport($sla)
    {
        $rkapTotal = $sla->rkap ?? 0;

        $months = [
            1 => 'januari',
            2 => 'februari',
            3 => 'maret',
            4 => 'april',
            5 => 'mei',
            6 => 'juni',
            7 => 'juli',
            8 => 'agustus',
            9 => 'september',
            10 => 'oktober',
            11 => 'november',
            12 => 'desember',
        ];

        $payload = [
            'item_id'   => $sla->item_id,
            'clinic_id' => $sla->clinic_id,
            'tahun'     => $sla->tahun,
            'user_id'   => NULL,
            'create_by' => NULL,
        ];

        foreach ($months as $number => $month) {
            $payload[$month] = (int) round(($rkapTotal * $number) / 12);
        }

        return Report::updateOrCreate(
            ['sla_id' => $sla->id],
            $payload
        );
    }

    public function run(): void
    {
        $data = [
            ['id' => Str::uuid(), 'item_id' => 'a1b73aa2-633d-4303-a095-8d3493c726a5', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73ab0-7ab6-4e33-a41e-97a0824a8a64', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73ac3-20a8-42ca-b485-ac3b9374c3d7', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73ad0-b029-4494-908e-562973471a2f', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73ae2-010e-43a0-9e21-3517174c0d77', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73af2-226f-4f07-92a7-e0e16ff1a51e', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73afe-8160-4323-a271-48a1bc377d8f', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73b20-1090-4194-bad1-4f3a4914e545', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73b2c-dcd1-48cd-9283-27e0cda193b1', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73b39-fa4b-4524-86cd-5a4dadff427b', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73b49-2f8c-4b9f-9ac5-135cad969885', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'a1b73b56-57dd-4126-a55c-eefb34a7b375', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c327837e-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278748-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c32787cc-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c327881d-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c327886e-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c32788b1-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c32788dd-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278908-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278935-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278962-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c327898c-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c32789b6-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c32789e2-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278a0b-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278a37-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278a64-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278a90-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278abc-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278ae8-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278b13-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278b3d-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278b6c-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278bae-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278c8c-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278cbd-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278ce9-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278d17-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278d44-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278d71-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278da0-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278dcc-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278df8-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278e26-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278e53-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278e82-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278eb0-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278ee1-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278f0e-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278f3b-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278f6a-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278f9b-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278fca-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c3278ffc-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c327902b-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c327905c-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
            ['id' => Str::uuid(), 'item_id' => 'c327908c-4968-11f1-9c09-bc2411d05483', 'clinic_id' => '68fac563-496b-11f1-9c09-bc2411d05483', 'rkap' => 10000000, 'tahun' => 2026, 'create_by' => NULL],
        ];

        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                $sla = Sla::tambahData($row);
                $this->syncMonthlyReport($sla);
            }
            Log::info('SLASeeder: Data SLA berhasil ditambahkan dan laporan bulanan disinkronkan.');
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
        }
    }
}
