<!-- Sidebar Start -->
<style>
    .custom-navbar {
        background-color: #7AA2E3;
        /* Ganti dengan warna yang diinginkan */
    }
</style>

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between custom-navbar">
            <a href="{{url("/home")}}" class="text-nowrap logo-img">
                <img src="../assets/images/logos/logo2.png" width="180" alt="" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- <hr> -->
        @if (auth()->user()->role == 'super_admin')
        <!-- Sidebar Super Admin-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('superadmin.dashboard')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-home"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Manajemen</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('superadmin.user')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">User</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endif
        <!-- End Sidebar Super Admin -->

        @if (auth()->user()->role == 'admin')
        <!-- Sidebar Admin-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.dashboard')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Perencanaan</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("admin.anggaran")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-cash"></i>
                        </span>
                        <span class="hide-menu">Anggaran</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("admin.usulan")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-clipboard-text"></i>
                        </span>
                        <span class="hide-menu">Usulan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("admin.realisasi")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-plus"></i>
                        </span>
                        <span class="hide-menu">RPD</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("admin.monitoring")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-device-desktop-analytics"></i>
                        </span>
                        <span class="hide-menu">Monitoring</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Meta Data</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("admin.kategori")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-article"></i>
                        </span>
                        <span class="hide-menu">Kategori</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("admin.kode")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-article"></i>
                        </span>
                        <span class="hide-menu">Kode Komponen</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("admin.satuan")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-article"></i>
                        </span>
                        <span class="hide-menu">Satuan</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Report</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("admin.report")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-report"></i>
                        </span>
                        <span class="hide-menu">Report</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endif
        <!-- End Sidebar Admin -->

        @if (auth()->user()->role == 'direksi')
        <!-- Sidebar Direksi-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('direksi.dashboard')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">MANAJEMEN</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("direksi.monitoring")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-device-desktop-analytics"></i>
                        </span>
                        <span class="hide-menu">Monitoring</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Report</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("direksi.report")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-report"></i>
                        </span>
                        <span class="hide-menu">Report</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endif
        <!-- End Sidebar Direksi -->

        @if (auth()->user()->role == 'unit')
        <!-- Sidebar Unit-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('unit.dashboard')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Manajemen</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("unit.usulan")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-file-pencil"></i>
                        </span>
                        <span class="hide-menu">Pengajuan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("unit.histori")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-clock"></i>
                        </span>
                        <span class="hide-menu">Histori</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("unit.rpd")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-plus"></i>
                        </span>
                        <span class="hide-menu">RPD</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("unit.monitoring")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-device-desktop-analytics"></i>
                        </span>
                        <span class="hide-menu">Monitoring</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Report</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route("unit.report")}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-report"></i>
                        </span>
                        <span class="hide-menu">Report</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endif
        <!-- End Sidebar Unit -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
