<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Parkir - Register Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Register Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="#" class="navbar-brand mx-4 mb-3">
                                <h3 class="text-primary"><i class="fa fa-parking me-2"></i>SistemParkir</h3>
                            </a>
                            <h3>Register Admin</h3>
                        </div>

                        {{-- Alert Error --}}
                        @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf

                            {{-- Nama --}}
                            <div class="form-floating mb-3">
                                <input
                                    class="form-control @error('name') is-invalid @enderror"
                                    name="name"
                                    type="text"
                                    id="name"
                                    placeholder="Masukkan nama anda"
                                    value="{{ old('name') }}"
                                    required>
                                <label for="name">Nama Lengkap</label>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-floating mb-3">
                                <input
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email"
                                    type="email"
                                    id="email"
                                    placeholder="Masukkan Email Anda"
                                    value="{{ old('email') }}"
                                    required>
                                <label for="email">Email Address</label>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input
                                        class="form-control @error('password') is-invalid @enderror"
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="Masukkan Password"
                                        required>
                                    <label for="password">Password</label>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="form-floating mb-4">
                                <input
                                    class="form-control"
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    required>
                                <label for="password_confirmation">Konfirmasi Password</label>
                            </div>

                            {{-- Info role --}}
                            <div class="alert alert-info py-2 mb-3" style="font-size: 0.85rem;">
                                <i class="bi bi-info-circle me-1"></i>
                                Akun yang dibuat akan otomatis menjadi <strong>Admin</strong>.
                            </div>

                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">
                                <i class="fa fa-user-plus me-2"></i>Buat Akun Admin
                            </button>

                            <p class="text-center mb-0">
                                Sudah punya akun? <a href="{{ route('login') }}">Sign In</a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Register End -->

    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>