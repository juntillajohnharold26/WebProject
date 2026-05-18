<x-menu>
    <x-sidebar>
        <section class="py-5">
            <div class="container-fluid px-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="{{ url('/explore') }}" class="btn btn-outline-dark btn-sm" onclick="if (window.history.length > 1) { window.history.back(); return false; }">← Back</a>
                    </div>
                </div>

                <div class="row align-items-center mb-4">
                    <div class="col-lg-8">
                        <h1 class="display-6 fw-bold">Inbox</h1>
                        <p class="text-muted">Your latest messages from sellers, collaborators, and support.</p>
                    </div>
                </div>

                <div class="list-group">
                    @forelse($messages as $message)
                        @php $isRead = ! empty($message['read']); @endphp
                        <div class="list-group-item rounded-4 shadow-sm mb-3 d-flex justify-content-between align-items-start">
                            <div class="me-3 flex-grow-1">
                                <a href="{{ url('/messages/' . $message['id']) }}" class="text-decoration-none text-dark">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="mb-1">{{ $message['sender'] }} @unless($isRead)<span class="badge bg-primary ms-2">New</span>@endunless</h5>
                                            <p class="mb-1 text-muted small">{{ $message['preview'] ?? $message['subject'] }}</p>
                                        </div>
                                        <small class="text-muted">{{ $message['time'] ?? 'Just now' }}</small>
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex flex-column gap-2 align-items-end">
                                <form method="POST" action="{{ route('messages.toggle-read', $message['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">{{ $isRead ? 'Mark Unread' : 'Mark Read' }}</button>
                                </form>
                                <form method="POST" action="{{ route('messages.delete', $message['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item rounded-4 shadow-sm text-center py-5">
                            <h5 class="fw-bold mb-2">No messages yet</h5>
                            <p class="text-muted mb-0">Purchase confirmations and order messages will show up here.</p>
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
        border: 1px solid rgba(0, 0, 0, 0.06);
        background-color: #fff;
    }

    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .list-group-item h5 {
        margin-bottom: 0.25rem;
    }
</style>
