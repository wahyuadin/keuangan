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

    /* Style tambahan untuk badge & button */
    .badge-soft-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }
    .btn-icon {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>

<div class="container-fluid mt-4">
    <div class="card w-100">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title">Laporan Konsolidasi Head Office (HO)</h5>
                    <p class="text-muted small">Konsolidasi Nasional per Item. Menampilkan data Branch dan Inputan Koreksi HO.</p>
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

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table id="tableHO" class="table table-striped table-bordered w-100 mt-3" style="font-size: 0.85rem;">
                    @php
                    $listBulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

                    // LOGIKA AGREGASI HO: Group by Item ID (Lintas Branch)
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
                            <th colspan="7" class="text-center">{{ Str::ucfirst($bln) }}</th>
                            @endforeach

                            <th colspan="3" class="text-center">Total Tahunan</th>
                        </tr>
                        <tr>
                            @foreach ($listBulan as $bln)
                            <th>Anggaran</th>
                            <th>Realisasi (Branch)</th>
                            <th>Realisasi (HO)</th> <!-- Kolom Baru -->
                            <th>Selisih (HO)</th>   <!-- Kolom Baru (Anggaran - Real HO) -->
                            <th>Ket (Branch)</th>
                            <th>Ket (HO)</th>       <!-- Kolom Baru -->
                            <th>Verif HO</th>
                            @endforeach
                            <th>Saldo</th>
                            <th>Realisasi (HO)</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = 1;
                        $grand_total_saldo = 0;
                        $grand_total_realisasi_ho = 0;
                        $grand_total_selisih_ho = 0;
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
                        $row_total_realisasi_ho = 0;
                        @endphp

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $tahun }}</td>
                            <td>{{ Str::upper($kategori) }}</td>
                            <td>{{ Str::upper($itemName) }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($totalRKAP, 0, ',', '.') }}</td>

                            @foreach($listBulan as $month)
                            @php
                            // AGREGASI: Hanya data yang sudah di verif klinik
                            $validReports = $reports->filter(fn($r) => !empty($r->{$month.'_verif_by'}));

                            // 1. Hitung Anggaran
                            $sumAnggaran = $validReports->sum(function($r) use ($month) {
                                $val = $r->$month ?? '0';
                                return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val);
                            });

                            // 2. Hitung Realisasi BRANCH
                            $sumRealisasiBranch = $validReports->sum(function($r) use ($month) {
                                $col = $month . '_realisasi';
                                $val = $r->$col ?? '0';
                                return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val);
                            });
                            
                            // 3. Hitung Realisasi HO (Inputan HO)
                            // Mengambil sum, karena di controller kita mereset baris lain jadi 0 dan menyimpan nilai hanya di baris pertama
                            $sumRealisasiHO = $validReports->sum(function($r) use ($month) {
                                $col = $month . '_realisasi_by_ho';
                                $val = $r->$col; 
                                return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val ?? '0');
                            });

                            // 4. Hitung Selisih (Versi HO: Anggaran - Realisasi HO)
                            // Jika Realisasi HO masih 0, maka selisih = Anggaran (belum ada pengeluaran di mata HO)
                            $selisihHO = $sumAnggaran - $sumRealisasiHO;

                            // 5. Keterangan
                            // Branch (Gabungan unique)
                            $remarksBranch = $validReports->pluck($month . '_keterangan')->filter()->unique()->implode('; ');
                            // HO (Gabungan unique - biasanya sama semua karena diupdate massal)
                            $remarksHO = $validReports->pluck($month . '_keterangan_by_ho')->filter()->unique()->first();

                            // 6. LOGIKA APPROVAL HO
                            $countVerifiedHO = $validReports->filter(fn($r) => !empty($r->{$month.'_verif_by_ho'}))->count();
                            $countTotalValid = $validReports->count();

                            $verifStatus = '-';
                            if ($countTotalValid > 0) {
                                // Data untuk dikirim ke JS Modal
                                $safeItemName = addslashes($itemName);
                                // Escape keterangan agar tidak merusak JS string
                                $safeKetHO = addslashes($remarksHO); 
                                
                                if ($countVerifiedHO >= $countTotalValid) {
                                    // Sudah diapprove, tetap bisa diedit
                                    $verifStatus = '
                                    <span class="badge badge-soft-success mb-1"><i class="bx bx-check-double"></i> Approved</span>
                                    <br>
                                    <button type="button" class="btn btn-xs btn-outline-secondary" title="Edit Data HO"
                                        onclick="confirmApprove(\''.$itemId.'\', \''.$month.'\', \''.$safeItemName.'\', \''.$tahun.'\', \''.$sumRealisasiHO.'\', \''.$safeKetHO.'\')">
                                        <i class="bx bx-edit"></i>
                                    </button>';
                                } else {
                                    // Belum diapprove
                                    $verifStatus = '<button type="button" class="btn btn-sm btn-outline-success py-0 px-2" 
                                        onclick="confirmApprove(\''.$itemId.'\', \''.$month.'\', \''.$safeItemName.'\', \''.$tahun.'\', \''.$sumRealisasiHO.'\', \''.$safeKetHO.'\')">
                                        Approve & Input
                                    </button>';
                                }
                            }

                            $row_total_anggaran += $sumAnggaran;
                            $row_total_realisasi_ho += $sumRealisasiHO;
                            @endphp
                            
                            <!-- Kolom Data -->
                            <td class="text-end">Rp {{ number_format($sumAnggaran, 0, ',', '.') }}</td>
                            <td class="text-end text-muted small">Rp {{ number_format($sumRealisasiBranch, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-primary">Rp {{ number_format($sumRealisasiHO, 0, ',', '.') }}</td>
                            <td class="text-end {{ $selisihHO < 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($selisihHO, 0, ',', '.') }}
                            </td>
                            
                            <!-- Kolom Keterangan -->
                            <td class="small text-truncate" style="max-width: 100px;" title="{{ $remarksBranch }}">{{ Str::limit($remarksBranch, 15) }}</td>
                            <td class="small text-truncate fw-bold text-primary" style="max-width: 100px;" title="{{ $remarksHO }}">{{ Str::limit($remarksHO, 15) }}</td>
                            
                            <!-- Tombol Aksi -->
                            <td class="text-center">{!! $verifStatus !!}</td>
                            @endforeach

                            @php
                            $row_selisih_total = $row_total_anggaran - $row_total_realisasi_ho;
                            $grand_total_saldo += $row_total_anggaran;
                            $grand_total_realisasi_ho += $row_total_realisasi_ho;
                            $grand_total_selisih_ho += $row_selisih_total;
                            @endphp

                            <td class="fw-bold text-end bg-light">Rp {{ number_format($row_total_anggaran, 0, ',', '.') }}</td>
                            <td class="fw-bold text-end bg-light">Rp {{ number_format($row_total_realisasi_ho, 0, ',', '.') }}</td>
                            <td class="fw-bold text-end bg-light">Rp {{ number_format($row_selisih_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary fw-bold">
                            <!-- Colspan disesuaikan dengan jumlah kolom -->
                            <td colspan="{{ 5 + (12 * 7) }}" class="text-end text-uppercase">Total Konsolidasi Nasional (Versi HO)</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_saldo, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_realisasi_ho, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_selisih_ho, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Approve & Input HO -->
<div class="modal fade" id="approveHOModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approval & Input HO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formApproveHO" action="{{ route('report.approve_ho') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Info Header -->
                    <div class="bg-light p-2 rounded mb-3 border">
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr><td width="20%">Item</td><td width="5%">:</td><td class="fw-bold" id="modalItemName"></td></tr>
                            <tr><td>Periode</td><td>:</td><td class="fw-bold"><span id="modalMonth"></span> <span id="modalYear"></span></td></tr>
                        </table>
                    </div>

                    <!-- Input Form HO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Realisasi Head Office</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="realisasi_ho" id="inputRealisasiHO" placeholder="Masukkan angka realisasi versi HO" step="any">
                        </div>
                        <div class="form-text text-muted small">Jika kosong, akan dianggap 0.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan / Catatan HO</label>
                        <textarea class="form-control" name="keterangan_ho" id="inputKeteranganHO" rows="3" placeholder="Masukkan catatan untuk item ini..."></textarea>
                    </div>

                    <div class="alert alert-info small mb-0 d-flex align-items-center gap-2">
                        <i class='bx bx-info-circle fs-4'></i> 
                        <div>Tombol "Simpan & Approve" akan memverifikasi data dan menyimpan angka koreksi HO di database.</div>
                    </div>
                    
                    <!-- Hidden Fields -->
                    <input type="hidden" name="item_id" id="inputItemId">
                    <input type="hidden" name="month" id="inputMonth">
                    <input type="hidden" name="tahun" id="inputYear">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class='bx bx-check-double'></i> Simpan & Approve</button>
                </div>
            </form>
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
        $('.select2').select2({ theme: "bootstrap-5" });
        new DataTable('#tableHO', {
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: { left: 4 }, 
            paging: true,
            pageLength: 10
        });
    });

    function exportExcel() {
        // Logic export excel (seperti sebelumnya, sesuaikan jumlah kolom jika perlu)
        var wb = XLSX.utils.table_to_book(document.getElementById('tableHO'), {sheet:"Consolidated HO"});
        XLSX.writeFile(wb, 'Laporan_Konsolidasi_HO.xlsx');
    }

    // Update Fungsi JS untuk menerima parameter nilai existing
    function confirmApprove(itemId, month, itemName, year, currentRealisasi, currentKet) {
        $('#inputItemId').val(itemId);
        $('#inputMonth').val(month);
        $('#inputYear').val(year);
        
        $('#modalItemName').text(itemName);
        $('#modalMonth').text(month.charAt(0).toUpperCase() + month.slice(1));
        $('#modalYear').text(year);
        
        // Isi form dengan data yang sudah ada (jika mau edit)
        // Parse float untuk memastikan angka valid, jika 0 atau NaN kosongkan
        let valReal = currentRealisasi;
        $('#inputRealisasiHO').val((!isNaN(valReal) && valReal > 0) ? valReal : '');
        
        // Isi textarea
        // Decode special chars if necessary or handle undefined
        $('#inputKeteranganHO').val(currentKet && currentKet !== '-' && currentKet !== 'null' ? currentKet : '');
        
        var modal = new bootstrap.Modal(document.getElementById('approveHOModal'));
        modal.show();
    }
</script>
@endpush
@endsection