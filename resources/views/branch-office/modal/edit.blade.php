@foreach ($data as $dataEdit)
<div class="modal fade" id="editBranch-office{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addbranch-officeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('branch-office.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editbranch-officeLabel{{ $dataEdit->id }}">Edit Data
                        Branch Office</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_branch-office" class="form-label">Nama Branch Office <span class="text-danger">*</span></label>
                        <input type="text" name="nama_branch" id="nama_branch-office" class="form-control" value="{{ $dataEdit->nama_branch }}" placeholder="Masukkan nama branch office" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat branch-office (opsional)">{{ $dataEdit->alamat }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
