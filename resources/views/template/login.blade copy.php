<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed  layout-menu-fixed   customizer-hide layout-wide" dir="ltr" data-theme="theme-default" data-assets-path="/sneat-html-django-admin-template/demo-1/static/" data-base-url="" data-framework="django" data-template="vertical-menu--theme-default-light">

<head>
    <title>{{ config('app.name') . ' | Halaman Login' }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="Netsian Komputer - PT. Nayaka Era Husada" />
    <meta name="keywords" content="nayaka, netsian komputer, nayaka era husada, klinik" />
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://wahyuadinugroho.my.id">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://vectorez.biz.id/wp-content/uploads/2024/05/Logo-Nayaka-Era-Husada@0.5x.png" />

    <!--Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/flag-icons.css') }}" />

    <!--Core CSS -->

    <link rel="stylesheet" href="{{ asset('assets/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo2.css') }}" />

    <!--Vendor CSS-->
    <link rel="stylesheet" href="{{ asset('assets/css/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/typeahead.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/form-validation.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/page-auth.css') }}" />

    <!--? Template customizer: To hide customizer set display_customizer value false in config.js.  -->

    <script src="{{ asset('assets/css/template-customizer.js') }}"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/css/config.js') }}"></script>
    <!-- beautify ignore:start -->
    @toastifyCss
</head>

<body>
    @php
    $rememberDeviceDecoded = isset($rememberDevice) ? json_decode($rememberDevice) : null;
    @endphp
    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-logo demo"></span>
            <img src="https://www.nayakaerahusada.com/assets/icon.png" height="100" alt="Brand Logo" class="logo-icon me-2">
            </span>
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="https://www.nayakaerahusada.com/storage/slide/01K1F7KWT8P6P8P1SQRFESXXWB.jpg" class="img-fluid" alt="Login image" width="5000">
                </div>
            </div>
            <!-- /Left Text -->
            @if (session('account'))
            <!-- Modal -->
            <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="accountModalLabel">Pendaftaran Berhasil </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Username:</strong> {{ session('account.username') }}</p>
                            <p><strong>Password:</strong> {{ session('account.password') }}</p>
                            <p class="text-muted">Silakan login menggunakan data tersebut untuk masuk ke sistem. Silahkan dicatat dan dihimbau untuk mengganti password ataupun username default.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="closeModal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    var myModal = new bootstrap.Modal(document.getElementById('accountModal'));
                    myModal.show();
                });
                const closeModal = document.getElementById('closeModal');
                closeModal.addEventListener('click', function() {
                    window.location.reload();
                });
            </script>
            @endif
            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-12 pt-5">
                    <h4 class="mb-1">Klinik Nayaka Husada</h4>
                    {{-- <p class="mb-6">PT. NAYAKA ERA HUSADA</p> --}}
                    {{-- <p class="text-medium-emphasis">M Wahyu Adi Nugroho</p> --}}

                    <form class="mb-6" action="{{ route('login.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Masukan Username" value="{{ $rememberDeviceDecoded ? $rememberDeviceDecoded->username : '' }}" autofocus>
                            @if ($errors->has('username'))
                            <div class="text-danger">
                                {{ $errors->first('username') }}
                            </div>
                            @endif
                        </div>
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" name="password" id="password" value="{{ $rememberDeviceDecoded ? $rememberDeviceDecoded->password : '' }}" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer" id="togglePassword"><i class="bx bx-hide"></i></span>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const passwordInput = document.getElementById('password');
                                    const togglePassword = document.getElementById('togglePassword');
                                    const icon = togglePassword.querySelector('i');
                                    togglePassword.addEventListener('click', function() {
                                        const type = passwordInput.type === 'password' ? 'text' : 'password';
                                        passwordInput.type = type;
                                        icon.classList.toggle('bx-hide');
                                        icon.classList.toggle('bx-show');
                                    });
                                });
                            </script>
                            @if ($errors->has('password'))
                            <div class="text-danger">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="my-8">
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0 ms-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="remember_device" {{ $rememberDevice ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember-me"> Simpan data </label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100">
                            Login
                        </button>
                    </form>
                    {{-- <p class="text-center">
                        <span>Baru di platform kami?</span>
                        <a href="">
                            <span>Daftar</span>
                        </a>
                    </p> --}}
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>

    <!-- Content: End -->
    <!--Core Javascript-->
    <script
        src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/libs/jquery/jquery.js">
    </script>
    <script
        src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/libs/popper/popper.js">
    </script>
    <script src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/js/bootstrap.js">
    </script>
    <script
        src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/libs/perfect-scrollbar/perfect-scrollbar.js">
    </script>
    <script
        src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/libs/hammer/hammer.js">
    </script>
    <script
        src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/libs/typeahead-js/typeahead.js">
    </script>
    <script src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/js/menu.js">
    </script>
    <script src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/js/main.js"></script>


    <!--Vendors Javascript-->
    <script
        src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/libs/%40form-validation/popular.js">
    </script>
    <script
        src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/libs/%40form-validation/bootstrap5.js">
    </script>
    <script
        src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/vendor/libs/%40form-validation/auto-focus.js">
    </script>


    <!--Page Javascript-->
    <script src="https://demos.themeselection.com/sneat-html-django-admin-template/demo-1/static/js/pages-auth.js"></script>
    @toastifyJs
</body>

</html>