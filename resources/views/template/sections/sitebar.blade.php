<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo mb-3">
        <a href="{{ url('/') }}" class="app-brand-link">
            <img src="{{ asset('assets/img/icons/brands/default.png') }}" alt="Nayaka Logo" style="height: 50px">
            <span class="app-brand-text menu-text fw-bolder ms-2" style="font-size: 20px">Nayaka Apps</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        @if(Auth::user()->role == 4)
        <li class="menu-item {{ Request::is('/') ? 'active' : '' }}">
            <a href="{{ url('/') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-house-chimney"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        @endif
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master</span>
        </li>
        <li class="menu-item {{ Request::is('master/kategori*') ? 'active' : '' }}">
            <a href="{{ route('kategori.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-tags"></i>
                <div data-i18n="kategori">Kategori</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('master/item*') ? 'active' : '' }}">
            <a href="{{ route('item.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-boxes"></i>
                <div data-i18n="item">Item</div>
            </a>
        </li>
        {{-- <li class="menu-item {{ Request::is('master/rkap*') ? 'active' : '' }}">
        <a href="{{ route('rkap.index') }}" class="menu-link">
            <i class="menu-icon tf-icons fa-solid fa-calculator"></i>
            <div data-i18n="item">Penetapan RKAP</div>
        </a>
        </li> --}}
        <li class="menu-item {{ Request::is('master/branch-office*') ? 'active' : '' }}">
            <a href="{{ route('branch-office.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-code-branch"></i>
                <div data-i18n="Analytics">Branch Office</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('master/clinic*') ? 'active' : '' }}">
            <a href="{{ route('clinic.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-hospital"></i>
                <div data-i18n="Klinik">Klinik</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('master/user*') ? 'active' : '' }}">
            <a href="{{ route('user-data.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-users"></i>
                <div data-i18n="Analytics">User</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">On Board</span>
        </li>
        <li class="menu-item {{ Request::is('report') ? 'active' : '' }}">
            <a href="{{ route('report.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-file-lines"></i>
                <div data-i18n="Analytics">Konsolidasi</div>
            </a>
        </li>
    </ul>
</aside>
