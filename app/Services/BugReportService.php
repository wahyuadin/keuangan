<?php

namespace App\Services;

use App\Models\BugReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BugReportService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $user = Auth::user();
            $data['pelapor'] = $user->nama ?? 'unknown';
            $data['user_id'] = $user->id ?? null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $path = $file->store('foto', 'public');
                $data['foto'] = $path;
            }
            BugReport::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');
            return redirect()->route('bug-report.index');
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
            $user = Auth::user();
            $data['pelapor'] = $user->nama ?? 'unknown';
            $data['user_id'] = $user->id ?? null;
            BugReport::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->route('bug-report.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            BugReport::hapusData($id);
            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');
            return redirect()->route('bug-report.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }


    public function accept($id)
    {
        DB::beginTransaction();
        try {
            $agent = BugReport::findOrFail($id);
            $agent->fill(['status' => 1]);
            $agent->save();

            DB::commit();
            toastify()->success('Berhasil Accept.');
            return redirect()->route('bug-report.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    public function reject($id)
    {
        DB::beginTransaction();
        try {
            $agent = BugReport::findOrFail($id);
            $agent->fill(['status' => 2]);
            $agent->save();
            DB::commit();
            toastify()->success('Berhasil Reject.');
            return redirect()->route('bug-report.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }
}
