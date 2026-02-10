@foreach ($data as $dataVerif)
<!-- Modal Verifikasi -->
<div class="modal fade" id="verifReport{{ $dataVerif->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            {{-- Form dengan layout flexbox untuk mendukung scrolling --}}
            <form action="{{ route('report-clinic.update', $dataVerif->id) }}" method="POST" class="d-flex flex-column h-100">
                @csrf
                @method('PUT')

                <!-- Header (Sticky/Fixed di atas) -->
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-white flex-shrink-0">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="modal-title fw-bold text-dark ls-tight" id="addItemLabel">
                                    Verifikasi Data
                                </h4>
                                <p class="text-muted small mb-2">Periode Anggaran {{ $dataVerif->tahun ?? '-' }}</p>
                            </div>
                            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Info Strip -->
                        <div class="d-flex flex-wrap gap-2 mt-2 pb-3 border-bottom">
                            <div class="badge bg-light text-secondary border fw-normal px-3 py-2 d-flex align-items-center rounded-pill">
                                <i class='bx bx-building-house text-primary me-2'></i>
                                {{ Str::upper($dataVerif->branch->nama_branch ?? '-') }}
                            </div>
                            <div class="badge bg-light text-secondary border fw-normal px-3 py-2 d-flex align-items-center rounded-pill">
                                <i class='bx bx-category text-primary me-2'></i>
                                {{ Str::upper($dataVerif->item->kategori->kategori ?? '-') }}
                            </div>
                            <div class="badge bg-light text-secondary border fw-normal px-3 py-2 d-flex align-items-center rounded-pill">
                                <i class='bx bx-box text-primary me-2'></i>
                                {{ Str::upper($dataVerif->item->item ?? '-') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Body (Area yang bisa di-scroll) -->
                <div class="modal-body px-0 pt-0 bg-light bg-opacity-10 overflow-y-auto">

                    @php
                    $months = [
                    'januari', 'februari', 'maret', 'april', 'mei', 'juni',
                    'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
                    ];
                    @endphp

                    <!-- Container Data -->
                    <div class="container-fluid px-0">

                        <!-- HEADER TABLE (Hanya muncul di Desktop) -->
                        <div class="d-none d-md-flex row mx-0 bg-light border-bottom sticky-top py-3 px-3 fw-bold text-secondary small text-uppercase" style="z-index: 10;">
                            <div class="col-md-3">Bulan & Status</div>
                            <div class="col-md-2 text-end">Nominal</div>
                            <div class="col-md-3 ps-4">Input Selisih</div>
                            <div class="col-md-3">Keterangan</div>
                            <div class="col-md-1 text-center">Verif</div>
                        </div>

                        <!-- LOOP DATA (Responsive Grid: Row di Desktop, Stack di Mobile) -->
                        @foreach ($months as $month)
                        @php
                        $verif_by_col = $month . '_verif_by';
                        $selisih_col = $month . '_selisih';
                        $ket_col = $month . '_keterangan';
                        $rkap_col = $month;
                        $isVerified = !empty($dataVerif->$verif_by_col);
                        $verifierName = $dataVerif->$verif_by_col ?? '-';
                        @endphp

                        <!-- Wrapper Item -->
                        <div class="row mx-0 border-bottom py-3 px-3 align-items-center bg-white hover-bg-light">

                            <!-- Kolom 1: Bulan & Status -->
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <div class="d-flex justify-content-between align-items-center d-md-block">
                                    <div class="fw-bold text-capitalize text-dark d-flex align-items-center">
                                        <!-- Icon Bulat hanya di Mobile -->
                                        <div class="d-md-none bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 0.7rem;">
                                            {{ substr(strtoupper($month), 0, 3) }}
                                        </div>
                                        {{ $month }}
                                    </div>

                                    <!-- Status Mobile (di kanan bulan) -->
                                    @if($isVerified)
                                    <div class="d-md-none badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill">
                                        <i class='bx bx-check-double'></i> {{ Str::limit($verifierName, 8) }}
                                    </div>
                                    @endif
                                </div>

                                <!-- Status Desktop (di bawah bulan) -->
                                <div class="d-none d-md-block mt-1">
                                    @if($isVerified)
                                    <div class="text-success small d-flex align-items-center">
                                        <i class='bx bx-check-double me-1'></i>
                                        <span>{{ Str::limit($verifierName, 12) }}</span>
                                    </div>
                                    @else
                                    <small class="text-muted fst-italic" style="font-size: 0.75rem;">Pending</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Kolom 2: RKAP -->
                            <div class="col-6 col-md-2 mb-2 mb-md-0 text-md-end">
                                <small class="d-md-none text-muted fw-bold x-small d-block mb-1">NILAI RKAP</small>
                                <span class="font-monospace text-secondary fw-semibold">
                                    Rp. {{ number_format($dataVerif->$rkap_col ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Kolom 3: Input Selisih -->
                            <div class="col-6 col-md-3 mb-2 mb-md-0 ps-md-4">
                                <small class="d-md-none text-muted fw-bold x-small d-block mb-1">SELISIH</small>
                                <!-- FIX: Added align-items-center to fix "Rp" alignment issue -->
                                <div class="input-group input-group-sm align-items-center">
                                    <span class="input-group-text bg-white text-muted border-end-0" style="height: 100%;">Rp</span>
                                    <input type="number" name="{{ $month }}_selisih" class="form-control border-start-0 shadow-none ps-1 input-selisih" value="{{ $dataVerif->$selisih_col }}" placeholder="0">
                                </div>
                            </div>

                            <!-- Kolom 4: Keterangan -->
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <small class="d-md-none text-muted fw-bold x-small d-block mb-1">KETERANGAN</small>
                                <input type="text" name="{{ $month }}_keterangan" class="form-control form-control-sm shadow-none border-light-subtle rounded-2" value="{{ $dataVerif->$ket_col }}" placeholder="Catatan...">
                            </div>

                            <!-- Kolom 5: Action (Verif) -->
                            <div class="col-12 col-md-1 text-center mt-2 mt-md-0 border-top border-md-top-0 pt-2 pt-md-0">
                                <div class="d-flex justify-content-between justify-content-md-center align-items-center">
                                    <span class="d-md-none fw-bold small text-dark">Verifikasi?</span>

                                    <!-- Checkbox: Value adalah Nama User yang sedang login -->
                                    @if(!$isVerified)
                                    <div class="form-check form-switch d-md-none">
                                        <input class="form-check-input" type="checkbox" role="switch" name="{{ $month }}_verif_by" value="{{ auth()->user()->nama ?? auth()->user()->name }}" {{ $isVerified ? 'checked disabled' : '' }}>
                                    </div>

                                    <div class="form-check d-none d-md-block">
                                        <input class="form-check-input shadow-none border-secondary" type="checkbox" name="{{ $month }}_verif_by" value="{{ auth()->user()->nama ?? auth()->user()->name }}" style="width: 1.3em; height: 1.3em; cursor: pointer;" {{ $isVerified ? 'checked disabled' : '' }}>
                                    </div>
                                    @else
                                    <!-- Jika sudah verif, tampilkan icon checked (non-interactive) -->
                                    <i class='bx bx-check-circle text-success fs-4'></i>
                                    @endif
                                </div>
                            </div>

                        </div>
                        @endforeach

                    </div>
                </div>

                <!-- Footer Floating/Sticky -->
                <div class="modal-footer border-top-0 px-4 pb-4 pt-3 bg-white flex-shrink-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between w-100 align-items-center gap-12">
                        <div class="d-none d-md-block">
                            <span class="text-muted small">Total Selisih Akumulasi:</span>
                            <!-- Value will be updated by JS on load -->
                            <span class="fw-bold text-dark font-monospace ms-1 total-accumulated-display">Rp {{ number_format($dataVerif->total_selisih ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex gap-2 w-100 w-md-auto">
                            <button type="button" class="btn btn-light text-muted fw-bold rounded-pill px-4 flex-grow-1 flex-md-grow-0" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-pill px-5 flex-grow-1 flex-md-grow-0">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function untuk menghitung total dalam modal tertentu
        function calculateTotalInModal(modal) {
            let inputs = modal.querySelectorAll('.input-selisih');
            let total = 0;

            inputs.forEach(function(input) {
                total += parseFloat(input.value) || 0;
            });

            let formatted = new Intl.NumberFormat('id-ID').format(total);
            let totalElement = modal.querySelector('.total-accumulated-display');
            if (totalElement) {
                totalElement.textContent = 'Rp ' + formatted;
            }
        }

        // 1. Hitung saat halaman baru diload (Initial Calculation)
        document.querySelectorAll('.modal').forEach(function(modal) {
            calculateTotalInModal(modal);
        });

        // 2. Hitung saat user mengetik (Real-time Calculation)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('input-selisih')) {
                let modal = e.target.closest('.modal');
                if (modal) {
                    calculateTotalInModal(modal);
                }
            }
        });
    });

</script>
