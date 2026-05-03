<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['create_by'] = Auth::user()->id;
            Notification::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');

            return redirect()->route('notification.index');
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
            Notification::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');

            return redirect()->route('notification.index');
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
            Notification::hapusData($id);
            toastify()->success('Data Berhasil Dihapus.');
            DB::commit();

            return redirect()->route('notification.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            DB::rollback();
            return redirect()->back();
        }
    }
}
