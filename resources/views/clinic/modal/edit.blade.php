@foreach ($data as $dataEdit)
<div class="modal fade" id="editclinic{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addbranch-officeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('clinic.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editclinicLabel{{ $dataEdit->id }}">Edit Data
                        Clinic</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="branch_id" class="form-label">Branch Office <span class="text-danger">*</span></label>
                        <select name="branch_id" id="branch_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Branch Office</option>
                            @php
                                $branchOffices = \App\Models\BranchOffice::showData();
                            @endphp
                            @foreach($branchOffices as $branch)
                                <option value="{{ $branch->id }}" {{ $dataEdit->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ Str::upper($branch->nama_branch) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_klinik" class="form-label">Nama Klinik <span class="text-danger">*</span></label>
                        <input type="text" name="nama_klinik" id="nama_klinik" class="form-control" value="{{ $dataEdit->nama_klinik }}" placeholder="Masukkan nama Klinik" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat branch-office">{{ $dataEdit->alamat }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                        <input type="text" name="kota" id="kota" class="form-control" value="{{ $dataEdit->kota }}" placeholder="Masukkan kota" required>
                    </div>
                    <div class="mb-3">
                        <label for="penetapan_rkap" class="form-label">Penetapan RKAP</label>
                        <input type="number" name="penetapan_rkap" id="penetapan_rkap" class="form-control" value="{{ $dataEdit->penetapan_rkap }}" placeholder="Masukkan penetapan rkap (opsional)" required>
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
