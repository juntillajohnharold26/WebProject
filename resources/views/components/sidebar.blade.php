<aside class="sidebar-panel d-none d-lg-block bg-dark text-light">
    <div class="sidebar-inner p-4">
        <h6 class="text-muted text-uppercase mb-4">Account</h6>
        <nav class="nav flex-column gap-2 mb-4">
            <a href="{{ url('/account') }}" class="nav-link text-light text-decoration-none small">Account Info</a>
            <a href="{{ url('/seller/templates') }}" class="nav-link text-light text-decoration-none small {{ request()->is('seller/templates*') ? 'bg-primary bg-opacity-25 border-start border-primary border-3' : '' }}">Templates</a>
            <a href="{{ url('/purchases') }}" class="nav-link text-light text-decoration-none small">Purchases</a>
            <a href="{{ url('/favorites') }}" class="nav-link text-light text-decoration-none small">Favorites</a>
        </nav>

        <hr class="border-secondary">

        <h6 class="text-muted text-uppercase mb-4">Support</h6>
        <nav class="nav flex-column gap-2">
            <a href="{{ url('/about') }}" class="nav-link text-light text-decoration-none small">Help Center</a>
            <a href="{{ url('/contact') }}" class="nav-link text-light text-decoration-none small">Contact Us</a>
        </nav>
    </div>
</aside>

<main class="sidebar-content">
    <div class="p-4">
        {{ $slot }}
    </div>
</main>

<style>
    .sidebar-panel {
        position: fixed;
        top: 60px;
        left: 0;
        width: 180px;
        height: calc(100vh - 60px);
        overflow-y: auto;
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        margin-top: -3px;
        backdrop-filter: blur(10px);
        background: rgba(15, 23, 42, 0.96);
        box-shadow: 2px 0 30px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .sidebar-inner {
        max-width: 180px;
    }

    .sidebar-content {
        margin-left: 180px;
        min-height: 100vh;
        background-color: #f4f5f8;
        padding: 2rem 2.25rem;
    }

    .sidebar-panel .nav-link {
        padding: 0.5rem 0;
        transition: all 0.3s ease;
        border-radius: 8px;
        margin: 2px 0;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .sidebar-panel .nav-link:hover {
        text-decoration: none;
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
        box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
    }

    .sidebar-panel h6 {
        transition: all 0.3s ease;
        padding-left: 1rem;
    }

    .sidebar-panel h6:hover {
        color: rgba(255, 255, 255, 0.9) !important;
        transform: translateX(3px);
    }

    @media (max-width: 991.98px) {
        .sidebar-panel {
            display: none;
        }

        .sidebar-content {
            margin-left: 0;
        }
    }
</style>
