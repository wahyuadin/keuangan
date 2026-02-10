@extends('template.app')
@section('content')
<style>
    /* Agar header tabel tetap rapi saat scroll */
    th {
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
        background-color: rgba(54, 162, 235, 0.2) !important;
        /* Biru muda transparan */
        color: #000 !important;
        /* Text hitam agar kontras */
        border-bottom: 2px solid #aaa;
    }

    td {
        white-space: nowrap;
    }

    .dataTables_scrollHeadInner {
        width: 100% !important;
    }

    /* Styling khusus untuk tombol action yang dijabarkan */
    .btn-icon {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

</style>

<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container-fluid mt-4">
    <!-- Pakai container-fluid agar full width -->
    <div class="card w-100">
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-8">
                    <h5 class="card-title">Data Report Klinik</h5>
                </div>
            </div>

            <!-- Toolbar & Filter -->
            <div class="mt-3 mb-4 d-flex justify-content-between align-items-end flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKategori">
                        <i class='bx bx-plus'></i> Tambah
                    </button>
                    <div class="dropdown">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-export'></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="printPDF()">Print PDF</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportExcel()">Export Excel</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Filter Form -->
                <form action="" method="GET" class="d-flex gap-2 flex-wrap">
                    <div>
                        <label class="form-label small">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm select2" style="width: 120px;">
                            @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" {{ request('bulan', date('n')) == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                                @endfor
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
                    <div>
                        <label class="form-label small">Status</label>
                        <select name="approve" class="form-select form-select-sm select2" style="width: 120px;">
                            <option value="" {{ request('approve') == '' ? 'selected' : '' }}>All</option>
                            <option value="1" {{ request('approve') == '1' ? 'selected' : '' }}>Approve</option>
                            <option value="0" {{ request('approve') == '0' ? 'selected' : '' }}>Not Approve</option>
                        </select>
                    </div>
                    <div class="align-self-end">
                        <button type="submit" class="btn btn-sm btn-info text-white">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Tabel -->
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered w-100 mt-3" style="font-size: 0.85rem;">
                    @include('alert')

                    @php
                    $listBulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
                    @endphp

                    <thead>
                        <!-- Baris 1: Header Utama & Grouping Bulan -->
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Klinik</th>
                            <th rowspan="2">Branch</th>
                            <th rowspan="2">Tahun</th>
                            <th rowspan="2">Kategori</th>
                            <th rowspan="2">Item</th>
                            <th rowspan="2">Penetapan RKAP</th>

                            @foreach ($listBulan as $bln)
                            <th colspan="5" class="text-center">{{ Str::ucfirst($bln) }}</th>
                            @endforeach

                            <th colspan="3" class="text-center">Total</th> <!-- Colspan jadi 3 -->
                            <th rowspan="2">Created By</th>
                            <th rowspan="2">Created At</th>
                            <th rowspan="2" style="min-width: 120px;">Action</th>
                        </tr>
                        <!-- Baris 2: Detail per Bulan -->
                        <tr>
                            @foreach ($listBulan as $bln)
                            <th>Anggaran</th>
                            <th>Realisasi</th>
                            <th>Selisih</th>
                            <th>Ket</th>
                            <th>Verif</th>
                            @endforeach
                            <th>Saldo</th>
                            <th>Realisasi</th> <!-- Tambah kolom Header Realisasi -->
                            <th>Selisih</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                        $grand_total_saldo = 0;
                        $grand_total_realisasi = 0; // Init grand total realisasi
                        $grand_total_selisih = 0;
                        @endphp

                        @foreach ($data as $index => $dataItem)
                        @php
                        $row_total_anggaran = 0;
                        $row_total_realisasi = 0;

                        // Hitung total per baris
                        foreach ($listBulan as $month) {
                        // 1. Bersihkan Anggaran
                        $raw_anggaran = $dataItem->$month ?? '0';
                        $val_anggaran = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $raw_anggaran);

                        // 2. Bersihkan Realisasi
                        $col_realisasi_name = $month . '_realisasi';
                        $raw_realisasi = $dataItem->$col_realisasi_name ?? '0';
                        $val_realisasi = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $raw_realisasi);

                        $row_total_anggaran += $val_anggaran;
                        $row_total_realisasi += $val_realisasi;
                        }

                        // Total Selisih Baris = Total Anggaran - Total Realisasi
                        $row_total_selisih_hitung = $row_total_anggaran - $row_total_realisasi;

                        $grand_total_saldo += $row_total_anggaran;
                        $grand_total_realisasi += $row_total_realisasi; // Akumulasi
                        $grand_total_selisih += $row_total_selisih_hitung;
                        $user = \App\Models\User::find($dataItem->create_by);
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ Str::limit(Str::upper($dataItem->clinic->nama_klinik ?? '-'), 20) }}</td>
                            <td>{{ Str::upper($dataItem->clinic->branch->nama_branch ?? '-') }}</td>
                            <td>{{ $dataItem->tahun ?? '-' }}</td>
                            <td>{{ Str::upper($dataItem->item->kategori->kategori ?? '-') }}</td>
                            <td>{{ Str::upper($dataItem->item->item ?? '-') }}</td>
                            <td class="text-end">Rp {{ number_format($dataItem->sla->rkap ?? 0, 0, ',', '.') }}</td>

                            <!-- Loop Kolom Bulan -->
                            @foreach ($listBulan as $month)
                            @php
                            // 1. Ambil Anggaran & Bersihkan
                            $raw_anggaran = $dataItem->$month ?? '0';
                            $anggaran = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $raw_anggaran);

                            // 2. Ambil Realisasi & Bersihkan
                            $raw_realisasi = $dataItem->{$month . '_realisasi'} ?? '0';
                            $realisasi = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $raw_realisasi);

                            // 3. Hitung Selisih (Anggaran - Realisasi)
                            $selisih_hitung = $anggaran - $realisasi;

                            // Data Text
                            $ket = $dataItem->{$month . '_keterangan'} ?? '-';
                            $verif = $dataItem->{$month . '_verif_by'} ?? '-';
                            @endphp
                            <td class="text-end">Rp {{ number_format($anggaran, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($realisasi, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold {{ $selisih_hitung < 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($selisih_hitung, 0, ',', '.') }}
                            </td>
                            <td>{{ $ket }}</td>
                            <td>{{ $verif }}</td>
                            @endforeach

                            <!-- Totals Row -->
                            <td class="fw-bold text-end">Rp {{ number_format($row_total_anggaran, 0, ',', '.') }}</td>
                            <td class="fw-bold text-end">Rp {{ number_format($row_total_realisasi, 0, ',', '.') }}</td> <!-- Tambah Total Realisasi Row -->
                            <td class="fw-bold text-end">Rp {{ number_format($row_total_selisih_hitung, 0, ',', '.') }}</td>

                            <td>{{ Str::upper($user->nama ?? '-') }}</td>
                            <td>{{ $dataItem->updated_at ? $dataItem->updated_at->format('d-m-Y') : '-' }}</td>

                            <td>
                                @if(Auth::user()->role == '2')
                                <div class="d-flex gap-1">
                                    <!-- Tombol Dijabarkan (Bukan Dropdown) -->
                                    <button type="button" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#inputSaldo{{ $dataItem->id }}" title="Input Saldo">
                                        <i class='bx bx-money'></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#verifReport{{ $dataItem->id }}" title="Verifikasi">
                                        <i class='bx bx-check-circle'></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#deleteReport{{ $dataItem->id }}" title="Hapus">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="table-secondary fw-bold">
                            <!-- Colspan: 7 Kolom awal + (12 bulan * 5 kolom) = 67 columns -->
                            <td colspan="67" class="text-end text-uppercase">Total Keseluruhan</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_saldo, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_realisasi, 0, ',', '.') }}</td> <!-- Tambah Grand Total Realisasi -->
                            <td class="text-end text-nowrap">Rp {{ number_format($grand_total_selisih, 0, ',', '.') }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@include('report.modal.add')
@include('report.modal.edit')
@include('report.modal.verif')
@include('report.modal.delete')

@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2').select2({
            theme: "bootstrap-5"
            , width: 'resolve'
        });

        // Inisialisasi DataTable dengan Scroll Horizontal
        var table = new DataTable('#example', {
            scrollX: true, // Aktifkan scroll horizontal
            scrollCollapse: true, // Tabel menyesuaikan tinggi
            fixedColumns: true, // Jika ingin kolom kiri diam (perlu plugin fixedColumns)
            paging: true
            , pageLength: 10
            , columnDefs: [{
                targets: '_all'
                , className: 'dt-head-center'
            }]
        });
    });

    // Helper: Export Excel
    function exportExcel() {
        document.getElementById('loading-overlay').classList.remove('d-none');

        setTimeout(() => {
            try {
                /* * SKEMA KOLOM BARU (dengan tambahan Total Realisasi):
                 * 0-6: Info Dasar (No -> RKAP) -> 7 Kolom
                 * 7-66: Data Bulan (12 Bulan x 5 Kolom) -> 60 Kolom
                 * 67: Total Saldo
                 * 68: Total Realisasi (BARU)
                 * 69: Total Selisih
                 * 70: Created By
                 * 71: Created At
                 * Total 72 kolom (index 0-71) + Action (index 72)
                 */

                let header1 = ["No", "Klinik", "Branch Office", "Tahun", "Kategori", "Item", "Penetapan RKAP"];
                let header2 = ["", "", "", "", "", "", ""];

                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                const subHeaders = ["Anggaran", "Realisasi", "Selisih", "Keterangan", "Verif By"];

                // Build Headers
                months.forEach(m => {
                    header1.push(m);
                    header1.push("", "", "", "");
                    header2.push(...subHeaders);
                });

                // Tail Headers
                header1.push("Total Saldo", "Total Realisasi", "Total Selisih", "Created By", "Created At");
                header2.push("", "", "", "", "");

                // Build Data
                let dataRows = [];

                $('#example tbody tr').each(function() {
                    let row = [];
                    $(this).find('td').each(function(index) {
                        // Skip kolom terakhir (Action) -> index 72
                        if (index < 72) {
                            row.push($(this).text().trim());
                        }
                    });
                    if (row.length > 0) dataRows.push(row);
                });

                // Footer (Totals)
                // Total Saldo (67), Total Realisasi (68), Total Selisih (69)
                let footerRow = new Array(header1.length).fill("");
                let tfoot = $('#example tfoot tr');

                footerRow[0] = "TOTAL KESELURUHAN";
                footerRow[67] = tfoot.find('td').eq(1).text().trim(); // Total Saldo
                footerRow[68] = tfoot.find('td').eq(2).text().trim(); // Total Realisasi
                footerRow[69] = tfoot.find('td').eq(3).text().trim(); // Total Selisih

                dataRows.push(footerRow);

                // Create Workbook
                let wb = XLSX.utils.book_new();
                let ws_data = [header1, header2, ...dataRows];
                let ws = XLSX.utils.aoa_to_sheet(ws_data);

                // Merge Configurations
                let merges = [];

                // 1. Merge Header Utama Vertikal (Kolom 0-6)
                for (let c = 0; c < 7; c++) {
                    merges.push({
                        s: {
                            r: 0
                            , c: c
                        }
                        , e: {
                            r: 1
                            , c: c
                        }
                    });
                }

                // 2. Merge Header Bulan (Per 5 Kolom)
                for (let m = 0; m < 12; m++) {
                    let startCol = 7 + (m * 5);
                    merges.push({
                        s: {
                            r: 0
                            , c: startCol
                        }
                        , e: {
                            r: 0
                            , c: startCol + 4
                        }
                    });
                }

                // 3. Merge Header Akhir Vertikal (Totals & Meta) - Sekarang ada 5 kolom akhir
                for (let c = 0; c < 5; c++) {
                    let col = 67 + c;
                    merges.push({
                        s: {
                            r: 0
                            , c: col
                        }
                        , e: {
                            r: 1
                            , c: col
                        }
                    });
                }

                // 4. Merge Footer
                let lastRowIdx = ws_data.length - 1;
                merges.push({
                    s: {
                        r: lastRowIdx
                        , c: 0
                    }
                    , e: {
                        r: lastRowIdx
                        , c: 66
                    }
                });

                ws['!merges'] = merges;

                // Auto Width
                let wscols = [];
                for (let i = 0; i < header1.length; i++) {
                    wscols.push({
                        wch: 15
                    });
                }
                ws['!cols'] = wscols;

                XLSX.utils.book_append_sheet(wb, ws, "Laporan RKAP");
                XLSX.writeFile(wb, "Laporan_RKAP_Klinik.xlsx");

            } catch (error) {
                console.error(error);
                alert("Gagal export excel: " + error.message);
            } finally {
                document.getElementById('loading-overlay').classList.add('d-none');
            }
        }, 500);
    }

    // Helper: Print PDF
    function printPDF() {
        document.getElementById('loading-overlay').classList.remove('d-none');
        const {
            jsPDF
        } = window.jspdf;

        let doc = new jsPDF('l', 'mm', 'a3');

        let title = "LAPORAN DATA KLINIK (RKAP)";
        doc.setFontSize(14);
        doc.text(title, 14, 15);
        doc.setFontSize(10);
        doc.text("Generated: " + new Date().toLocaleString(), 14, 22);

        // Update Headers PDF
        let headers = [
            ["No", "Klinik", "Branch", "Item", "Total Saldo", "Total Realisasi", "Total Selisih", "Created By"]
        ];
        let data = [];

        $('#example tbody tr').each(function() {
            let cells = $(this).find('td');

            // Ambil kolom Summary (Update Index karena ada kolom baru)
            let totalSaldo = $(this).find('td').eq(67).text().trim();
            let totalRealisasi = $(this).find('td').eq(68).text().trim();
            let totalSelisih = $(this).find('td').eq(69).text().trim();
            let createdBy = $(this).find('td').eq(70).text().trim();

            data.push([
                cells.eq(0).text().trim()
                , cells.eq(1).text().trim()
                , cells.eq(2).text().trim()
                , cells.eq(5).text().trim()
                , totalSaldo
                , totalRealisasi
                , totalSelisih
                , createdBy
            ]);
        });

        doc.autoTable({
            head: headers
            , body: data
            , startY: 30
            , theme: 'grid'
            , headStyles: {
                fillColor: [41, 128, 185]
            }
        });

        doc.save("Report_Klinik_Summary.pdf");
        document.getElementById('loading-overlay').classList.add('d-none');
    }

</script>
@endpush
@endsection
