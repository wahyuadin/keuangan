<div class="modal fade" id="addReportPPK" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class='bx bx-edit'></i> Input Anggaran (HUB PPK)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPPK" action="{{ route('report-clinic.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class='bx bx-info-circle'></i> <strong>Perhatian:</strong> Anda hanya dapat mengisi kolom Anggaran. Jika baris berwarna abu-abu dan terkunci (Tersimpan/Terverifikasi), maka data tidak dapat diubah kembali.
                    </div>

                    <div class="row mb-3">

                        {{-- BRANCH --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Branch</label>
                            <select id="ppk_id_branch" class="form-select select2-modal" required>
                                <option value="">-- Pilih Branch --</option>
                                @foreach(\App\Models\BranchOffice::orderBy('nama_branch')->get() as $b)
                                <option value="{{ $b->id }}">{{ strtoupper($b->nama_branch) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KLINIK --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Klinik</label>
                            <select name="clinic_id" id="ppk_id_clinic" class="form-select select2-modal" required>
                                <option value="">-- Pilih Branch Dahulu --</option>
                            </select>
                        </div>

                        {{-- ITEM --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Item Anggaran</label>
                            <select name="item_id" id="ppk_id_item" class="form-select select2-modal" required>
                                <option value="">-- Pilih Klinik Dahulu --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <select name="tahun" id="ppk_tahun" class="form-select" required>
                                @for($t = date('Y'); $t >= 2023; $t--)
                                <option value="{{ $t }}">{{ $t }}</option>
                                @endfor
                            </select>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th width="150">Bulan</th>
                                    <th>Anggaran (Rp)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'] as $m)
                                <tr id="row_ppk_{{ $m }}">
                                    <td class="align-middle fw-bold text-capitalize">{{ $m }}</td>
                                    <td>
                                        <input type="text" name="{{ $m }}" id="ppk_inp_{{ $m }}" class="form-control form-control-sm mask-money" placeholder="0">
                                    </td>
                                    <td class="text-center align-middle" id="ppk_status_{{ $m }}">
                                        <span class="badge bg-secondary">Belum Terisi</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpanPPK" class="btn btn-success">Simpan Anggaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function initPPK() {
        if (!window.jQuery) {
            setTimeout(initPPK, 50);
            return;
        }

        (function($) {

            const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

            // ===============================
            // BRANCH -> KLINIK
            // ===============================
            $('#ppk_id_branch').on('change', function() {

                let branchId = $(this).val();

                $('#ppk_id_clinic').html('<option value="">Loading...</option>');
                $('#ppk_id_item').html('<option value="">-- Pilih Klinik Dahulu --</option>');

                if (branchId) {
                    $.get("{{ route('server.branch-clinics', ':id') }}".replace(':id', branchId), function(data) {

                        let options = '<option value="">-- Pilih Klinik --</option>';

                        data.forEach(function(clinic) {
                            options += `<option value="${clinic.id}">
                                        ${clinic.nama_klinik.toUpperCase()}
                                    </option>`;
                        });

                        $('#ppk_id_clinic').html(options);
                    });
                }
            });

            // ===============================
            // KLINIK -> ITEM (BERDASARKAN SLA)
            // ===============================
            $('#ppk_id_clinic').on('change', function() {

                let clinicId = $(this).val();

                $('#ppk_id_item').html('<option value="">Loading...</option>');

                if (clinicId) {
                    $.get("{{ route('server.clinic-items', ':id') }}".replace(':id', clinicId), function(data) {

                        let options = '<option value="">-- Pilih Item --</option>';

                        data.forEach(function(item) {
                            options += `<option value="${item.id}">
                                        ${item.text}
                                    </option>`;
                        });

                        $('#ppk_id_item').html(options);
                    });
                }
            });

            // ===============================
            // CHECK EXISTING DATA
            // ===============================
            $('#ppk_id_clinic, #ppk_id_item, #ppk_tahun').on('change', function() {

                let id_clinic = $('#ppk_id_clinic').val();
                let id_item = $('#ppk_id_item').val();
                let tahun = $('#ppk_tahun').val();

                if (id_clinic && id_item && tahun) {

                    $.ajax({
                        url: "{{ route('report.check-existing') }}"
                        , method: "GET"
                        , data: {
                            id_clinic
                            , id_item
                            , tahun
                        }
                        , success: function(res) {

                            months.forEach(function(m) {

                                let input = $(`#ppk_inp_${m}`);
                                let row = $(`#row_ppk_${m}`);
                                let status = $(`#ppk_status_${m}`);

                                // Reset
                                input.val('').attr('readonly', false);
                                row.removeClass('table-secondary text-muted');
                                status.html('<span class="badge bg-secondary">Baru</span>');

                                if (res && res.success && res.data) {

                                    input.val(formatIDR(res.data[m]));

                                    if (res.data[m] && res.data[m] != 0) {
                                        input.attr('readonly', true);
                                        row.addClass('table-secondary text-muted');
                                        status.html('<span class="badge bg-primary">Tersimpan</span>');
                                    }
                                }
                            });
                        }
                        , error: function() {
                            console.error("Gagal memuat data report.");
                        }
                    });
                }
            });

            function formatIDR(val) {
                if (!val) return '';
                let clean = val.toString().replace(/[^0-9]/g, '');
                return clean ? new Intl.NumberFormat('id-ID').format(clean) : '';
            }

        })(jQuery);
    }
    initPPK();

</script>
@endpush
