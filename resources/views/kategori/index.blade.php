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
                    <h5 class="card-title">Data Daftar Kategori</h5>
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
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Update By</th>
                            <th>Created At</th>
                            @if(Auth::user()->role == '2')
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $dataItem)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ Str::upper($dataItem->kategori ?? '-') }}</td>
                            @php
                            $user = \App\Models\User::where('id', $dataItem->create_by)->first();
                            @endphp
                            <td>{{ Str::upper($user->nama ?? '-') }}</td>
                            <td>{{ Str::upper($dataItem->updated_at ?? '-') }}</td>
                            @if(Auth::user()->role == '2')
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#edit-kategori{{ $dataItem->id }}">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-kategori{{ $dataItem->id }}">
                                        Delete
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('kategori.modal.add')
@include('kategori.modal.edit')
@include('kategori.modal.delete')
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
            placeholder: "Cari atau pilih peserta..."
            , theme: "bootstrap-5"
            , allowClear: true
            , dropdownParent: $(this).closest('.modal')
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
