<?php

namespace App\Exports;

use App\Models\Clinic;
use App\Models\BranchOffice;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportBranchExport implements WithMultipleSheets
{
    protected $tahun;
    protected $branchId;
    protected $bulanAwal;
    protected $bulanAkhir;

    public function __construct($tahun, $branchId, $clinicIds = [], $bulanAwal = 'januari', $bulanAkhir = 'desember')
    {
        $this->tahun      = $tahun;
        $this->branchId   = $branchId;
        $this->bulanAwal  = $bulanAwal;
        $this->bulanAkhir = $bulanAkhir;
    }

    public function sheets(): array
    {
        $sheets = [];
        $branch = BranchOffice::find($this->branchId);
        if (!$branch) {
            return [];
        }

        $clinics = Clinic::where('branch_id', $this->branchId)
            ->orderBy('nama_klinik', 'asc')
            ->get();

        $sheets[] = new BranchConsolidatedSheetExport(
            $this->tahun,
            $branch,
            $this->bulanAwal,
            $this->bulanAkhir
        );

        foreach ($clinics as $clinic) {
            $sheets[] = new ClinicPerSheetExport(
                $this->tahun,
                $clinic,
                $this->bulanAwal,
                $this->bulanAkhir
            );
        }

        return $sheets;
    }
}
