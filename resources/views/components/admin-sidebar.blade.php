<aside class="sidebar-panel d-none d-lg-block bg-dark text-light">
    <div class="sidebar-inner p-4">
        <h6 class="text-muted text-uppercase mb-4">Admin</h6>
        <nav class="nav flex-column gap-2 mb-4">
            <a href="{{ url('/admin') }}" class="nav-link text-light text-decoration-none small {{ request()->is('admin') ? 'bg-primary bg-opacity-25 border-start border-primary border-3' : '' }}">Dashboard</a>
            <a href="{{ url('/admin?status=pending') }}" class="nav-link text-light text-decoration-none small {{ request()->input('status') === 'pending' ? 'bg-primary bg-opacity-25 border-start border-primary border-3' : '' }}">Pending Review</a>
            <a href="{{ url('/admin?status=active') }}" class="nav-link text-light text-decoration-none small {{ request()->input('status') === 'active' ? 'bg-primary bg-opacity-25 border-start border-primary border-3' : '' }}">Approved</a>
            <a href="{{ url('/admin?status=archived') }}" class="nav-link text-light text-decoration-none small {{ request()->input('status') === 'archived' ? 'bg-primary bg-opacity-25 border-start border-primary border-3' : '' }}">Rejected</a>
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
        color: rgba(255, 255, 255, 0.78) !important;
        font-weight: 700;
        letter-spacing: 0;
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
