<?php

namespace Database\Seeders;

use App\Models\Branchoffice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_branch' => 'Head Office',
                'alamat' => 'Gedung DPK BPJS KETENAGAKERJAAN Lt. 2 Jl. Tangkas Baru No.1, Jakarta Selatan 12930',
            ],
            [
                'nama_branch' => 'Branch Office Bekasi',
                'alamat' => 'Perumahan Puri Esperanza Jl. Kemakmuran RT. 03 RW. 05 Kav 25 Margajaya Bekasi Selatan Kota Bekasi',
            ],
            [
                'nama_branch' => 'Branch Office Cimahi',
                'alamat' => 'Komplek Nusa Hijau Blok J-10 RT 04 RW 18 Kel. Citeureup Kec. Cimahi Utara Kota Cimahi',
            ],
            [
                'nama_branch' => 'Branch Office Cirebon',
                'alamat' => 'Jl. DR. Sudarsono No 282 Kelurahan Kesambi, Kecamatan Kesambi Cirebon, Jawa Barat 45134',
            ],
            [
                'nama_branch' => 'Branch Office Jakarta',
                'alamat' => 'Gedung DPK BPJS Ketenagakerjaan - Jamsostek (Persero) Lt. 2 Jl. Tangkas Baru No. 1 Jakarta Selatan 12930',
            ],
            [
                'nama_branch' => 'Branch Office Malang',
                'alamat' => 'Jl. Ciujung No. 4 Kel.Purwantoro, Blimbing, Malang.',
            ],
            [
                'nama_branch' => 'Branch Office Palembang',
                'alamat' => 'Jl. MP Mangkunegara Perumahan Musi Palem Indah Blok E No. 10 Kel. 8 Ilir Kec. Ilir Timur III Palembang 30114',
            ],
            [
                'nama_branch' => 'Branch Office Pekanbaru',
                'alamat' => 'Perumahan Maharaja Residence Blok C8 Jl. Datuk Setia Maharaja RT. 005 RW. 006 Kel. Tangkerang Selatan Kec. Bukit raya Pekanbaru.',
            ],
            [
                'nama_branch' => 'Branch Office Semarang',
                'alamat' => 'Jl. Rorojonggrang III, No. 1B, RT. 08 RW.10 Kel. Manyaran, Kec. Semarang Barat, Kota Semarang, Jawa Tengah.',
            ],
        ];

        foreach ($data as $item) {
            Branchoffice::create($item);
        }
    }
}
