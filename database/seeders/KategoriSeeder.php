<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori' => 'PENDAPATAN'
            ],
            [
                'kategori' => 'HONORARIUM'
            ],
            [
                'kategori' => 'BEBAN OPERASIONAL'
            ],
            [
                'kategori' => 'BEBAN UMUM KLINIK'
            ],
            [
                'kategori' => 'BEBAN OBAT'
            ],
            [
                'kategori' => 'JAMINAN MEDIS KLINIK'
            ],
        ];

        foreach ($data as $item) {
            Kategori::create($item);
        }
    }
}
