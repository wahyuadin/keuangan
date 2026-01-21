<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if(Auth::user()->role != '0')
                        <img src="{{ asset('assets/profile/default.png') }}" alt="User Avatar" class="w-px-40 h-px-40 rounded-circle" style="object-fit: cover;" />
                        @else
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar" class="w-px-40 h-px-40 rounded-circle" style="object-fit: cover;" />
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <div class="dropdown-item d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    @if(Auth::user()->role != '0')
                                    <img src="{{ asset('assets/profile/default.png') }}" alt="User Avatar" class="w-px-40 h-px-40 rounded-circle" style="object-fit: cover;" />
                                    @else
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar" class="w-px-40 h-px-40 rounded-circle" style="object-fit: cover;" />
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">{{ ucwords(Auth::user()->nama) }}</span>
                                <small class="text-muted">
                                    @if(Auth::user()->role == 0)
                                    HRD {{ Str::upper(Auth::user()->customer->nama_perusahaan ?? '-') }}
                                    @elseif(Auth::user()->role == 1)
                                    {{ Auth::user()->clinic->nama_klinik ?? '-' }}
                                    @elseif(Auth::user()->role == 2)
                                    Super User
                                    @endif
                                </small>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileEdit{{ Auth::user()->id }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('logout') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
