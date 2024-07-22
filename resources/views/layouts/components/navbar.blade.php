<!--  Header Start -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light ">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>

        </ul>
        <ul class="navbar-nav  flex-row ms-auto align-items-center mt-2">
            <li>
                <span class="d-flex align-text-center">
                    @yield('page-title')
                </span>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <div class="align-text-center dropdown-item" aria-disabled="">
                                <p class="mb-0 fs-3 fw-semibold text-center">
                                    @if (auth()->user()->role == 'admin')
                                    {{ auth()->user()->admin->name }}
                                    @elseif (auth()->user()->role == 'unit')
                                    {{ auth()->user()->unit->name }}
                                    @elseif (auth()->user()->role == 'direksi')
                                    {{ auth()->user()->direksi->name }}
                                    @elseif (auth()->user()->role == 'super_admin')
                                    {{ auth()->user()->super_admin->name }}
                                    @else
                                    {{ auth()->user()->name }}
                                    @endif
                                </p>
                            </div>
                            <a href="{{route('profile.redirect')}}" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-user fs-6"></i>
                                <p class="mb-0 fs-3">My Profile</p>
                            </a>

                            <a href="{{ url('/logout') }}" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!--  Header End -->
