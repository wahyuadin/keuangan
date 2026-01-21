@foreach ($data as $datas)
<div class="modal fade" id="editData{{ $datas->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('user-data.update', $datas->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Data User || {{ config('app.name') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" class="modal-data-id" value="{{ $datas->id }}">

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama User <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" value="{{ old('nama', $datas->nama) }}" required>
                    </div>

                    {{-- Username / Email --}}
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="{{ old('username', $datas->username) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $datas->email) }}" required>
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select role-select" data-id="{{ $datas->id }}">
                                    <option disabled selected>== Pilih Role ==</option>
                                    <option value="0" {{ $datas->role == 0 ? 'selected' : '' }}>HRD</option>
                                    <option value="1" {{ $datas->role == 1 ? 'selected' : '' }}>Admin Klinik</option>
                                    <option value="2" {{ $datas->role == 2 ? 'selected' : '' }}>Super Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Is Active <span class="text-danger">*</span></label>
                                <select name="is_active" class="form-select">
                                    <option disabled selected>== Pilih Status ==</option>
                                    <option value="1" {{ $datas->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $datas->is_active == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Field Perusahaan / Klinik --}}
                    <div class="mb-3 perusahaan-field perusahaan-field-{{ $datas->id }}">
                        <label class="form-label">Perusahaan</label>
                        <select name="customer_id" class="form-select select2">
                            <option disabled selected>== Pilih Salah Satu ==</option>
                            @foreach(App\Models\Customer::all() as $c)
                            <option value="{{ $c->id }}" {{ $datas->customer_id == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_perusahaan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 clinic-field clinic-field-{{ $datas->id }}">
                        <label class="form-label">Klinik</label>
                        <select name="clinic_id" class="form-select select2">
                            <option disabled selected>== Pilih Salah Satu ==</option>
                            @foreach(App\Models\Clinic::all() as $c)
                            <option value="{{ $c->id }}" {{ $datas->clinic_id == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_klinik }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Password --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control password-input" name="password" id="password_edit{{ $datas->id }}" placeholder="Masukan Password">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_edit{{ $datas->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                <div class="invalid-feedback d-block" id="password_error_edit{{ $datas->id }}" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control repassword-input" name="repassword" id="repassword_edit{{ $datas->id }}" placeholder="Masukan ulang kembali password">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="repassword_edit{{ $datas->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                <div class="invalid-feedback d-block" id="password_confirmation_error_edit{{ $datas->id }}" style="display:none;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="mb-3">
                        <label class="form-label">Foto</label> <br>
                        <img id="preview_edit{{ $datas->id }}" src="{{ $datas->avatar != 'default.png' ? asset('storage/'.$datas->avatar) : asset('assets/profile/default.png') }}" class="img-fluid mb-2" style="max-width:150px">

                        <input id="foto_edit{{ $datas->id }}" type="file" class="form-control" onchange="previewImageEdit(this, 'preview_edit{{ $datas->id }}')" name="avatar" accept="image/*">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    function previewImageEdit(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];

        if (file && preview) {
            const reader = new FileReader();
            reader.onloadend = function() {
                preview.src = reader.result; // langsung timpa gambar lama
            }
            reader.readAsDataURL(file);
        }
    }

    $(function() {
        $('.toggle-password').on('click', function() {
            let targetId = $(this).data('target');
            let input = document.getElementById(targetId);
            let icon = $(this).find('i');

            if (input.type === "password") {
                input.type = "text";
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                input.type = "password";
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });

        $('.password-input, .repassword-input').on('keyup change', function() {
            let id = $(this).closest('.modal').find('.modal-data-id').val();

            let password = $('#password_edit' + id).val();
            let repassword = $('#repassword_edit' + id).val();

            let passwordError = $('#password_error_edit' + id);
            let confirmError = $('#password_confirmation_error_edit' + id);

            // Reset error
            passwordError.hide().text('');
            confirmError.hide().text('');

            // Regex: huruf besar di awal + kombinasi angka + minimal 6
            let regex = /^[A-Z][A-Za-z0-9]{5,}$/;
            let numberCheck = /[0-9]/;

            if (password.length > 0) {
                if (!regex.test(password) || !numberCheck.test(password)) {
                    passwordError.text("Password harus minimal 6 karakter, huruf besar di awal, dan kombinasi angka.")
                        .show();
                }
            }

            if (repassword.length > 0) {
                if (password !== repassword) {
                    confirmError.text("Password tidak sama.").show();
                }
            }
        });
    });

</script>
@endpush
