@extends('template.app')
@section('content')
<style>
    th {
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
        background-color: rgba(40, 167, 69, 0.2) !important;
        /* Hijau transparan untuk HO */
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

<div class="container-fluid mt-4">
    <div class="card w-100">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title">Laporan Konsolidasi Head Office (HO)</h5>
                    <p class="text-muted small">Konsolidasi Nasional per Item. Menghitung data yang sudah diverifikasi Branch.</p>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="mt-3 mb-4 d-flex justify-content-between align-items-end flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class='bx bx-export'></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportExcel()">Export Excel</a></li>
                        </ul>
                    </div>
                </div>

                <form action="" method="GET" class="d-flex gap-2">
                    <div>
                        <label class="form-label small">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm select2" style="width: 100px;">
                            @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success align-self-end">Filter</button>
                </form>
            </div>

            <div class="table-responsive">
                <table id="tableHO" class="table table-striped table-bordered w-100 mt-3" style="font-size: 0.85rem;">
                    @php
                    $listBulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

                    // LOGIKA AGREGASI HO
                    // Group by Item ID saja (Lintas Branch)
                    $groupedData = $data->groupBy('item_id');
                    @endphp

                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Tahun</th>
                            <th rowspan="2">Kategori</th>
                            <th rowspan="2">Item</th>
                            <th rowspan="2">Total RKAP Nasional</th>

                            @foreach ($listBulan as $bln)
                            <th colspan="4" class="text-center">{{ Str::ucfirst($bln) }}</th>
                            @endforeach

                            <th colspan="3" class="text-center">Total Tahunan</th>
                        </tr>
                        <tr>
                            @foreach ($listBulan as $bln)
                            <th>Anggaran</th>
                            <th>Realisasi</th>
                            <th>Selisih</th>
                            <th>Verif HO</th> <!-- Kolom Baru HO -->
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

                        @foreach($groupedData as $itemId => $reports)
                        @php
                        $ref = $reports->first();
                        $tahun = $ref->tahun ?? '-';
                        $kategori = $ref->item->kategori->kategori ?? '-';
                        $itemName = $ref->item->item ?? '-';

                        // Total RKAP Nasional untuk item ini
                        $totalRKAP = $reports->sum(fn($r) => $r->sla->rkap ?? 0);

                        $row_total_anggaran = 0;
                        $row_total_realisasi = 0;
                        @endphp

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $tahun }}</td>
                            <td>{{ Str::upper($kategori) }}</td>
                            <td>{{ Str::upper($itemName) }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($totalRKAP, 0, ',', '.') }}</td>

                            @foreach($listBulan as $month)
                            @php
                            // AGREGASI: Hanya jika sudah verif klinik (atau branch logic jika ada)
                            // Kita pakai logic: sudah verif klinik
                            $validReports = $reports->filter(fn($r) => !empty($r->{$month.'_verif_by'}));

                            $sumAnggaran = $validReports->sum(function($r) use ($month) {
                            $val = $r->$month ?? '0';
                            return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val);
                            });

                            $sumRealisasi = $validReports->sum(function($r) use ($month) {
                            $col = $month . '_realisasi';
                            $val = $r->$col ?? '0';
                            return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val);
                            });

                            $selisih = $sumAnggaran - $sumRealisasi;

                            // Cek Status Verifikasi HO
                            // Karena ini agregasi, kita cek apakah ADA salah satu record yang sudah di verif HO?
                            // Atau HO melakukan verif per Item Global?
                            // Asumsi: HO melakukan verif terhadap agregasi ini.
                            // Note: Anda perlu tabel/mekanisme terpisah untuk menyimpan status 'Verif HO per Item Aggregation'
                            // ATAU HO melakukan verif massal ke semua child rows.
                            // Disini saya tampilkan status "Verified" jika semua child sudah di verif HO, atau "Pending" jika belum.

                            $countVerifiedHO = $reports->filter(fn($r) => !empty($r->{$month.'_verif_by_ho'}))->count();
                            $countTotal = $reports->count();

                            $verifStatus = '-';
                            if($countVerifiedHO == $countTotal && $countTotal > 0) {
                            $verifStatus = '<span class="badge bg-success">Approved</span>';
                            } elseif ($countVerifiedHO > 0) {
                            $verifStatus = '<span class="badge bg-warning text-dark">Partial</span>';
                            } else {
                            // Tombol Action untuk Approve HO (Perlu implementasi modal/backend)
                            $verifStatus = '<button class="btn btn-xs btn-outline-success" onclick="approveHO(\''.$itemId.'\', \''.$month.'\')">Approve</button>';
                            }

                            $row_total_anggaran += $sumAnggaran;
                            $row_total_realisasi += $sumRealisasi;
                            @endphp
                            <td class="text-end">Rp {{ number_format($sumAnggaran, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($sumRealisasi, 0, ',', '.') }}</td>
                            <td class="text-end {{ $selisih < 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($selisih, 0, ',', '.') }}
                            </td>
                            <td class="text-center">{!! $verifStatus !!}</td>
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
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary fw-bold">
                            <td colspan="{{ 5 + (12 * 4) }}" class="text-end text-uppercase">Total Konsolidasi Nasional</td>
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
        new DataTable('#tableHO', {
            scrollX: true
            , scrollCollapse: true
            , fixedColumns: {
                left: 4
            }, // Freeze sampai kolom Item
            paging: true
        });
    });

    function exportExcel() {
        var wb = XLSX.utils.table_to_book(document.getElementById('tableHO'), {
            sheet: "Consolidated HO"
        });
        XLSX.writeFile(wb, 'Laporan_Konsolidasi_HO.xlsx');
    }

    function approveHO(itemId, month) {
        // Implementasi Ajax untuk update [bulan]_verif_by_ho ke database
        alert("Fitur Approval HO untuk Item ID: " + itemId + " Bulan: " + month + " perlu diimplementasikan di Controller.");
    }

</script>
@endpush
@endsection
