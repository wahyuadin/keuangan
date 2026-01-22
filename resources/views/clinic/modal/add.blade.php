<div class="modal fade" id="addClinic" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addClinicLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('clinic.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addClinicLabel">Tambah Data klinik</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama klinik -->

                    <div class="mb-3">
                        <label for="branch_id" class="form-label">Branch Office <span class="text-danger">*</span></label>
                        <select name="branch_id" id="branch_id" class="form-select select2" required>
                            <option value="" disabled selected>Pilih Branch Office</option>
                            @php
                                $branchOffices = \App\Models\BranchOffice::showData();
                            @endphp
                            @foreach($branchOffices as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ Str::upper($branch->nama_branch) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nama_klinik" class="form-label">Klinik <span class="text-danger">*</span></label>
                        <input type="text" name="nama_klinik" id="nama_klinik" class="form-control" placeholder="Masukkan Nama Klinik" required value="{{ old('nama_klinik') }}">
                    </div>

                    <!-- Alamat klinik -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat klinik" required>{{ old('alamat') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="Kota" class="form-label">Kota <span class="text-danger">*</span></label>
                        <input type="text"name="kota" id="kota" class="form-control" placeholder="Masukkan kota klinik">{{ old('kota') }}</input>
                    </div>
                    <div class="mb-3">
                        <label for="penetapan_rkap" class="form-label">Penetapan RKAP</label>
                        <input type="text" name="penetapan_rkap" type="number" id="penetapan_rkap" class="form-control" placeholder="Masukkan penetapan RKAP (opsional)">{{ old('penetapan_rkap') }}</input>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
