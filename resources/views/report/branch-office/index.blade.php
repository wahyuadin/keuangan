@extends('template.app')
@section('content')
<style>
    th {
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
        background-color: rgba(255, 193, 7, 0.2) !important;
        /* Kuning transparan untuk Branch */
        color: #000 !important;
        border-bottom: 2px solid #aaa;
    }

    td {
        white-space: nowrap;
    }

    .dataTables_scrollHeadInner {
        width: 100% !important;
    }

</style>

<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border text-warning" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container-fluid mt-4">
    <div class="card w-100">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title">Laporan Konsolidasi Branch Office</h5>
                    <p class="text-muted small">Data diagregasi per Branch & Item. Hanya menghitung data klinik yang sudah diverifikasi.</p>
                </div>
            </div>

            <!-- Toolbar & Filter -->
            <div class="mt-3 mb-4 d-flex justify-content-between align-items-end flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-export'></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportExcel()">Export Excel</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Filter Form (Sesuaikan dengan route controller Anda) -->
                <form action="" method="GET" class="d-flex gap-2 flex-wrap">
                    <div>
                        <label class="form-label small">Branch</label>
                        <select name="branch_id" class="form-select form-select-sm select2" style="width: 150px;">
                            <option value="">Semua Branch</option>
                            @foreach(\App\Models\BranchOffice::all() as $b)
                            <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_branch }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label small">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm select2" style="width: 100px;">
                            @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="align-self-end">
                        <button type="submit" class="btn btn-sm btn-info text-white">Filter</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="tableBranch" class="table table-striped table-bordered w-100 mt-3" style="font-size: 0.85rem;">
                    @php
                    $listBulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

                    // LOGIKA AGREGASI DATA
                    // 1. Group by Branch ID
                    // 2. Group by Item ID
                    // 3. Sum data bulan HANYA JIKA verified
                    $groupedData = $data->groupBy(['clinic.branch_id', 'item_id']);
                    @endphp

                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Branch Office</th>
                            <th rowspan="2">Tahun</th>
                            <th rowspan="2">Kategori</th>
                            <th rowspan="2">Item</th>
                            <th rowspan="2">Total RKAP</th>

                            @foreach ($listBulan as $bln)
                            <th colspan="3" class="text-center">{{ Str::ucfirst($bln) }}</th>
                            @endforeach

                            <th colspan="3" class="text-center">Total Tahunan</th>
                        </tr>
                        <tr>
                            @foreach ($listBulan as $bln)
                            <th>Anggaran</th>
                            <th>Realisasi</th>
                            <th>Selisih</th>
                            @endforeach
                            <th>Saldo</th>
                            <th>Realisasi</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = 1;
                        $grand_total_saldo = 0;
                        $grand_total_realisasi = 0;
                        $grand_total_selisih = 0;
                        @endphp

                        @foreach($groupedData as $branchId => $itemsGroup)
                        @foreach($itemsGroup as $itemId => $reports)
                        @php
                        // Ambil data referensi dari item pertama di group
                        $ref = $reports->first();
                        $branchName = $ref->clinic->branch->nama_branch ?? '-';
                        $tahun = $ref->tahun ?? '-';
                        $kategori = $ref->item->kategori->kategori ?? '-';
                        $itemName = $ref->item->item ?? '-';

                        // Hitung Total RKAP (Sum RKAP dari semua klinik di branch ini untuk item ini)
                        $totalRKAP = $reports->sum(fn($r) => $r->sla->rkap ?? 0);

                        $row_total_anggaran = 0;
                        $row_total_realisasi = 0;
                        @endphp

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ Str::upper($branchName) }}</td>
                            <td>{{ $tahun }}</td>
                            <td>{{ Str::upper($kategori) }}</td>
                            <td>{{ Str::upper($itemName) }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($totalRKAP, 0, ',', '.') }}</td>

                            @foreach($listBulan as $month)
                            @php
                            // HITUNG AGREGASI PER BULAN
                            // Hanya ambil data jika [bulan]_verif_by TIDAK NULL (Sudah Verif Klinik)

                            $sumAnggaran = $reports->filter(fn($r) => !empty($r->{$month.'_verif_by'}))
                            ->sum(function($r) use ($month) {
                            $val = $r->$month ?? '0';
                            return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val);
                            });

                            $sumRealisasi = $reports->filter(fn($r) => !empty($r->{$month.'_verif_by'}))
                            ->sum(function($r) use ($month) {
                            $col = $month . '_realisasi';
                            $val = $r->$col ?? '0';
                            return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val);
                            });

                            $selisih = $sumAnggaran - $sumRealisasi;

                            $row_total_anggaran += $sumAnggaran;
                            $row_total_realisasi += $sumRealisasi;
                            @endphp
                            <td class="text-end">Rp {{ number_format($sumAnggaran, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($sumRealisasi, 0, ',', '.') }}</td>
                            <td class="text-end {{ $selisih < 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($selisih, 0, ',', '.') }}
                            </td>
                            @endforeach

                            @php
                            $row_selisih_total = $row_total_anggaran - $row_total_realisasi;
                            $grand_total_saldo += $row_total_anggaran;
                            $grand_total_realisasi += $row_total_realisasi;
                            $grand_total_selisih += $row_selisih_total;
                            @endphp

                            <td class="fw-bold text-end bg-light">Rp {{ number_format($row_total_anggaran, 0, ',', '.') }}</td>
                            <td class="fw-bold text-end bg-light">Rp {{ number_format($row_total_realisasi, 0, ',', '.') }}</td>
                            <td class="fw-bold text-end bg-light">Rp {{ number_format($row_selisih_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary fw-bold">
                            <td colspan="{{ 6 + (12 * 3) }}" class="text-end text-uppercase">Total Konsolidasi Branch</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_saldo, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_realisasi, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_selisih, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap-5"
        });
        new DataTable('#tableBranch', {
            scrollX: true
            , scrollCollapse: true
            , fixedColumns: {
                left: 2
            }
            , paging: true
        });
    });

    function exportExcel() {
        // Gunakan logika export yang sama dengan report.blade.php tapi sesuaikan kolomnya
        // Implementasi sederhana:
        var wb = XLSX.utils.table_to_book(document.getElementById('tableBranch'), {
            sheet: "Consolidated Branch"
        });
        XLSX.writeFile(wb, 'Laporan_Konsolidasi_Branch.xlsx');
    }

</script>
@endpush
@endsection
