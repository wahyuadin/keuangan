<?php

namespace App\Exports;

use App\Models\Clinic;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportClinicExport implements WithMultipleSheets
{
    protected $tahun;
    protected $branchId;
    protected $clinicIds;

    /**
     * @param int|string
     * @param string|null
     * @param mixed
     */
    public function __construct($tahun, $branchId = null, $clinicIds = [])
    {
        $this->tahun = $tahun;
        $this->branchId = $branchId;

        if (is_array($clinicIds)) {
            $this->clinicIds = $clinicIds;
        } else {
            $this->clinicIds = !empty($clinicIds) ? [$clinicIds] : [];
        }
    }

    public function sheets(): array
    {
        $sheets = [];
        $query = Clinic::query();

        if (!empty($this->branchId)) {
            $query->where('branch_id', $this->branchId);
        }

        if (!empty($this->clinicIds)) {
            $query->whereIn('id', $this->clinicIds);
        }

        $clinics = $query->orderBy('nama_klinik', 'asc')->get();

        if ($clinics->isEmpty()) {
            $dummyClinic = new Clinic();
            $dummyClinic->nama_klinik = 'KLINIK NAYAKA HUSADA';
            return [new ClinicPerSheetExport($this->tahun, $dummyClinic)];
        }

        foreach ($clinics as $clinic) {
            $sheets[] = new ClinicPerSheetExport($this->tahun, $clinic);
        }

        return $sheets;
    }
}
