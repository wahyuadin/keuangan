<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['create_by'] = Auth::user()->id;
            Report::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');
            return redirect()->route('report.index');
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
            Report::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->route('report.index');
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
            Report::hapusData($id);
            toastify()->success('Data Berhasil Dihapus.');
            DB::commit();
            return redirect()->route('report.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }
}
