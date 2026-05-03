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
                <div class="col-12 col-md-10">
                    <h5 class="card-title">Daftar Bug Report</h5>
                    <p style="text-align: justify">Jika ada kritik, saran, atau error pada program, mohon kirim <b>Screenshot</b> yang jelas agar kami bisa segera memperbaikinya. <br>Terimakasih <i class='bx bx-happy-heart-eyes'></i></p>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <div class="mt-3 mb-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addbug">
                        <i class='bx bx-plus'></i>
                    </button>
                </div>
                <table id="example" class="table table-striped table-bordered w-100 mt-3">
                    @include('alert')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            @if (Auth::user()->role == '2')
                            <th>Pelapor</th>
                            <th>Tanggal</th>
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $dataItem)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ Str::upper($dataItem->deskripsi ?? '-') }}</td>
                            <td>
                                @if($dataItem->foto)
                                <a href="{{ asset('storage/' . $dataItem->foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $dataItem->foto) }}" alt="{{ $dataItem->foto ?? 'img' }}" style="max-width: 100px; max-height: 100px;">
                                </a>
                                @else
                                -
                                @endif
                            </td>

                            <td>
                                @if($dataItem->status == 1)
                                <span class="badge bg-success">Solved</span>
                                @elseif($dataItem->status == 0)
                                <span class="badge bg-primary">Progress</span>
                                @elseif($dataItem->status == 2)
                                <span class="badge bg-danger">Rejected</span>
                                @else
                                <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            @if (Auth::user()->role == '2')
                            <td>
                                {{ $dataItem->pelapor ?? 'Error' }}
                            </td>
                            <td>
                                {{ $dataItem->created_at ?? 'Error' }}
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form action="{{ route('bug-report-accept', $dataItem->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class='bx bx-check'></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('bug-report-reject', $dataItem->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-warning">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deletebug{{ $dataItem->id }}">
                                        <i class='bx bx-trash'></i>
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
@include('bug-report.modal.add')
@include('bug-report.modal.delete')
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

</script>
@endpush
@endsection
