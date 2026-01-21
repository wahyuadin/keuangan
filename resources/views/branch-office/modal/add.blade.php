<div class="modal fade" id="addBranch-office" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addBranch-officeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('branch-office.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addBranch-officeLabel">Tambah Data klinik</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama klinik -->
                    <div class="mb-3">
                        <label for="nama_branch" class="form-label">Branch Office <span class="text-danger">*</span></label>
                        <input type="text" name="nama_branch" id="nama))___" class="form-control" placeholder="Masukkan Nama Branch Office" required value="{{ old('nama_branch') }}">
                    </div>

                    <!-- Alamat klinik -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat klinik (opsional)">{{ old('alamat') }}</textarea>
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
