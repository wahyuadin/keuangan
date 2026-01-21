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
                    <h5 class="card-title">Data User</h5>
                </div>
            </div>
            <form id="bulkDeleteForm" method="POST" action="{{ route('user-data.bulk-delete') }}">
                @csrf
                @method('DELETE')
                <div class="table-responsive mt-3">
                    <div class="mt-3 mb-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addData"><i class='bx bx-plus'></i>
                        </button>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importData"><i class='bx bx-import'></i>
                        </button>
                        <button type="submit" id="bulkDeleteBtn" class="btn btn-danger d-none" onclick="return confirm('Are you sure you want to delete selected users?')">
                            <i class="bx bx-trash"></i>
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
                                <th style="width : 5%">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th style="width : 5%">No</th>
                                <th>Username</th>
                                <th>Is Active</th>
                                <th>Role</th>
                                <th>Perusahaan</th>
                                <th>Klinik</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $datas)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox" name="selected_ids[]" value="{{ $datas->id }}">
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $datas->username ?? '-' }}</td>
                                <td>
                                    @if (isset($datas->is_active))
                                    @if ($datas->is_active == 1)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Tidak Active</span>
                                    @endif
                                    @else
                                    <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if (isset($datas->role))
                                    @if ($datas->role == 0)
                                    <span class="badge bg-primary">HRD</span>
                                    @elseif($datas->role == 1)
                                    <span class="badge bg-info">Admin Klinik</span>
                                    @elseif($datas->role == 2)
                                    <span class="badge bg-danger">Super Admin</span>
                                    @else
                                    <span class="badge bg-secondary">{{ $datas->role }}</span>
                                    @endif
                                    @else
                                    <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ Str::upper($datas->customer->nama_perusahaan ?? '-') ?? '-' }}
                                </td>
                                <td>
                                    {{ Str::upper($datas->clinic->nama_klinik ?? '-') ?? '-' }}
                                </td>
                                <td>
                                    @if ($datas->avatar != 'default.png')
                                    <img src="{{ asset('storage/' . $datas->avatar) }}" alt="User avatar" class="rounded" style="max-width: 50px;">
                                    @else
                                    <img src="{{ asset('assets/profile/default.png') }}" alt="No Photo" class="rounded" style="max-width: 50px;">
                                    @endif
                                </td>
                                <td class="d-flex flex-column flex-sm-row gap-2">
                                    @if ($datas->role != 2)
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editData{{ $datas->id }}" class="btn btn-warning btn-sm bx bx-edit" title="Edit">
                                    </button>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#hapusData{{ $datas->id }}" class="btn btn-danger btn-sm bx bx-trash" title="Edit">
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </form>
        </div>
    </div>
</div>
</div>
@include('user-data.add')
@include('user-data.edit')
@include('user-data.import')
@include('user-data.delete')
@push('style')
{{-- datatable --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
@endpush

@push('scripts')
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
        lengthMenu: [
            [10, 30, 50, 100, -1]
            , [10, 30, 50, 100, 'All']
        ]
        , columnDefs: [{
            targets: 0
            , orderable: false
            , searchable: false
            , className: 'text-center'
        }]
        , order: []
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

        let title = "DATA USER PT NAYAKA ERA HUSADA";
        let pageWidth = doc.internal.pageSize.width;
        let titleWidth = doc.getTextWidth(title);
        doc.text(title, (pageWidth - titleWidth) / 2, 10);

        // Ambil header
        let headers = [];
        $('#example thead th').each(function(index) {
            if (index < 7) { // Hanya ambil kolom yang relevan
                headers.push($(this).text().trim());
            }
        });

        // Ambil data dari tabel DOM
        let data = [];
        $('#example tbody tr:visible').each(function() {
            let rowData = [];
            $(this).find('td').each(function(index) {
                if (index < 7) { // Ambil hanya kolom yang relevan
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

        doc.save("Data_User.pdf");
        document.getElementById('loading-overlay').classList.add('d-none');
    }

    function exportExcel() {
        document.getElementById('loading-overlay').classList.remove('d-none');

        // Ambil header
        let headers = [];
        $('#example thead th').each(function(index) {
            if (index < 7) {
                headers.push($(this).text().trim());
            }
        });

        // Ambil data dari DOM
        let data = [];
        $('#example tbody tr:visible').each(function() {
            let rowData = [];
            $(this).find('td').each(function(index) {
                if (index < 7) {
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
        XLSX.utils.book_append_sheet(wb, ws, "Data User");

        XLSX.writeFile(wb, "Data_User.xlsx");
        document.getElementById('loading-overlay').classList.add('d-none');
    }

    function previewImageEdit(input) {
        const preview = input.closest('.mb-3').querySelector('img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePasswordEdit(button) {
        const input = button.closest('.input-group').querySelector('input');
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

</script>
<script>
    // Checkbox select all
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        toggleBulkDeleteBtn();
    });

    // Checkbox per row
    $(document).on('change', '.row-checkbox', function() {
        $('#selectAll').prop('checked', $('.row-checkbox:checked').length === $('.row-checkbox').length);
        toggleBulkDeleteBtn();
    });

    function toggleBulkDeleteBtn() {
        if ($('.row-checkbox:checked').length > 0) {
            $('#bulkDeleteBtn').removeClass('d-none');
        } else {
            $('#bulkDeleteBtn').addClass('d-none');
        }
    }

</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            dropdownParent: $('#addData')
            , placeholder: "--- Pilih Salah Satu ---"
            , theme: 'bootstrap-5'
            , maximumSelectionLength: 3
            , width: '100%'
        });
    });

    function toggleClinicField() {
        var role = $('#roleSelect').val();
        if (role == '0') {
            $('#perusahaanfield').show();
            $('#clinicfield').hide();
        } else if (role == '1') {
            $('#clinicfield').show();
            $('#perusahaanfield').hide();
        } else {
            $('#clinicfield').hide();
            $('#perusahaanfield').hide();
        }
    }

    $('#roleSelect').on('change', toggleClinicField);
    toggleClinicField();

    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function previewImage() {
        let input = document.getElementById("foto_marki");
        let preview = document.getElementById("preview");

        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove("d-none");
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
@endpush
@endsection
