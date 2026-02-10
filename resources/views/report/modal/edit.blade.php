@foreach ($data as $dataItem)
@php
// Cek status approve global. Jika 1, maka seluruh data dikunci
$isGlobalLocked = $dataItem->approve == '1';
@endphp
<!-- Modal Input Saldo Awal -->
<div class="modal fade" id="inputSaldo{{ $dataItem->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="inputSaldoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            {{-- Sesuaikan route dengan controller Anda untuk menyimpan saldo awal --}}
            <form action="{{ route('report-clinic.update', $dataItem->id) }}" method="POST" class="d-flex flex-column h-100">
                @csrf
                @method('PUT')

                <!-- Header -->
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-white flex-shrink-0">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="modal-title fw-bold text-dark ls-tight" id="inputSaldoLabel">
                                    <i class='bx bx-wallet text-primary me-2'></i>Input Saldo Awal
                                </h4>
                                <p class="text-muted small mb-2">Tetapkan anggaran operasional bulanan untuk Tahun {{ $dataItem->tahun ?? '-' }}</p>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                @if($isGlobalLocked)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">
                                    <i class='bx bx-lock-alt me-1'></i> Approved
                                </span>
                                @endif
                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        </div>

                        <!-- Info Strip (Context Data) -->
                        <div class="d-flex flex-wrap gap-2 mt-2 pb-3 border-bottom">
                            <div class="badge bg-light text-secondary border fw-normal px-3 py-2 d-flex align-items-center rounded-pill">
                                <i class='bx bx-building-house text-primary me-2'></i>
                                {{ Str::upper($dataItem->branch->nama_branch ?? '-') }}
                            </div>
                            <div class="badge bg-light text-secondary border fw-normal px-3 py-2 d-flex align-items-center rounded-pill">
                                <i class='bx bx-category text-primary me-2'></i>
                                {{ Str::upper($dataItem->item->kategori->kategori ?? '-') }}
                            </div>
                            <div class="badge bg-light text-secondary border fw-normal px-3 py-2 d-flex align-items-center rounded-pill">
                                <i class='bx bx-box text-primary me-2'></i>
                                {{ Str::upper($dataItem->item->item ?? '-') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Body (Scrollable Inputs) -->
                <div class="modal-body px-4 pt-4 bg-light bg-opacity-10 overflow-y-auto">

                    @php
                    $months = [
                    'januari', 'februari', 'maret', 'april', 'mei', 'juni',
                    'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
                    ];
                    @endphp

                    @if($isGlobalLocked)
                    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center" role="alert">
                        <i class='bx bx-info-circle fs-4 me-2'></i>
                        <div>
                            <strong>Data Terkunci!</strong> Anggaran ini telah disetujui secara keseluruhan dan tidak dapat diubah lagi.
                        </div>
                    </div>
                    @endif

                    <div class="container-fluid px-0">
                        <!-- Grid Layout: 1 Kolom di Mobile, 3 Kolom di Desktop -->
                        <div class="row g-4">
                            @foreach ($months as $month)
                            @php
                            // Cek apakah bulan ini sudah diverifikasi
                            $verifCol = $month . '_verif_by';
                            $isMonthVerified = !empty($dataItem->$verifCol);

                            // Input dikunci jika Global Locked ATAU Bulan ini Verified
                            $isInputLocked = $isGlobalLocked || $isMonthVerified;
                            @endphp

                            <div class="col-12 col-md-4">
                                <div class="card border-0 shadow-sm h-100 rounded-3 {{ !$isInputLocked ? 'hover-scale-sm' : '' }} transition-all">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="{{ $month }}_{{ $dataItem->id }}" class="form-label fw-bold text-capitalize text-secondary mb-0">
                                                {{ $month }}
                                                @if($isMonthVerified && !$isGlobalLocked)
                                                <i class='bx bx-check-shield text-success ms-1' data-bs-toggle="tooltip" title="Bulan ini sudah diverifikasi"></i>
                                                @endif
                                            </label>
                                            <!-- Hiasan visual bulan -->
                                            <span class="badge {{ $isMonthVerified ? 'bg-success bg-opacity-10 text-success' : 'bg-primary bg-opacity-10 text-primary' }} rounded-circle" style="width: 24px; height: 24px; display:flex; align-items:center; justify-content:center; font-size: 0.6rem;">
                                                {{ substr(strtoupper($month), 0, 3) }}
                                            </span>
                                        </div>

                                        <div class="input-group align-items-center border rounded-2 {{ $isInputLocked ? 'bg-light' : 'bg-white' }} overflow-hidden {{ !$isInputLocked ? 'focus-within-ring' : '' }}">
                                            <span class="input-group-text {{ $isInputLocked ? 'bg-light' : 'bg-white' }} border-0 text-muted pe-1 ps-3">Rp</span>
                                            <input type="number" id="{{ $month }}_{{ $dataItem->id }}" name="{{ $month }}" class="form-control border-0 shadow-none ps-1 fw-semibold input-rkap text-dark {{ $isInputLocked ? 'bg-light' : '' }}" value="{{ $dataItem->$month }}" placeholder="0" min="0" {{ $isInputLocked ? 'readonly' : '' }}>

                                            {{-- Jika locked karena verified, tampilkan icon gembok kecil di dalam input --}}
                                            @if($isInputLocked)
                                            <span class="input-group-text bg-light border-0 text-muted px-2">
                                                <i class='bx bx-lock-alt font-size-12'></i>
                                            </span>
                                            @endif
                                        </div>

                                        @if($isMonthVerified && !$isGlobalLocked)
                                        <div class="mt-1">
                                            <small class="text-success fst-italic" style="font-size: 0.7rem;">
                                                *Terkunci (Sudah Diverifikasi)
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer (Total Calculation) -->
                <div class="modal-footer border-top-0 px-4 pb-4 pt-3 bg-white flex-shrink-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between w-100 align-items-center gap-3">
                        <div class="w-100 w-md-auto text-center text-md-start">
                            <span class="text-muted small text-uppercase fw-bold d-block">Total Anggaran (Setahun)</span>
                            <span class="fs-4 fw-bold text-primary font-monospace total-rkap-display">
                                Rp {{ number_format($dataItem->total ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex gap-2 w-100 w-md-auto">
                            <button type="button" class="btn btn-light text-muted fw-bold rounded-pill px-4 flex-grow-1 flex-md-grow-0" data-bs-dismiss="modal">Tutup</button>

                            {{-- Tombol simpan hanya muncul jika TIDAK dikunci secara global --}}
                            @if(!$isGlobalLocked)
                            <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-pill px-5 flex-grow-1 flex-md-grow-0">
                                <i class='bx bx-save me-1'></i> Simpan Saldo
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    /* Utility Class untuk efek hover */
    .hover-scale-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
    }

    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    /* Efek focus pada input group wrapper */
    .focus-within-ring:focus-within {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .font-size-12 {
        font-size: 12px;
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function calculate total RKAP
        function calculateTotalRKAP(modal) {
            let inputs = modal.querySelectorAll('.input-rkap');
            let total = 0;

            inputs.forEach(function(input) {
                total += parseFloat(input.value) || 0;
            });

            let formatted = new Intl.NumberFormat('id-ID').format(total);
            let totalElement = modal.querySelector('.total-rkap-display');
            if (totalElement) {
                totalElement.textContent = 'Rp ' + formatted;
            }
        }

        // 1. Initial Calculation on Load
        document.querySelectorAll('.modal').forEach(function(modal) {
            // Cek apakah ini modal input saldo (bukan modal verifikasi)
            if (modal.id.startsWith('inputSaldo')) {
                calculateTotalRKAP(modal);
            }
        });

        // 2. Real-time Calculation on Input
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('input-rkap')) {
                let modal = e.target.closest('.modal');
                if (modal) {
                    calculateTotalRKAP(modal);
                }
            }
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });

</script>
