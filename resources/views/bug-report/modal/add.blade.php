<div class="modal fade" id="addbug" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addbugLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('bug-report.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addbugLabel">Tambah Data Bug Report</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama klinik -->
                    <div class="mb-3">
                        <label for="nama_agent" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="5" required>{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="nama_pasien" class="form-label">Screenshot <span class="text-danger">*</span></label>
                        <div id="preview-container" style="margin-top:10px;">
                            <img id="preview" src="" alt="Preview Gambar" style="max-width:200px; display:none; border-radius:8px;">
                            <p id="error-msg" style="color:red; font-size:14px; display:none;"></p>
                        </div>
                        <input class="form-control" type="file" name="foto" id="image" accept="image/*" required>
                        <small class="text-muted">Ukuran maksimal file: 1MB</small>
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
@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('preview');
        const errorMsg = document.getElementById('error-msg');

        // Reset tampilan
        preview.style.display = 'none';
        errorMsg.style.display = 'none';
        preview.src = '';

        if (!file) return;

        // Validasi ukuran file (1MB = 1 * 1024 * 1024 bytes)
        if (file.size > 1 * 1024 * 1024) {
            errorMsg.textContent = 'Ukuran file terlalu besar! Maksimal 1MB.';
            errorMsg.style.display = 'block';
            this.value = ''; // Reset input file
            return;
        }

        // Preview gambar
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    });

</script>
@endpush
