<div class="modal fade" id="profileEdit{{ Auth::user()->id }}" tabindex="-1" aria-labelledby="profileEdit{{ Auth::user()->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('user-data.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Data User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama User <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" value="{{ old('nama', Auth::user()->nama) }}" required>
                    </div>

                    {{-- Username / Email --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="{{ old('username', Auth::user()->username) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-primary">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordProfile(this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small id="passwordHelp" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="repassword" class="form-label">Re-Password <span class="text-primary">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="repassword" name="repassword" placeholder="Masukan Kembali Password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordProfile(this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small id="repasswordHelp" class="text-danger"></small>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->role == '1')
                    <div class="mb-3 clinic-field clinic-field-{{ Auth::user()->id }}">
                        <label class="form-label">Klinik</label>
                        <select name="clinic_id" class="form-control" disabled>
                            <option disabled selected>== Pilih Salah Satu ==</option>
                            @foreach(App\Models\Clinic::all() as $c)
                            <option value="{{ $c->id }}" {{ Auth::user()->clinic_id == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_klinik }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @elseif(Auth::user()->role == '0')
                    <div class="mb-3 clinic-field clinic-field-{{ Auth::user()->id }}">
                        <label class="form-label">Perusahaan</label>
                        <select name="nama_perusahaan" class="form-control" disabled>
                            <option disabled selected>== Pilih Salah Satu ==</option>
                            @foreach(App\Models\Customer::where('id', Auth::user()->customer_id)->get() as $c)
                            <option value="{{ $c->id }}" {{ Auth::user()->customer_id == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_perusahaan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Foto --}}
                    <div class="mb-3">
                        @if(Auth::user()->role != '0')
                        <label class="form-label">Tanda Tangan Digital</label> <br>
                        @else
                        <label class="form-label">Foto Profile</label> <br>
                        @endif
                        <img id="old_avatar{{ Auth::user()->id }}" src="{{ asset('storage/'. Auth::user()->avatar) }}" class="img-fluid mb-2" style="max-width:150px" />
                        <img id="preview_edit{{ Auth::user()->id }}" class="img-fluid mb-2" style="max-width:150px; display:none;" />
                        <input id="foto_edit{{ Auth::user()->id }}" type="file" class="form-control" onchange="previewImageEdit(this, 'preview_edit{{ Auth::user()->id }}')" name="avatar" accept="image/*">
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

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const password = document.getElementById("password");
        const repassword = document.getElementById("repassword");
        const passwordHelp = document.getElementById("passwordHelp");
        const repasswordHelp = document.getElementById("repasswordHelp");

        function validatePassword() {
            const value = password.value.trim();
            let message = "";

            if (value === "") {
                message = "";
            } else if (value.length < 6) {
                message = "Password minimal 6 karakter.";
            } else if (!/^[A-Z]/.test(value)) {
                message = "Password harus diawali huruf kapital.";
            } else if (!/\d/.test(value)) {
                message = "Password harus mengandung angka.";
            }

            passwordHelp.textContent = message;
            return message === "";
        }

        function validateRepassword() {
            const value = repassword.value.trim();
            let message = "";

            if (value === "") {
                message = "";
            } else if (value !== password.value.trim()) {
                message = "Re-Password tidak cocok.";
            }

            repasswordHelp.textContent = message;
            return message === "";
        }

        password.addEventListener("input", () => {
            validatePassword();
            validateRepassword();
        });

        repassword.addEventListener("input", validateRepassword);
        document.querySelector("#profileEdit{{ Auth::user()->id }} form")
            .addEventListener("submit", function(e) {
                const passVal = password.value.trim();
                const repassVal = repassword.value.trim();

                if (passVal === "" && repassVal === "") {
                    passwordHelp.textContent = "";
                    repasswordHelp.textContent = "";
                    return;
                }

                if (!validatePassword() || !validateRepassword()) {
                    e.preventDefault();
                    alert("Periksa kembali password Anda.");
                }
            });
    });

    function previewImageEdit(input, previewId) {
        const preview = document.getElementById(previewId);
        const oldAvatar = document.getElementById('old_avatar{{ Auth::user()->id }}');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = "block";
            if (oldAvatar) oldAvatar.style.display = "none";
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('assets/profile/default.png') }}";
            preview.style.display = "block";
            if (oldAvatar) oldAvatar.style.display = "none";
        }
    }

    function togglePasswordProfile(button) {
        const input = button.closest('.input-group').querySelector('input');
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

</script>
@endpush
