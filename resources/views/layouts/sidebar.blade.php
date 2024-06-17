<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('thoikhoabieu')}}">
        <img class="h-100" src="{{asset('imgs/logo/logo.png')}}" alt="logo">
        {{-- <div class="sidebar-brand-text mx-3">Shedule<sup></sup></div> --}}
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('thoikhoabieu') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('thoikhoabieu')}}">
            <i class="fas fa-fw fa-calendar"></i>
            <span>Thời khóa biểu</span>
        </a>
    </li>

    @guest
    @else
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    @if(Auth::user()->VaiTroID == 2) <!-- Giảng viên -->
    <!-- Heading -->
    <div class="sidebar-heading">
        Giảng Viên
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('thoiKhoaBieuDangKy') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Quản trị Giảng Viên</span>
        </a>
        <div id="collapseTwo" class="collapse {{ request()->is('thoiKhoaBieuDangKy') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('thoiKhoaBieuDangKy') ? 'active' : '' }}" href="/thoiKhoaBieuDangKy">TKB Đăng Ký</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Checking User Role -->
    @endif
     
    @if(Auth::user()->VaiTroID == 1) <!-- Admin -->
    <!-- Heading -->
    <div class="sidebar-heading">
        Quản trị viên
    </div>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('duyetTKB') || request()->is('listTKBDK') ? 'active' : '' }}">
        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#collapseTKBDK"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-table"></i>
            <span>Quản Lý TKB Đăng Ký</span>
            @if($countPendingThoiKhoaBieu > 0)
                <span class="badge badge-danger badge-counter">{{ $countPendingThoiKhoaBieu }}+</span>
            @endif
        </a>
        <div id="collapseTKBDK" class="collapse {{ request()->is('duyetTKB') || request()->is('listTKBDK') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('duyetTKB') ? 'active' : '' }}" href="{{route('danh-sach-dang-ky-tkb')}}">
                    <span>Duyệt TKB</span>
                    @if($countPendingThoiKhoaBieu > 0)
                        <span class="badge badge-danger badge-counter float-right">{{ $countPendingThoiKhoaBieu }}+</span>
                    @endif
                </a>
                <a class="collapse-item {{ request()->is('listTKBDK') ? 'active' : '' }}" href="{{route('ds-tkb-dang-ky-tk')}}">TKB Đăng Ký</a>
            </div>
        </div>
    </li>
    <li class="nav-item {{ request()->is('crawlerDataTKB') ? 'active' : '' }}">
        <a class="nav-link" href="/crawlerDataTKB">
            <i class="fas fa-sync-alt"></i>
            <span>Lấy dữ liệu TKB</span>
        </a>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('phongmay') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDesktop"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-desktop"></i>
            <span>Phòng Máy</span>
        </a>
        <div id="collapseDesktop" class="collapse {{ request()->is('phongmay') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('phongmay') ? 'active' : '' }}" href="{{route('phongmay')}}">Danh Sách Phòng</a>
            </div>
        </div>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('tiethoc') || request()->is('hocky') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseService"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-tools"></i>
            <span>Cấu Hình</span>
        </a>
        <div id="collapseService" class="collapse {{ request()->is('tiethoc') || request()->is('hocky') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('tiethoc') ? 'active' : '' }}" href="{{route('tiethoc')}}">Tiết Học</a>
                <a class="collapse-item {{ request()->is('hocky') ? 'active' : '' }}" href="{{route('hocky')}}">Học Kỳ</a>
            </div>
        </div>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('taikhoangiangvien') || request()->is('taikhoanhethong') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-user"></i>
            <span>Quản Lý Tài Khoản</span>
        </a>
        <div id="collapsePages" class="collapse {{ request()->is('taikhoangiangvien') || request()->is('taikhoanhethong') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('taikhoangiangvien') ? 'active' : '' }}" href="{{route('taikhoangiangvien')}}">Tài Khoản Giảng Viên</a>
                <a class="collapse-item {{ request()->is('taikhoanhethong') ? 'active' : '' }}" href="{{route('taikhoanhethong')}}">Tài Khoản Hệ Thống</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    @endif

    @endguest

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
