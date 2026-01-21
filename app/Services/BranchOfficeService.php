<?php

namespace App\Services;

use App\Models\Branchoffice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchOfficeService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['create_by'] = Auth::user()->id;
            Branchoffice::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');
            return redirect()->route('branch-office.index');
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
            Branchoffice::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->route('branch-office.index');
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
            Branchoffice::hapusData($id);
            toastify()->success('Data Berhasil Dihapus.');
            DB::commit();
            return redirect()->route('branch-office.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }
}
