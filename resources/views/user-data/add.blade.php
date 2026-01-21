<div class="modal fade" id="addData" tabindex="1000" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('user-data.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data User || {{ config('app.name') }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Nama User <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" placeholder="Masukan Nama Lengkap" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Masukan username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Masukan Email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" placeholder="Masukan password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="repassword" class="form-label">Re-Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="repassword" name="repassword" placeholder="Masukan Kembali Password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('repassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" id="roleSelect">
                                    <option selected disabled>== Pilih Role ==</option>
                                    <option value="0" {{ old('role') == 0 ? 'selected' : '' }}>HRD</option>
                                    <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Admin Klinik</option>
                                    <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Super Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Is Active <span class="text-danger">*</span></label>
                                <select name="is_active" class="form-select">
                                    <option disabled selected>== Pilih Status ==</option>
                                    <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Tidak Aktif
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" id="perusahaanfield">
                            <div class="mb-3">
                                <label for="customer" class="form-label">Perusahaan <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-select select2">
                                    <option selected disabled>== Pilih Salah Satu ==</option>
                                    @php
                                    $customer = App\Models\Customer::all();
                                    foreach ($customer as $c) {
                                    echo '<option value="' .
                                                $c->id .
                                                '" ' .
                                                ($c->id == old(' customer_id') ? 'selected' : '' ) . '>' . $c->nama_perusahaan .
                                        '</option>';
                                    }
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" id="clinicfield">
                            <div class="mb-3">
                                <label for="clinic_id" class="form-label">Klinik <span class="text-danger">*</span></label>
                                <select name="clinic_id" class="form-select select2">
                                    <option selected disabled>== Pilih Salah Satu ==</option>
                                    @php
                                    $clinic = App\Models\Clinic::all();
                                    foreach ($clinic as $c) {
                                    echo '<option value="' .
                                                $c->id .
                                                '" ' .
                                                ($c->id == old(' clinic_id') ? 'selected' : '' ) . '>' . $c->nama_klinik .
                                        '</option>';
                                    }
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" id="labelFoto">Foto <span class="text-secoundary">*</span></label>
                                <div class="mt-3 mb-3">
                                    <img id="preview" src="#" alt="Preview Foto" class="img-fluid d-none" style="max-width: 200px; max-height: 200px;">
                                </div>
                                <input type="file" class="form-control" name="avatar" accept="image/*" onchange="previewImage()">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
