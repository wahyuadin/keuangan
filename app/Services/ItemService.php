<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token', 'secure');
            $data['create_by'] = Auth::id();

            $item = Item::tambahData($data);

            Report::tambahData([
                'clinic_id' => $data['clinic_id'],
                'item_id' => $item->id,
                'branch_id' => $request->secure,
                'tahun' => now()->format('Y'),
                'create_by' => Auth::user()->id,
            ]);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');

            return redirect()->route('item.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            toastify()->error('Error, ' . $th->getMessage());

            return redirect()->back();
        }
    }

    public function edit($id, $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['create_by'] = Auth::user()->id;
            Item::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');

            return redirect()->route('item.index');
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
            Item::hapusData($id);
            toastify()->success('Data Berhasil Dihapus.');
            DB::commit();

            return redirect()->route('item.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);

            return redirect()->back();
            DB::rollback();
        }
    }
}
