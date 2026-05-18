<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DevBuy - Browse premium digital designs and templates.">
    <title>DevBuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
    @php
        $isAdminArea = request()->is('admin*');
        $menuUser = session('user_id') ? \App\Models\User::find(session('user_id')) : null;
    @endphp
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <a href="{{ $isAdminArea ? url('/admin') : url('/explore') }}" class="navbar-brand fw-bold text-decoration-none">
                {{ $isAdminArea ? 'DevBuy Admin' : 'DevBuy' }}
            </a>
            @unless($isAdminArea)
            <form method="GET" action="{{ url('/search') }}" class="d-flex mx-auto flex-grow-1" style="max-width: 500px;">
                <input class="form-control form-control-sm me-2 text-white" type="search" name="q" value="{{ request('q') }}" placeholder="Search templates, designs..." aria-label="Search">
                <button class="btn btn-outline-light btn-sm" type="submit" title="Search">🔍</button>
            </form>
            @else
                <div class="mx-auto text-light small fw-semibold">Template Review Console</div>
            @endunless
            <div class="d-flex gap-2 align-items-center">
                @unless($isAdminArea)
                @php
                    $navNotifications = session('notifications', []);
                    $navMessages = session('messages', []);
                    if ($menuUser) {
                        $savedNotifications = $menuUser->marketplaceNotifications()
                            ->latest()
                            ->take(5)
                            ->get()
                            ->map(fn ($notification) => [
                                'title' => $notification->title,
                                'body' => $notification->body,
                                'badge' => $notification->badge,
                                'badge_class' => $notification->badge_class,
                                'time' => $notification->created_at->diffForHumans(),
                            ])
                            ->all();
                        $navNotifications = array_values(array_merge($savedNotifications, $navNotifications));
                    }
                @endphp
                <button class="btn btn-outline-light btn-sm" title="Notifications" data-bs-toggle="modal" data-bs-target="#notificationsModal">🔔</button>
                @php
                    $navMessages = session('messages', []);
                    $unreadCount = collect($navMessages)->filter(fn($m) => empty($m['read']))->count();
                @endphp
                <a href="{{ url('/inbox') }}" class="btn btn-outline-light btn-sm position-relative" title="Inbox">✉️
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ url('/cart') }}" class="btn btn-outline-light btn-sm position-relative" title="Cart">
                    🛒
                    @php
                        $navCartCount = count(session('cart', []));
                    @endphp
                    @if($navCartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            {{ $navCartCount }}
                        </span>
                    @endif
                </a>
                @endunless
                <div class="dropdown">
                    @php
                        $avatarUrl = $menuUser?->profile_avatar ?: 'https://static.vecteezy.com/system/resources/previews/046/010/545/non_2x/user-icon-simple-design-free-vector.jpg';
                    @endphp
                    <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center p-0" type="button" id="profileMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ $avatarUrl }}" alt="Profile avatar" class="rounded-circle" width="32" height="32" style="object-fit:cover; border:1px solid rgba(255,255,255,0.4);" />
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenuButton">
                        @unless($isAdminArea)
                        <li><a class="dropdown-item text-white" href="{{ url('/account') }}">Account</a></li>
                        @endunless
                        @if($menuUser && $menuUser->is_admin && ! $isAdminArea)
                            <li><a class="dropdown-item text-warning" href="{{ url('/admin') }}">Admin Dashboard</a></li>
                        @endif
                        @unless($isAdminArea)
                        <li><a class="dropdown-item text-white" href="{{ route('switch-account') }}">Switch Account</a></li>

                        @endunless
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

    @unless($isAdminArea)
    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group mb-3">
                        @forelse($navNotifications as $notification)
                            <div class="list-group-item rounded-4 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $notification['title'] }}</h6>
                                        <p class="mb-1 text-muted small">{{ $notification['body'] }}</p>
                                    </div>
                                    <span class="badge {{ $notification['badge_class'] ?? 'bg-primary' }}">{{ $notification['badge'] ?? 'Update' }}</span>
                                </div>
                                <small class="text-muted">{{ $notification['time'] ?? 'Just now' }}</small>
                            </div>
                        @empty
                            <div class="list-group-item rounded-4 text-center py-4">
                                <p class="text-muted small mb-0">Marketplace notifications will appear here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <a href="{{ url('/notifications') }}" class="btn btn-outline-dark btn-sm">View all notifications</a>
                </div>
            </div>
        </div>
    </div>
    @endunless

    <div>
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>
