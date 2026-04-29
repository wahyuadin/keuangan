<?php

namespace App\Exports;

use App\Models\Report;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClinicPerSheetExport implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $tahun;
    protected $clinic;
    protected $bulanAwal;
    protected $bulanAkhir;

    public function __construct($tahun, $clinic, $bulanAwal, $bulanAkhir)
    {
        $this->tahun      = $tahun;
        $this->clinic     = $clinic;
        $this->bulanAwal  = $bulanAwal;
        $this->bulanAkhir = $bulanAkhir;
    }

    public function view(): View
    {
        $listBulan = [
            'januari',
            'februari',
            'maret',
            'april',
            'mei',
            'juni',
            'juli',
            'agustus',
            'september',
            'oktober',
            'november',
            'desember'
        ];

        $startIndex = array_search($this->bulanAwal, $listBulan);
        $endIndex   = array_search($this->bulanAkhir, $listBulan);

        $filteredBulan = array_slice($listBulan, $startIndex, ($endIndex - $startIndex + 1));

        if ($this->clinic) {
            $reports = Report::with(['item.kategori', 'sla'])
                ->where('clinic_id', $this->clinic->id ?? 0)
                ->where('tahun', $this->tahun)
                ->get()
                ->groupBy(function ($item) {
                    return $item->item->kategori->kategori ?? 'LAIN-LAIN';
                });
        } else {
            $reports = [];
        }


        return view('exports.report_clinic_excel', [
            'data'       => $reports,
            'clinic'     => $this->clinic,
            'tahun'      => $this->tahun,
            'listBulan'  => $filteredBulan
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            4 => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function title(): string
    {
        $nama = $this->clinic->nama_klinik ?? 'Data Klinik';
        $safeName = str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $nama);
        return substr(trim($safeName), 0, 31);
    }
}
