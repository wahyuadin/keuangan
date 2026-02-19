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
            Report::tambahData([
                'item_id' => $data['item_id'],
                'sla_id' => $dataSLA->id,
                'clinic_id' => $data['clinic_id'],
                'tahun' => now()->format('Y'),
                'create_by' => Auth::user()->id,
            ]);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');

            return redirect()->route('sla.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);

            return redirect()->back();
            DB::rollback();
        }
    }

    public function edit($id, $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['create_by'] = Auth::user()->id;
            Sla::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');

            return redirect()->route('sla.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            DB::rollback();

            return redirect()->back();
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            Sla::hapusData($id);
            Report::where('sla_id', $id)->delete();
            toastify()->success('Data Berhasil Dihapus.');
            DB::commit();

            return redirect()->route('sla.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);

            return redirect()->back();
            DB::rollback();
        }
    }
}
