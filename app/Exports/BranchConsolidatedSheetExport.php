<?php

namespace App\Exports;

use App\Models\Report;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BranchConsolidatedSheetExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $tahun;
    protected $branch;
    protected $bulanAwal;
    protected $bulanAkhir;

    public function __construct($tahun, $branch, $bulanAwal, $bulanAkhir)
    {
        $this->tahun      = $tahun;
        $this->branch     = $branch;
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

        // Ambil semua report milik klinik yang berada di branch ini
        $reports = Report::with(['clinic.branch', 'item.kategori', 'sla'])
            ->whereHas('clinic', function ($query) {
                $query->where('branch_id', $this->branch->id);
            })
            ->where('tahun', $this->tahun)
            ->get();

        // Group data berdasarkan Kategori -> Item Anggaran
        // Karena kita mau mengkonsolidasi (menjumlahkan) item yang sama dari berbagai klinik
        $groupedData = $reports->groupBy(function ($item) {
            return $item->item->kategori->kategori ?? 'LAIN-LAIN';
        })->map(function ($kategoriGroup) {
            return $kategoriGroup->groupBy('item_id');
        });

        return view('exports.report_branch_excel', [
            'groupedData' => $groupedData,
            'branch'      => $this->branch,
            'tahun'       => $this->tahun,
            'listBulan'   => $filteredBulan
        ]);
    }

    public function title(): string
    {
        // Format: [NAMA-BRANCH]-KONS.KLINIK
        // Contoh: JAKARTA-KONS.KLINIK
        $nama = $this->branch->nama_branch ?? 'BRANCH';
        $safeName = str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $nama);
        $title = trim($safeName) . '-KONS.KLINIK';

        // Excel membatasi nama sheet maksimal 31 karakter
        return substr(strtoupper($title), 0, 31);
    }
}
