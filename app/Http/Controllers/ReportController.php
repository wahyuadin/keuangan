<?php

namespace App\Http\Controllers;

use App\Exports\ReportClinicExport;
use App\Models\Clinic;
use App\Models\Report;
use App\Models\Sla;
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
        $query = Report::with('clinic.branch', 'item', 'sla');

        // Filter Branch
        if ($request->filled('branch_id')) {
            $query->whereHas('clinic', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Filter Klinik
        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }

        // Filter Tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $data = $query->orderBy('tahun', 'desc')->get();

        return view('report.index', compact('data'));
    }

    public function checkExisting(Request $request)
    {
        $request->validate([
            'id_clinic' => 'required',
            'id_item'   => 'required',
            'tahun'     => 'required',
        ]);

        $data = Report::where('clinic_id', $request->id_clinic)
            ->where('item_id', $request->id_item)
            ->where('tahun', $request->tahun)
            ->first();


        if ($data) {
            return response()->json([
                'success' => true,
                'data'    => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    public function getBranchClinics($branchId)
    {
        return Clinic::where('branch_id', $branchId)
            ->orderBy('nama_klinik')
            ->get(['id', 'nama_klinik']);
    }

    public function getClinicItems($clinicId)
    {
        return Sla::with('item')
            ->where('clinic_id', $clinicId)
            ->get()
            ->map(function ($sla) {
                return [
                    'id' => $sla->item->id,
                    'text' => strtoupper($sla->item->item),
                    'sla_id' => $sla->id,
                    'rkap' => $sla->rkap
                ];
            });
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
            'realisasi_ho'  => 'nullable|numeric|min:0',
            'keterangan_ho' => 'nullable|string',
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
        $request->validate([
            'clinic_id' => 'required',
            'item_id'   => 'required',
            'tahun'     => 'required',
        ]);

        $months = [
            'januari',
            'februari',
            'maret',
            'april',
            'mei',
            'juni',
            'juli',
            'agustus',
            'september',
            'oktober',
            'november',
            'desember'
        ];

        $report = Report::updateOrCreate(
            [
                'clinic_id' => $request->clinic_id,
                'item_id'   => $request->item_id,
                'tahun'     => $request->tahun,
            ],
            [
                'create_by' => Auth::id(),
            ]
        );

        foreach ($months as $m) {
            if ($request->filled($m)) {
                $val = str_replace(['Rp', '.', ' '], '', $request->$m);
                $report->$m = $val ?: 0;
            }
            // Realisasi
            if ($request->filled($m . '_realisasi')) {

                $realVal = str_replace(['Rp', '.', ' '], '', $request->{$m . '_realisasi'});
                $realVal = $realVal ?: 0;
                $report->{$m . '_realisasi'} = $realVal;
                if ($realVal > 0) {
                    $report->{$m . '_verif_by'} = Auth::user()->nama ?? 'Klinik Admin';
                }
            }
            if ($request->has($m . '_keterangan')) {
                $report->{$m . '_keterangan'} =
                    $request->{$m . '_keterangan'};
            }
        }

        // dd($report->toArray());
        $report->save();

        return redirect()->back()
            ->with('success', 'Data Anggaran berhasil dikonsolidasi.');
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
        return $this->report->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->report->hapus($id);
    }


    // export
    public function exportClinic(Request $request)
    {
        $tahun      = $request->tahun ?? date('Y');
        $branchId   = $request->branch_id;
        $bulanAwal  = $request->bulan_awal ?? 'januari';
        $bulanAkhir = $request->bulan_akhir ?? 'desember';

        return Excel::download(
            new ReportClinicExport($tahun, $branchId, [], $bulanAwal, $bulanAkhir),
            "Laporan_{$tahun}_{$bulanAwal}_sd_{$bulanAkhir}.xlsx"
        );
    }
}
