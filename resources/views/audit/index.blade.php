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
                    <h4 class="card-title">Data Audit</h4>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table id="example" class="table table-striped table-bordered w-100 mt-3">
                    @include('alert')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User Type</th>
                            <th>Nama User</th>
                            <th>event</th>
                            <th>auditable type</th>
                            <th>auditable id</th>
                            <th style="min-width: 500px;">iNPUTAN LAMA</th>
                            <th style="min-width: 500px;">iNPUTAN BARU</th>
                            <th>Link</th>
                            <th>IP Address</th>
                            <th style="min-width: 200px;"">Device</th>
                            <th>tag</th>
                            <th>Tgl Buat</th>
                            <th>Tgl update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $dataItem)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dataItem->user_type ?? '-' }}</td>
                            <td>
                                @php
                                    $user = \App\Models\User::where('id', $dataItem->user_id)->first();
                                @endphp
                                {{ $user->nama ?? '-' }}
                            </td>
                            <td>
                                {{ ucwords($dataItem->event ?? '-') }}
                            </td>
                            <td>
                                {{ $dataItem->auditable_type ?? '-' }}
                            </td>
                            <td>
                                {{ $dataItem->auditable_id ?? '-' }}
                            </td>
                            <td>
                                @if($dataItem->old_values)
                                @php $old = json_decode($dataItem->old_values, true); @endphp
                                <ul class=" list-unstyled mb-0">
                                @foreach($old as $key => $value)
                                <li><strong>{{ ucfirst($key) }}:</strong> {{ $value ?? '-' }}</li>
                                @endforeach
                                </ul>
                                @else
                                -
                                @endif
                                </td>
                            <td>
                                @if($dataItem->new_values)
                                @php $new = json_decode($dataItem->new_values, true); @endphp
                                <ul class="list-unstyled mb-0">
                                    @foreach($new as $key => $value)
                                    <li><strong>{{ ucfirst($key) }}:</strong> {{ $value ?? '-' }}</li>
                                    @endforeach
                                </ul>
                                @else
                                -
                                @endif
                            </td>

                            <td>
                                {{ $dataItem->url ?? '-' }}
                            </td>
                            <td>
                                {{ $dataItem->ip_address ?? '-' }}
                            </td>
                            <td>
                                {{ $dataItem->user_agent ?? '-' }}
                            </td>
                            <td>
                                {{ $dataItem->tags ?? null }}
                            </td>
                            <td>
                                {{ $dataItem->created_at ?? null }}
                            </td>
                            <td>
                                {{ $dataItem->updated_at ?? null }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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
        , serverSide: false
        , lengthMenu: [1, 3, 5, 10, 25, 50, 100]
        , pageLength: 1
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
