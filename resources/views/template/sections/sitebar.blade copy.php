<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo mb-3">
        <a href="{{ url('/') }}" class="app-brand-link">
            <img src="{{ asset('assets/img/icons/brands/default.png') }}" alt="Nayaka Logo" style="height: 50px">
            <span class="app-brand-text menu-text fw-bolder ms-2" style="font-size: 20px">Nayaka Apps</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        @if(Auth::user()->role == '2')
        <li class="menu-item {{ Request::is('/') ? 'active' : '' }}">
            <a href="{{ url('/') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-house-chimney"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        @endif
        {{-- <li class="menu-item {{ Request::is('bulanan*') ? 'active' : '' }}">
        <a href="{{ route('dashboard-bulanan') }}" class="menu-link">
            <i class="menu-icon tf-icons fa-solid fa-calendar-days"></i>
            <div data-i18n="Analytics">Dashboard Bulanan</div>
        </a>
        </li> --}}
        @if(Auth::user()->role != '0')

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master</span>
        </li>
        <li class="menu-item {{ Request::is('kategori*') ? 'active' : '' }}">
            <a href="{{ route('kategori.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-building"></i>
                <div data-i18n="kategori">Kategori</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('klinik*') ? 'active' : '' }}">
            <a href="{{ route('klinik.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-hospital"></i>
                <div data-i18n="Klinik">Klinik</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('paramedis*') ? 'active' : '' }}">
            <a href="{{ route('paramedis.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-user-nurse"></i>
                <div data-i18n="Analytics">Paramedis</div>
            </a>
        </li>
        @if(Auth::user()->role == '2')
        <li class="menu-item {{ Request::is('branch-office*') ? 'active' : '' }}">
            <a href="{{ route('branch-office.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-code-branch"></i>
                <div data-i18n="Analytics">Branch Office</div>
            </a>
        </li>
        @endif
        <li class="menu-item {{ Request::is('patient*') ? 'active' : '' }}">
            <a href="{{ route('patient.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-wheelchair"></i>
                <div data-i18n="Analytics">Pasien</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('agent*') ? 'active' : '' }}">
            <a href="{{ route('agent.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-user-tie"></i>
                <div data-i18n="Analytics">Agent</div>
            </a>
        </li>
        @endif
        @if(Auth::user()->role == '2')
        <li class="menu-item {{ Request::is('user*') ? 'active' : '' }}">
            <a href="{{ route('user-data.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-users"></i>
                <div data-i18n="Analytics">User</div>
            </a>
        </li>
        @endif
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">On Board</span>
        </li>
        <li class="menu-item {{ Request::is('surat*') ? 'active' : '' }}">
            <a href="{{ route('surat.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-envelope"></i>
                <div data-i18n="Analytics">Pelayanan Klinik</div>
            </a>
        </li>
        @if(Auth::user()->role != '0')
        <li class="menu-item {{ Request::is('tagihan*') ? 'active' : '' }}">
            <a href="{{ route('tagihan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-file-invoice"></i>
                <div data-i18n="Analytics">Tagihan</div>
            </a>
        </li>
        @endif
        <li class="menu-item {{ Request::is('bug-report*') ? 'active' : '' }}">
            <a href="{{ route('bug-report.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-bug"></i>
                <div data-i18n="Analytics">Bug Report</div>
            </a>
        </li>
    </ul>
</aside>