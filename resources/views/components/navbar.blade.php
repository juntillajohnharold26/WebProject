<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DevBuy is your smart destination for tech services, digital designs, and reliable online solutions.">
    <title>DevBuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            background-color: #000;
            color: #fff;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.95) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            transition: all 0.3s ease;
        }
        
        .navbar-brand,
        .nav-link,
        .navbar-toggler-icon {
            color: #fff !important;
        }

        .navbar-brand {
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            color: #fff !important;
        }
        
        .nav-link {
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.85) !important;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
            color: #fff !important;
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn-outline-light,
        .btn-light {
            color: #000 !important;
            background-color: #fff !important;
            border-color: #fff !important;
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.9) !important;
            border-color: #fff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.22);
        }
        
        .btn-light:hover {
            background-color: #fff !important;
            color: #000 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
        
        .bg-light {
            background-color: #fff !important;
            color: #000 !important;
        }

        .bg-dark {
            background-color: #000 !important;
            color: #fff !important;
        }

        .card {
            background-color: #fff !important;
            color: #000 !important;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" id="homepage" href="{{ url('/') }}">DevBuy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/explore') }}">Explore</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                    </li>
                </ul>
                <div class="d-flex ms-auto align-items-center gap-2">
                    @if (session('authenticated'))
                        <a href="{{ url('/account') }}" class="btn btn-outline-light btn-sm">Account</a>
                        <a href="#" onclick="document.getElementById('logoutForm').submit(); return false;" class="btn btn-light btn-sm">Log out</a>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('signup') }}" class="btn btn-outline-light btn-sm me-3">Sign up</a>
                        <a href="{{ route('login') }}" class="btn btn-light btn-sm">Log in</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-5">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>