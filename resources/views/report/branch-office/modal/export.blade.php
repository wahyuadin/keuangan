<div class="modal fade" id="modalExportBranch" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">
                    <i class='bx bx-cloud-download'></i> Export Laporan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formExport" method="GET" action="{{ route('export.branch') }}">
                <div class="modal-body">

                    {{-- BRANCH --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Branch</label>
                        <select name="branch_id" class="form-select" required>
                            <option value="">-- Pilih Branch --</option>
                            @if(auth()->user()->branch_id == null)
                            @foreach(\App\Models\BranchOffice::orderBy('nama_branch')->get() as $b)
                            <option value="{{ $b->id }}">
                                {{ strtoupper($b->nama_branch) }}
                            </option>
                            @endforeach
                            @else
                            <option value="{{ auth()->user()->branch_id }}" selected>
                                {{ strtoupper(auth()->user()->branch->nama_branch) }}
                            </option>
                            @endif
                        </select>
                    </div>

                    {{-- BULAN RANGE --}}
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bulan Awal</label>
                            <select name="bulan_awal" class="form-select select2-modal" required>
                                @foreach(['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'] as $m)
                                <option value="{{ $m }}">{{ ucfirst($m) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bulan Akhir</label>
                            <select name="bulan_akhir" class="form-select select2-modal" required>
                                @foreach([
                                'januari','februari','maret','april','mei','juni',
                                'juli','agustus','september','oktober','november','desember'
                                ] as $m)
                                <option value="{{ $m }}" {{ $m == 'desember' ? 'selected' : '' }}>
                                    {{ ucfirst($m) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- TAHUN --}}
                    <div class="mt-3">
                        <label class="form-label fw-bold">Tahun</label>
                        <select name="tahun" class="form-select select2-modal" required>
                            @for($i = date('Y'); $i >= 2023; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-secondary">
                        <i class='bx bx-download'></i> Export Excel
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
