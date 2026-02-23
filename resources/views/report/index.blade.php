@extends('template.app')
@section('content')
@push('style')
<style>
    /* Styling Header Tabel - Menggunakan warna cerah */
    th {
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
        background-color: #f1f5f9 !important;
        /* Warna Slate Terang */
        color: #1e293b !important;
        /* Warna Slate Gelap untuk teks */
        border-bottom: 2px solid #cbd5e1;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
    }

    td {
        white-space: nowrap;
        vertical-align: middle;
        color: #334155;
    }

    tr:hover td {
        background-color: #f8fafc !important;
    }

    /* Penyesuaian DataTables Scroll */
    .dataTables_scrollHeadInner,
    .table {
        width: 100% !important;
    }

    .btn-icon {
        padding: 0.4rem;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    /* Custom Scrollbar for Table */
    .dataTables_scrollBody::-webkit-scrollbar {
        height: 8px;
    }

    .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Custom Header Warna Cerah untuk Akumulasi */
    .header-akumulasi {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }

</style>
@endpush

<!-- Loading Overlay -->
<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="text-center">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="mt-2 fw-bold text-primary">Memproses Data...</div>
    </div>
</div>

<div class="container-fluid mt-4">
    <div class="card shadow-sm border-0 w-100" style="border-radius: 1rem; background-color: #ffffff;">
        <div class="card-body p-4">
            <!-- Header & Tombol Tambah -->
            <div class="row align-items-center mb-4">
                <div class="col-md-7">
                    <h4 class="fw-bold mb-1 text-dark"><i class='bx bx-bar-chart-square text-primary me-2'></i>Monitoring Report Klinik</h4>
                    <p class="text-muted mb-0">Pemantauan realisasi anggaran dan verifikasi keuangan unit klinik.</p>
                </div>
                <div class="col-md-5 text-end d-flex justify-content-end gap-2">
                    @if(Auth::user()->role == '2') {{-- Role PPK / HUB --}}
                    <button type="button" class="btn btn-primary shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addReportPPK">
                        <i class='bx bx-plus me-1'></i> Anggaran
                    </button>
                    <button type="button" class="btn btn-info text-white shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addReportKeu">
                        <i class='bx bx-check-double me-1'></i> Realisasi
                    </button>
                    @endif
                </div>
            </div>

            <!-- Toolbar & Filter Section -->
            <div class="border rounded-4 p-3 mb-4 bg-light shadow-sm border-0">
                <form method="GET" class="row g-3 align-items-end">

                    {{-- BRANCH --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold small text-secondary">Branch</label>
                        <select name="branch_id" id="filter_branch" class="form-select">
                            <option value="">-- Semua Branch --</option>
                            @foreach(\App\Models\BranchOffice::orderBy('nama_branch')->get() as $b)
                            <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
                                {{ strtoupper($b->nama_branch) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- KLINIK --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold small text-secondary">Klinik</label>
                        <select name="clinic_id" id="filter_clinic" class="form-select">
                            <option value="">-- Semua Klinik --</option>
                        </select>
                    </div>

                    {{-- TAHUN --}}
                    <div class="col-12 col-md-2">
                        <label class="form-label fw-semibold small text-secondary">Tahun</label>
                        <select name="tahun" class="form-select">
                            <option value="">-- Semua Tahun --</option>
                            @for($i = date('Y'); $i >= 2023; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                            @endfor
                        </select>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="col-12 col-md-4">
                        <div class="d-flex flex-column flex-md-row gap-2">

                            <button type="button" class="btn btn-secondary flex-fill" data-bs-toggle="modal" data-bs-target="#modalExport">
                                <i class='bx bx-cloud-download me-1'></i> Export
                            </button>

                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class='bx bx-search me-1'></i> Filter
                            </button>

                        </div>
                    </div>

                </form>
            </div>

            <!-- Tabel Monitoring -->
            <div class="table-responsive">
                <table id="tableReport" class="table table-hover align-middle w-100" style="font-size: 0.825rem; border: 1px solid #e2e8f0;">
                    @include('alert')

                    @php
                    $listBulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
                    @endphp

                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" style="width: 40px;">No</th>
                            <th rowspan="2">Klinik</th>
                            <th rowspan="2">Branch</th>
                            <th rowspan="2" class="text-center">Tahun</th>
                            <th rowspan="2">Item Anggaran</th>
                            <th rowspan="2" class="text-end">RKAP</th>

                            @foreach ($listBulan as $bln)
                            <th colspan="4" class="text-center border-start" style="background-color: #f8fafc !important;">{{ Str::limit(Str::ucfirst($bln), 3, '') }}</th>
                            @endforeach

                            <th colspan="3" class="text-center border-start header-akumulasi">Total Akumulasi</th>
                            <th rowspan="2" class="text-center">Aksi</th>
                        </tr>
                        <tr>
                            @foreach ($listBulan as $bln)
                            <th class="border-start small" style="background-color: #ffffff !important; font-weight: 600;">Angg</th>
                            <th style="background-color: #ffffff !important; font-weight: 600;">Real</th>
                            <th style="background-color: #ffffff !important; font-weight: 600;">Slsh</th>
                            <th style="background-color: #ffffff !important; font-weight: 600;" class="text-center">Stts</th>
                            @endforeach
                            <th class="border-start small" style="background-color: #f1f5f9 !important; font-weight: 700;">Angg</th>
                            <th style="background-color: #f1f5f9 !important; font-weight: 700;">Real</th>
                            <th style="background-color: #f1f5f9 !important; font-weight: 700;">Slsh</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                        $grand_angg = 0; $grand_real = 0; $grand_slsh = 0;
                        @endphp

                        @foreach ($data as $idx => $item)
                        @php
                        $row_angg = 0; $row_real = 0;
                        foreach($listBulan as $m) {
                        $v_angg = (float)str_replace(['Rp', '.', ','], ['', '', ''], $item->$m ?? 0);
                        $v_real = (float)str_replace(['Rp', '.', ','], ['', '', ''], $item->{$m.'_realisasi'} ?? 0);
                        $row_angg += $v_angg;
                        $row_real += $v_real;
                        }
                        $row_slsh = $row_angg - $row_real;
                        $grand_angg += $row_angg; $grand_real += $row_real; $grand_slsh += $row_slsh;
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td class="fw-bold text-dark">{{ strtoupper($item->clinic->nama_klinik ?? '-') }}</td>
                            <td><span class="badge bg-light text-primary border fw-normal">{{ strtoupper($item->clinic->branch->nama_branch ?? '-') }}</span></td>
                            <td class="text-center">{{ $item->tahun }}</td>
                            <td>
                                <div style="max-width: 150px; overflow: hidden; text-overflow: ellipsis;" title="{{ strtoupper($item->item->item ?? '-') }}">{{ strtoupper($item->item->item ?? '-') }}</div>
                            </td>
                            <td class="text-end fw-semibold">Rp {{ number_format($item->sla->rkap ?? 0, 0, ',', '.') }}</td>

                            @foreach ($listBulan as $m)
                            @php
                            $a = (float)str_replace(['Rp', '.', ','], ['', '', ''], $item->$m ?? 0);
                            $r = (float)str_replace(['Rp', '.', ','], ['', '', ''], $item->{$m.'_realisasi'} ?? 0);
                            $s = $a - $r;
                            $is_v = $item->{$m.'_verif_by'} ? true : false;
                            @endphp
                            <td class="text-end border-start">Rp {{ number_format($a, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($r, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold {{ $s < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($s, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($is_v)
                                <span class="text-success" title="Terverifikasi oleh {{ $item->{$m.'_verif_by'} }}">
                                    <i class='bx bxs-badge-check fs-5'></i>
                                </span>
                                @else
                                <i class='bx bx-minus text-light'></i>
                                @endif
                            </td>
                            @endforeach

                            <td class="text-end fw-bold border-start bg-light text-dark">Rp {{ number_format($row_angg, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold bg-light text-dark">Rp {{ number_format($row_real, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold bg-light {{ $row_slsh < 0 ? 'text-danger' : 'text-primary' }}">
                                Rp {{ number_format($row_slsh, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteReport{{ $item->id }}" title="Hapus Data">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot style="background-color: #f8fafc; font-weight: 800; border-top: 2px solid #cbd5e1;">
                        <tr>
                            <td colspan="6" class="text-end text-dark">TOTAL KESELURUHAN (IDR)</td>
                            @foreach ($listBulan as $m)
                            <td colspan="4" class="border-start"></td>
                            @endforeach
                            <td class="text-end border-start text-dark">Rp {{ number_format($grand_angg, 0, ',', '.') }}</td>
                            <td class="text-end text-dark">Rp {{ number_format($grand_real, 0, ',', '.') }}</td>
                            <td class="text-end text-primary">Rp {{ number_format($grand_slsh, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Includes -->
@include('report.modal.ppk')
@include('report.modal.keucabang')
@include('report.modal.edit')
@include('report.modal.delete')
@include('report.modal.export')

@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* Mengatasi dropdown Select2 tertutup modal atau melayang salah posisi */
    .select2-container--open {
        z-index: 99999 !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px !important;
        border: 1px solid #dee2e6 !important;
    }

    .modal-body {
        overflow-x: hidden;
    }

</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(function() {
        const branchSelect = $('#filter_branch');
        const clinicSelect = $('#filter_clinic');
        const tahunSelect = $('select[name="tahun"]');

        branchSelect.add(clinicSelect).add(tahunSelect).select2({
            theme: 'bootstrap-5'
            , width: '100%'
            , placeholder: '-- Pilih --'
            , allowClear: true
        });

        $(document).on('shown.bs.modal', '.modal', function() {

            $(this).find('.select2-modal').each(function() {

                if (!$(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2({
                        theme: 'bootstrap-5'
                        , dropdownParent: $(this).closest('.modal')
                        , width: '100%'
                        , placeholder: '-- Pilih --'
                        , allowClear: true
                    });
                }

            });

        });

        branchSelect.on('change', function() {

            let branchId = $(this).val();

            clinicSelect.html('<option value="">Loading...</option>').trigger('change');

            if (!branchId) {
                clinicSelect.html('<option value="">-- Semua Klinik --</option>').trigger('change');
                return;
            }

            $.get("{{ route('server.branch-clinics', ':id') }}".replace(':id', branchId), function(data) {

                let options = '<option value="">-- Semua Klinik --</option>';

                data.forEach(function(clinic) {
                    options += `<option value="${clinic.id}">
                                ${clinic.nama_klinik.toUpperCase()}
                            </option>`;
                });

                clinicSelect.html(options).trigger('change');
            });

        });

        let selectedBranch = "{{ request('branch_id') }}";
        let selectedClinic = "{{ request('clinic_id') }}";

        if (selectedBranch) {

            $.get("{{ route('server.branch-clinics', ':id') }}".replace(':id', selectedBranch), function(data) {

                let options = '<option value="">-- Semua Klinik --</option>';

                data.forEach(function(clinic) {

                    let selected = clinic.id == selectedClinic ? 'selected' : '';

                    options += `<option value="${clinic.id}" ${selected}>
                                ${clinic.nama_klinik.toUpperCase()}
                            </option>`;
                });

                clinicSelect.html(options).trigger('change');
            });

        }

        $('#tableReport').DataTable({
            scrollX: true
            , scrollCollapse: true
            , paging: true
            , pageLength: 10
            , fixedColumns: {
                left: 2
            }
            , language: {
                search: ""
                , searchPlaceholder: "Cari report..."
                , lengthMenu: "_MENU_"
                , info: "Data _START_ - _END_ dari _TOTAL_"
                , paginate: {
                    first: "«"
                    , last: "»"
                    , next: "›"
                    , previous: "‹"
                }
            }
            , dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>'
        });

    });

    function exportExcel() {

        $('#loading-overlay').removeClass('d-none');

        let params = $('#filterForm').serialize();

        window.location.href = "{{ route('export.clinic') }}?" + params;

        setTimeout(() => {
            $('#loading-overlay').addClass('d-none');
        }, 2000);
    }

</script>
@endpush
@endsection
