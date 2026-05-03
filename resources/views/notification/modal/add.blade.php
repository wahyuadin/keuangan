<div class="modal fade" id="addNotification" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addNotificationLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('notification.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addNotificationLabel">Tambah Data Notifikasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama klinik -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input name="title" id="title" class="form-control" placeholder="Masukkan judul notifikasi" required>{{ old('title') }}</input>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                        <textarea name="message" id="message" class="form-control" rows="3" placeholder="Masukkan pesan notifikasi" required>{{ old('message') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status Aktif <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_active" id="is_active_1" value="1" checked>
                            <label class="form-check-label" for="is_active_1">Aktif</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_active" id="is_active_0" value="0">
                            <label class="form-check-label" for="is_active_0">Tidak Aktif</label>
                        </div>
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
