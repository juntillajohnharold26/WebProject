<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DevBuy - Browse premium digital designs and templates.">
    <title>DevBuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            background-color: #f4f5f8;
            color: #111;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(17, 24, 39, 0.96) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            color: #ffffff !important;
        }
        
        .btn-outline-light {
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.16);
        }
        
        .form-control {
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
            padding: 0.55rem 1rem;
            min-width: 260px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.72);
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 0.55);
            background-color: rgba(255, 255, 255, 0.12);
            transition: all 0.3s ease;
        }
        
        .dropdown-menu {
            backdrop-filter: blur(10px);
            background: rgba(17, 24, 39, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .dropdown-item {
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">DevBuy</span>
            <form class="d-flex mx-auto flex-grow-1" style="max-width: 500px;">
                <input class="form-control form-control-sm me-2" type="search" placeholder="Search templates, designs..." aria-label="Search">
                <button class="btn btn-outline-light btn-sm" type="submit" title="Search">🔍</button>
            </form>
            <div class="d-flex gap-2 align-items-center">
                <button class="btn btn-outline-light btn-sm" title="Notifications" data-bs-toggle="modal" data-bs-target="#notificationsModal">🔔</button>
                <a href="{{ url('/inbox') }}" class="btn btn-outline-light btn-sm" title="Inbox">✉️</a>
                <div class="dropdown">
                    @php
                        $menuUser = session('user_id') ? \App\Models\User::find(session('user_id')) : null;
                        $avatarUrl = $menuUser?->profile_avatar ?: 'https://static.vecteezy.com/system/resources/previews/046/010/545/non_2x/user-icon-simple-design-free-vector.jpg';
                    @endphp
                    <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center p-0" type="button" id="profileMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ $avatarUrl }}" alt="Profile avatar" class="rounded-circle" width="32" height="32" style="object-fit:cover; border:1px solid rgba(255,255,255,0.4);" />
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenuButton">
                        <li><a class="dropdown-item text-white" href="{{ url('/account') }}">Account</a></li>
                        <li><a class="dropdown-item text-white" href="#">Switch Account</a></li>
                        <li><a class="dropdown-item text-white" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logoutMenuForm').submit();">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <form id="logoutMenuForm" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group mb-3">
                        <a href="{{ url('/messages/1') }}" class="list-group-item list-group-item-action rounded-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Order confirmed</h6>
                                    <p class="mb-1 text-muted small">Your order for the E-commerce UI Kit has been confirmed.</p>
                                </div>
                                <small class="text-muted">10 minutes ago</small>
                            </div>
                        </a>
                        <a href="{{ url('/messages/2') }}" class="list-group-item list-group-item-action rounded-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Billing update</h6>
                                    <p class="mb-1 text-muted small">Your payment method was successfully updated.</p>
                                </div>
                                <small class="text-muted">Yesterday</small>
                            </div>
                        </a>
                        <a href="{{ url('/messages/3') }}" class="list-group-item list-group-item-action rounded-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">New feature</h6>
                                    <p class="mb-1 text-muted small">We added new dashboard templates to the Explore page.</p>
                                </div>
                                <small class="text-muted">2 days ago</small>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <a href="{{ url('/notifications') }}" class="btn btn-outline-dark btn-sm">View all notifications</a>
                </div>
            </div>
        </div>
    </div>

    <div>
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>
