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

    public function __construct($tahun, $clinic)
    {
        $this->tahun = $tahun;
        $this->clinic = $clinic;
    }

    public function view(): View
    {
        $reports = Report::with(['item.kategori', 'sla'])
            ->where('clinic_id', $this->clinic->id)
            ->where('tahun', $this->tahun)
            ->get()
            ->groupBy(function ($item) {
                return $item->item->kategori->kategori ?? 'LAIN-LAIN';
            });

        return view('exports.report_clinic_excel', [
            'data' => $reports,
            'clinic' => $this->clinic,
            'tahun' => $this->tahun,
            'listBulan' => ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember']
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
        // Nama Sheet maksimal 31 karakter
        return substr($this->clinic->nama_klinik, 0, 31);
    }
}
