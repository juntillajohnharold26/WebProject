<x-menu>
    <x-sidebar>
        <section class="py-5">
            <div class="container-fluid px-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-dark btn-sm">← Back</a>
                    </div>
                </div>

                <div class="row align-items-center mb-4">
                    <div class="col-lg-8">
                        <h1 class="display-6 fw-bold">Notifications</h1>
                        <p class="text-muted">See updates about your account, orders, and marketplace activity.</p>
                    </div>
                </div>

                <div class="list-group">
                    @forelse($notifications as $notification)
                        <div class="list-group-item rounded-4 shadow-sm mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1">{{ $notification['title'] }}</h5>
                                    <p class="mb-1 text-muted small">{{ $notification['body'] }}</p>
                                </div>
                                <span class="badge {{ $notification['badge_class'] ?? 'bg-primary' }}">{{ $notification['badge'] ?? 'Update' }}</span>
                            </div>
                            <small class="text-muted">{{ $notification['time'] ?? 'Just now' }}</small>
                        </div>
                    @empty
                        <div class="list-group-item rounded-4 shadow-sm text-center py-5">
                            <h5 class="fw-bold mb-2">No notifications yet</h5>
                            <p class="text-muted mb-0">When you purchase a template, your order update will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .list-group-item {
        transition: all 0.3s ease;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .badge {
        font-size: 0.75rem;
    }
</style>
