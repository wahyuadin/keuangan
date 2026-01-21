<div class="modal fade" id="addKategori" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addItemLabel">Tambah Data Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama item -->
                    <div class="mb-3">
                        <label for="item" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kategori" id="kategori" class="form-control" placeholder="Masukkan Nama Kategori" required value="{{ old('kategori') }}">
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
