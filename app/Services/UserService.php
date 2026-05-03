<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function tambah($request)
    {
        DB::beginTransaction();

        try {
            $data = $request->except('_token', 'repassword');

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $data['avatar'] = $file->store('avatar', 'public');
            }

            User::create($data);

            DB::commit();

            toastify()->success('Data berhasil disimpan');
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();

            toastify()->error('Gagal menyimpan data: ' . $th->getMessage());
            return false;
        }
    }

    public function edit($id, $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['create_by'] = Auth::user()->id;
            User::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->route('user-data.index');
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
            User::hapusData($id);
            toastify()->success('Data Berhasil Dihapus.');
            DB::commit();
            return redirect()->route('user-data.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            DB::rollback();
            return redirect()->back();
        }
    }
}
