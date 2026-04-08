<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sistem Parkir - @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">

    <!-- Bootstrap & Style -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Spinner -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        {{-- ===================== SIDEBAR START ===================== --}}
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">

                <!-- Brand -->
                <a href="#" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-parking me-2"></i>SistemParkir</h3>
                </a>

                <!-- Info User -->
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{ asset('img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <span class="text-capitalize">{{ Auth::user()->role }}</span>
                    </div>
                </div>

                <!-- Menu -->
                <div class="navbar-nav w-100">

                    {{-- ADMIN --}}
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-item nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fa fa-users me-2"></i>Kelola User
                    </a>
                    <a href="{{ route('admin.kendaraan') }}" class="nav-item nav-link {{ request()->routeIs('admin.kendaraan*') ? 'active' : '' }}">
                        <i class="fa fa-car me-2"></i>Kendaraan
                    </a>
                    <a href="{{ route('admin.area') }}" class="nav-item nav-link {{ request()->routeIs('admin.area*') ? 'active' : '' }}">
                        <i class="fa fa-map me-2"></i>Area Parkir
                    </a>
                    <a href="{{ route('admin.tarif') }}" class="nav-item nav-link {{ request()->routeIs('admin.tarif*') ? 'active' : '' }}">
                        <i class="fa fa-tags me-2"></i>Tarif
                    </a>
                    <a href="{{ route('admin.laporan') }}" class="nav-item nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                        <i class="fa fa-chart-bar me-2"></i>Laporan
                    </a>

                    {{-- PETUGAS --}}
                    @elseif(Auth::user()->role === 'petugas')
                    <a href="{{ route('petugas.dashboard') }}" class="nav-item nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('petugas.kendaraan') }}" class="nav-item nav-link {{ request()->routeIs('petugas.kendaraan*') ? 'active' : '' }}">
                        <i class="fa fa-car me-2"></i>Kendaraan
                    </a>
                    <a href="{{ route('petugas.transaksi') }}" class="nav-item nav-link {{ request()->routeIs('petugas.transaksi*') ? 'active' : '' }}">
                        <i class="fa fa-exchange-alt me-2"></i>Transaksi
                    </a>
                    <a href="{{ route('petugas.riwayat') }}" class="nav-item nav-link {{ request()->routeIs('petugas.riwayat*') ? 'active' : '' }}">
                        <i class="fa fa-history me-2"></i>Riwayat
                    </a>

                    {{-- OWNER --}}
                    @elseif(Auth::user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}" class="nav-item nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('owner.laporan') }}" class="nav-item nav-link {{ request()->routeIs('owner.laporan*') ? 'active' : '' }}">
                        <i class="fa fa-chart-line me-2"></i>Laporan
                    </a>
                    <a href="{{ route('owner.pendapatan') }}" class="nav-item nav-link {{ request()->routeIs('owner.pendapatan*') ? 'active' : '' }}">
                        <i class="fa fa-money-bill me-2"></i>Pendapatan
                    </a>
                    @endif

                </div>
            </nav>
        </div>
        {{-- ===================== SIDEBAR END ===================== --}}


        <!-- Content -->
        <div class="content">

            {{-- ===================== NAVBAR START ===================== --}}
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="#" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-parking"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">

                    <!-- Notifikasi -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notifikasi</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item text-center">Tidak ada notifikasi</a>
                        </div>
                    </div>

                    <!-- Profile & Logout -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fa fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </nav>
            {{-- ===================== NAVBAR END ===================== --}}


            {{-- Konten halaman --}}
            @yield('content')


            {{-- ===================== FOOTER START ===================== --}}
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; {{ date('Y') }} <a href="#">Sistem Parkir</a>. All Rights Reserved.
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            Login sebagai: <strong class="text-primary text-capitalize">{{ Auth::user()->role }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ===================== FOOTER END ===================== --}}

        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
            <i class="bi bi-arrow-up"></i>
        </a>

    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>