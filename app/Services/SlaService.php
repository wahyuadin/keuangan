<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Sla;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SlaService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['create_by'] = Auth::user()->id;
            $dataSLA = Sla::tambahData($data);
            $this->syncMonthlyReport($dataSLA);

            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');

            return redirect()->route('sla.index');
        } catch (\Throwable $th) {
            DB::rollback();
            toastify()->error('Error: ' . $th->getMessage());

            return redirect()->back();
        }
    }

    public function edit($id, $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['create_by'] = Auth::user()->id;

            // 1. Edit Data SLA
            $dataSLA = Sla::editData($id, $data);

            // 2. Update atau Buat ulang Report terkait
            $this->syncMonthlyReport($dataSLA);

            DB::commit();
            toastify()->success('Data Berhasil diedit.');

            return redirect()->route('sla.index');
        } catch (\Throwable $th) {
            DB::rollback();
            toastify()->error('Error: ' . $th->getMessage());

            return redirect()->back();
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            Sla::hapusData($id);
            Report::where('sla_id', $id)->delete();
            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');

            return redirect()->route('sla.index');
        } catch (\Throwable $th) {
            DB::rollback();
            toastify()->error('Error: ' . $th->getMessage());

            return redirect()->back();
        }
    }

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
            'user_id'   => Auth::id(),
            'create_by' => Auth::id(),
        ];

        foreach ($months as $number => $month) {
            $payload[$month] = (int) round(($rkapTotal * $number) / 12);
        }

        return Report::updateOrCreate(
            ['sla_id' => $sla->id],
            $payload
        );
    }
}
