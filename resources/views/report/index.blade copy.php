@extends('template.app')
@section('content')
<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!-- Start of Selection -->
<div class="container mt-4">
    <div class="card w-100">
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-8">
                    <h5 class="card-title">Data Report</h5>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <div class="mt-3 mb-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKategori">
                        <i class='bx bx-plus'></i>
                    </button>
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-export'></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="printPDF()">Print PDF</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="exportExcel()">Export
                                Excel</a>
                        </li>
                    </ul>
                </div>
                <table id="example" class="table table-striped table-bordered w-100 mt-3">
                    @include('alert')
                    <div class="row mb-3">
                        <h5>Periode</h5>
                        <div class="col-md-4">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select select2">
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" {{ request('bulan', date('n')) == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select select2">
                                @for ($y = date('Y'); $y >= 1945; $y--)
                                <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Approve</label>
                            <select name="approve" class="form-select select2">
                                <option value="" {{ request('approve') == '' ? 'selected' : '' }}>
                                    All
                                </option>
                                <option value="1" {{ request('approve') == '1' ? 'selected' : '' }}>
                                    Approve
                                </option>
                                <option value="0" {{ request('approve') == '0' ? 'selected' : '' }}>
                                    Not Approve
                                </option>
                            </select>
                        </div>
                    </div>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Branch Office</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Item</th>
                            <th>Penetapan RKAP</th>
                            <th>Januari</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Februari</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Maret</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>April</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Mei</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Juni</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Juli</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Agustus</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>September</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Oktober</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>November</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Desember</th>
                            <th>Verifed By</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                            <th>Total Saldo</th>
                            <th>Total Selisih</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        // Inisialisasi variabel Grand Total
                        $grand_total_saldo = 0;
                        $grand_total_selisih = 0;
                        @endphp

                        @foreach ($data as $index => $dataItem)

                        @php
                        $months = [
                        'januari', 'februari', 'maret', 'april', 'mei', 'juni',
                        'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
                        ];

                        $total_saldo = 0;
                        $total_selisih = 0;

                        foreach ($months as $month) {
                        // Hitung Total Saldo (RKAP)
                        $total_saldo += (float) ($dataItem->$month ?? 0);

                        // Hitung Total Selisih
                        // Asumsi kolom selisih bernama "bulan_selisih" misal "januari_selisih"
                        $selisih_col = $month . '_selisih';
                        $total_selisih += (float) ($dataItem->$selisih_col ?? 0);
                        }

                        // Tambahkan ke Grand Total
                        $grand_total_saldo += $total_saldo;
                        $grand_total_selisih += $total_selisih;
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ Str::upper($dataItem->branch->nama_branch ?? '-') }}</td>
                            <td>{{ Str::upper($dataItem->tahun ?? '-') }}</td>
                            <td>{{ Str::upper($dataItem->item->kategori->kategori ?? '-') }}</td>
                            <td>{{ Str::upper($dataItem->item->item ?? '-') }}</td>
                            @php
                            $user = \App\Models\User::where('id', $dataItem->create_by)->first();
                            @endphp
                            <td>{{ Str::upper('Tahap Pengembangan' ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->januari ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->januari_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->januari_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->januari_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->februari ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->februari_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->februari_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->februari_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->maret ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->maret_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->maret_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->maret_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->april ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->april_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->april_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->april_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->mei ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->mei_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->mei_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->mei_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->juni ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->juni_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->juni_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->juni_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->juli ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->juli_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->juli_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->juli_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->agustus ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->agustus_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->agustus_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->agustus_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->september ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->september_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->september_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->september_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->oktober ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->oktober_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->oktober_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->oktober_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->november ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->november_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->november_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->november_keterangan ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->desember ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->desember_verif_by ?? '-') }}</td>
                            <td>Rp {{ number_format($dataItem->desember_selisih ?? 0, 0, ',', '.') }}</td>
                            <td>{{ Str::upper($dataItem->desember_keterangan ?? '-') }}</td>

                            <!-- Menampilkan Total yang sudah dihitung -->
                            <td>Rp {{ number_format($total_saldo, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($total_selisih, 0, ',', '.') }}</td>

                            <td>{{ Str::upper($user->nama ?? '-') }}</td>
                            <td>{{ Str::upper($dataItem->updated_at ?? '-') }}</td>
                            @if(Auth::user()->role == '2')
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#inputSaldo{{ $dataItem->id }}">
                                        Saldo
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#verifReport{{ $dataItem->id }}">
                                        Verif
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReport{{ $dataItem->id }}">
                                        Delete
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-active fw-bold">
                            <!-- Colspan 54 columns (6 awal + 12 bulan * 4) untuk sampai ke kolom Total Saldo -->
                            <td colspan="54" class="text-end text-uppercase">Total Keseluruhan</td>
                            <td class="text-nowrap">Rp {{ number_format($grand_total_saldo, 0, ',', '.') }}</td>
                            <td class="text-nowrap">Rp {{ number_format($grand_total_selisih, 0, ',', '.') }}</td>
                            <!-- Sisa kolom setelah total (Created By, Created At, Action) -->
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
{{-- datatable --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
@endpush

@push('scripts')
{{-- Moment.js for date handling --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
{{-- DataTables JS --}}
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>

{{-- Library untuk Export Excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

{{-- Library untuk Export PDF --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<script>
    new DataTable('#example', {
        processing: true
    });

    $('.select2').each(function() {
        $(this).select2({
            placeholder: "Pilih Salah Satu..."
            , theme: "bootstrap-5"
            , allowClear: true
            // , dropdownParent: $(this).closest('.modal')
        });
    });

    function ucwordsJS(str) {
        return str
            .replace(/_/g, ' ') // ganti underscore jadi spasi
            .toLowerCase()
            .replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
    }

    function printPDF() {
        document.getElementById('loading-overlay').classList.remove('d-none');

        const {
            jsPDF
        } = window.jspdf;
        let doc = new jsPDF();

        let title = "DATA DAFTAR KUNJUNGAN PT NAYAKA ERA HUSADA";
        let pageWidth = doc.internal.pageSize.width;
        let titleWidth = doc.getTextWidth(title);
        doc.text(title, (pageWidth - titleWidth) / 2, 10);

        // Ambil header
        let headers = [];
        $('#example thead th').each(function(index) {
            if (index < 8) { // Hanya ambil kolom yang relevan
                headers.push($(this).text().trim());
            }
        });

        // Ambil data dari tabel DOM
        let data = [];
        $('#example tbody tr:visible').each(function() {
            let rowData = [];
            $(this).find('td').each(function(index) {
                if (index < 8) { // Ambil hanya kolom yang relevan
                    let text = $(this).text().trim();
                    rowData.push(ucwordsJS(text));

                }
            });
            data.push(rowData);
        });

        // Buat tabel PDF
        doc.autoTable({
            head: [headers]
            , body: data
            , startY: 20
            , theme: "striped"
            , styles: {
                fontSize: 8
                , textColor: [0, 0, 0]
            }
            , headStyles: {
                fillColor: [192, 192, 192]
                , textColor: [0, 0, 0]
            }
        , });

        doc.save("Dafta_Daftar_Kunjungan.pdf");
        document.getElementById('loading-overlay').classList.add('d-none');
    }

    function exportExcel() {
        document.getElementById('loading-overlay').classList.remove('d-none');

        // Ambil header
        let headers = [];
        $('#example thead th').each(function(index) {
            if (index < 4) {
                headers.push($(this).text().trim());
            }
        });

        // Ambil data dari DOM
        let data = [];
        $('#example tbody tr:visible').each(function() {
            let rowData = [];
            $(this).find('td').each(function(index) {
                if (index < 4) {
                    let text = $(this).text().trim();
                    rowData.push(ucwordsJS(text));
                }
            });
            data.push(rowData);
        });

        let ws_data = [headers, ...data];
        let ws = XLSX.utils.aoa_to_sheet(ws_data);

        // Hitung lebar kolom
        let colWidths = ws_data[0].map((_, colIndex) => {
            let maxWidth = 10;
            ws_data.forEach(row => {
                let cell = row[colIndex];
                if (cell && cell.length > maxWidth) {
                    maxWidth = cell.length;
                }
            });
            return {
                wch: maxWidth + 2
            };
        });
        ws['!cols'] = colWidths;

        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Kategori");

        XLSX.writeFile(wb, "Kategori.xlsx");
        document.getElementById('loading-overlay').classList.add('d-none');
    }

</script>
@endpush
@endsection
