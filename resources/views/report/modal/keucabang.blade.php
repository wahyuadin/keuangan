<div class="modal fade" id="addReportKeu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class='bx bx-money'></i> Input Realisasi & Verifikasi (Keuangan)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKeu" action="{{ route('report-clinic.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">

                        {{-- BRANCH --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Branch</label>
                            <select id="keu_id_branch" class="form-select select2-modal" required>
                                <option value="">-- Pilih Branch --</option>
                                @foreach(\App\Models\BranchOffice::orderBy('nama_branch')->get() as $b)
                                <option value="{{ $b->id }}">{{ strtoupper($b->nama_branch) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KLINIK --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Klinik</label>
                            <select name="clinic_id" id="keu_id_clinic" class="form-select select2-modal" required>
                                <option value="">-- Pilih Branch Dahulu --</option>
                            </select>
                        </div>

                        {{-- ITEM --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Item Anggaran</label>
                            <select name="item_id" id="keu_id_item" class="form-select select2-modal" required>
                                <option value="">-- Pilih Klinik Dahulu --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <select name="tahun" id="keu_tahun" class="form-select" required>
                                @for($t = date('Y'); $t >= 2023; $t--)
                                <option value="{{ $t }}">{{ $t }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-info text-center">
                                <tr>
                                    <th width="120">Bulan</th>
                                    <th>Anggaran (Rp)</th>
                                    <th>Realisasi (Rp)</th>
                                    <th>Keterangan</th>
                                    <th width="150">Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'] as $m)
                                <tr id="row_keu_{{ $m }}">
                                    <td class="align-middle fw-bold text-capitalize">{{ $m }}</td>
                                    <td><input type="text" id="keu_anggaran_{{ $m }}" class="form-control form-control-sm bg-light" readonly></td>
                                    <td><input type="text" name="{{ $m }}_realisasi" id="keu_inp_real_{{ $m }}" class="form-control form-control-sm mask-money" placeholder="0"></td>
                                    <td><input type="text" name="{{ $m }}_keterangan" id="keu_inp_ket_{{ $m }}" class="form-control form-control-sm" placeholder="Keterangan..."></td>
                                    <td class="text-center align-middle">
                                        <input type="hidden" name="{{ $m }}_verif" id="keu_verif_val_{{ $m }}" value="0">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input chk-verif" type="checkbox" id="keu_chk_verif_{{ $m }}" data-month="{{ $m }}" style="cursor: pointer; width: 2.5em; height: 1.25em;">
                                        </div>
                                        <small id="keu_status_text_{{ $m }}" class="text-muted" style="font-size: 0.75rem;">Belum Verif</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">Simpan Realisasi & Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function initKeu() {

        if (!window.jQuery) {
            setTimeout(initKeu, 100);
            return;
        }

        (function($) {

            const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
            $('#addReportKeu').on('shown.bs.modal', function() {
                $('.select2-modal').select2({
                    theme: 'bootstrap-5'
                    , dropdownParent: $('#addReportKeu')
                    , width: '100%'
                    , placeholder: '-- Pilih --'
                    , allowClear: true
                });
            });
            $('#keu_id_branch').on('change', function() {

                let branchId = $(this).val();

                $('#keu_id_clinic').html('<option value="">Loading...</option>');
                $('#keu_id_item').html('<option value="">-- Pilih Klinik Dahulu --</option>');

                if (branchId) {
                    $.get("{{ route('server.branch-clinics', ':id') }}".replace(':id', branchId), function(data) {

                        let options = '<option value="">-- Pilih Klinik --</option>';

                        data.forEach(function(clinic) {
                            options += `<option value="${clinic.id}">
                                        ${clinic.nama_klinik.toUpperCase()}
                                    </option>`;
                        });

                        $('#keu_id_clinic').html(options).trigger('change');
                    });
                }
            });

            // ===============================
            // KLINIK -> ITEM (BERDASARKAN SLA)
            // ===============================
            $('#keu_id_clinic').on('change', function() {

                let clinicId = $(this).val();

                $('#keu_id_item').html('<option value="">Loading...</option>');

                if (clinicId) {
                    $.get("{{ route('server.clinic-items', ':id') }}".replace(':id', clinicId), function(data) {

                        let options = '<option value="">-- Pilih Item --</option>';

                        data.forEach(function(item) {
                            options += `<option value="${item.id}">
                                        ${item.text}
                                    </option>`;
                        });

                        $('#keu_id_item').html(options).trigger('change');
                    });
                }
            });

            // ===============================
            // CHECK EXISTING REPORT
            // ===============================
            $('#keu_id_clinic, #keu_id_item, #keu_tahun').on('change', function() {

                let id_clinic = $('#keu_id_clinic').val();
                let id_item = $('#keu_id_item').val();
                let tahun = $('#keu_tahun').val();

                if (!(id_clinic && id_item && tahun)) return;

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

                            // RESET DEFAULT
                            $(`#keu_anggaran_${m}`).val('0');
                            $(`#keu_inp_real_${m}`).val('0').prop('readonly', false);
                            $(`#keu_inp_ket_${m}`).val('').prop('readonly', false);
                            $(`#keu_chk_verif_${m}`).prop('checked', false).prop('disabled', false);
                            $(`#keu_verif_val_${m}`).val('0');
                            $(`#keu_status_text_${m}`).text('Belum Verif').removeClass('text-success fw-bold');

                            if (res.success && res.data) {

                                let d = res.data;

                                // ANGARAN
                                $(`#keu_anggaran_${m}`).val(formatIDR(d[m]));

                                // REALISASI
                                $(`#keu_inp_real_${m}`).val(formatIDR(d[m + '_realisasi']));

                                // KETERANGAN
                                $(`#keu_inp_ket_${m}`).val(d[m + '_keterangan'] || '');

                                // CEK VERIFIKASI
                                let verifikator = d[m + '_verif_by'];

                                if (verifikator) {

                                    $(`#keu_chk_verif_${m}`).prop('checked', true).prop('disabled', true);
                                    $(`#keu_verif_val_${m}`).val('1');
                                    $(`#keu_inp_real_${m}`).prop('readonly', true);
                                    $(`#keu_inp_ket_${m}`).prop('readonly', true);

                                    $(`#keu_status_text_${m}`)
                                        .html(`<span class="text-success fw-bold">
                                            <i class="bx bxs-check-shield"></i>
                                            Terverif: ${verifikator}
                                           </span>`);
                                }
                            }
                        });
                    }
                });

            });

            // ===============================
            // CHECKBOX VERIF MANUAL
            // ===============================
            $(document).off('change', '.chk-verif').on('change', '.chk-verif', function() {

                let month = $(this).data('month');
                let isChecked = $(this).is(':checked');
                let realVal = $(`#keu_inp_real_${month}`).val();

                if (isChecked) {

                    if (!realVal || realVal === '0') {
                        Swal.fire('Perhatian', 'Realisasi harus diisi sebelum verifikasi', 'warning');
                        $(this).prop('checked', false);
                        return;
                    }

                    $(`#keu_verif_val_${month}`).val('1');
                    $(`#keu_status_text_${month}`)
                        .html('<span class="text-success fw-bold"><i class="bx bxs-check-shield"></i> Siap Verif</span>');
                } else {
                    $(`#keu_verif_val_${month}`).val('0');
                    $(`#keu_status_text_${month}`)
                        .text('Belum Verif')
                        .removeClass('text-success fw-bold');
                }
            });

            // ===============================
            // FORMAT RUPIAH
            // ===============================
            function formatIDR(val) {
                if (!val || val === '0') return '0';
                let clean = val.toString().replace(/[^0-9]/g, '');
                return clean ? new Intl.NumberFormat('id-ID').format(clean) : '0';
            }

        })(jQuery);
    }
    initKeu();

</script>
@endpush
