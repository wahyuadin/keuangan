<?php

namespace App\Http\Controllers;

use App\Exports\ReportClinicExport;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected $report;

    public function __construct(ReportService $report)
    {
        $this->report = $report;
    }

    public function index(Request $request)
    {
        return view('report.index', ['data' => report::showData($request->bo)]);
    }

    public function branch()
    {
        return view('report.branch-office.index', ['data' => report::showData()]);
    }

    public function headOffice()
    {
        return view('report.head-office.index', ['data' => report::showData()]);
    }

    public function approveHeadOffice(Request $request)
    {
        $request->validate([
            'item_id'       => 'required',
            'month'         => 'required|string',
            'tahun'         => 'required',
            'realisasi_ho'  => 'nullable|numeric|min:0', // Inputan baru
            'keterangan_ho' => 'nullable|string',        // Inputan baru
        ]);

        $month  = strtolower($request->month);
        $itemId = $request->item_id;
        $tahun  = $request->tahun;

        // Tentukan nama kolom dinamis
        $colVerifClinic = $month . '_verif_by';      // Syarat (harus sudah verif klinik)
        $colVerifHO     = $month . '_verif_by_ho';   // Target Update User
        $colRealHO      = $month . '_realisasi_by_ho'; // Target Update Angka
        $colKetHO       = $month . '_keterangan_by_ho'; // Target Update Text

        try {
            DB::beginTransaction();

            // 1. Ambil semua baris report yang valid (sudah diverif klinik) untuk Item & Tahun ini
            $validReports = Report::where('item_id', $itemId)
                ->where('tahun', $tahun)
                ->whereNotNull($colVerifClinic)
                ->get();

            if ($validReports->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada data yang dapat diverifikasi. Pastikan klinik cabang sudah melakukan verifikasi laporan terlebih dahulu.');
            }

            // 2. Update Status Verifikasi & Keterangan HO (Ke SEMUA baris terkait)
            // Keterangan kita samakan ke semua baris agar konsisten
            Report::whereIn('id', $validReports->pluck('id'))
                ->update([
                    $colVerifHO => Auth::user()->nama ?? Auth::user()->name ?? 'HO Admin',
                    $colKetHO   => $request->keterangan_ho
                ]);


            // a. Reset kolom realisasi HO jadi 0 untuk semua baris item ini
            Report::whereIn('id', $validReports->pluck('id'))->update([$colRealHO => 0]);

            // b. Simpan nilai inputan di baris pertama saja
            $inputRealisasi = $request->input('realisasi_ho');

            if (is_numeric($inputRealisasi) && $inputRealisasi > 0) {
                // --- PERBAIKAN DI SINI ---
                // JANGAN gunakan: $validReports->first()->update([...]);
                // GUNAKAN query langsung ke ID agar memaksa update ke database:

                Report::where('id', $validReports->first()->id)->update([
                    $colRealHO => $inputRealisasi
                ]);
            }
            DB::commit();

            return redirect()->back()->with('success', "Berhasil memverifikasi dan menyimpan koreksi HO untuk bulan " . ucfirst($month) . ".");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal melakukan verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->report->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return report::showData($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // return Provider::showData($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        return $this->report->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->report->hapus($id);
    }


    // report
    public function exportClinic(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $branchId = $request->branch_id;

        return Excel::download(
            new ReportClinicExport($tahun, $branchId),
            "Laporan_Konsolidasi_klinik_{$tahun}.xlsx"
        );
    }
}
