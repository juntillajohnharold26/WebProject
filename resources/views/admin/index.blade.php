@php
    $statusBadges = [
        'pending' => 'bg-warning text-dark',
        'active' => 'bg-success',
        'draft' => 'bg-secondary',
        'archived' => 'bg-muted text-dark',
    ];

    $statusLabels = [
        'pending' => 'Pending Review',
        'active' => 'Approved',
        'draft' => 'Draft',
        'archived' => 'Rejected',
    ];

    $filterLabels = [
        'pending' => 'Pending Review',
        'active' => 'Approved',
        'draft' => 'Drafts',
        'archived' => 'Rejected',
        'all' => 'All Templates',
    ];

    $currentFilter = $status ?? 'pending';
    $currentLabel = $filterLabels[$currentFilter] ?? 'Templates';
@endphp

<x-menu>
    <x-admin-sidebar>
        <section class="admin-page py-4 py-xl-5">
            <div class="admin-page__inner mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="small text-muted">Admin dashboard</span>
                        </div>
                        <h1 class="h3 fw-bold mb-0">Review Templates</h1>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">{{ session('error') }}</div>
                @endif

                {{-- Stats Cards --}}
                <div class="row g-3 mb-5">
                    @foreach (['pending', 'active', 'archived', 'all'] as $stat)
                        <div class="col-sm-6 col-xl-3">
                            <a href="{{ url('/admin?status=' . ($stat === 'all' ? '' : $stat)) }}" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 {{ $currentFilter === $stat || ($currentFilter === 'pending' && $stat === 'pending') ? 'border border-primary border-2' : '' }}">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted small mb-1">{{ $filterLabels[$stat] }}</p>
                                            <h3 class="fw-bold mb-0">{{ $counts[$stat] ?? 0 }}</h3>
                                        </div>
                                        <span class="badge {{ $statusBadges[$stat] ?? 'bg-light text-dark' }} fs-5 px-3 py-2">{{ $counts[$stat] ?? 0 }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h5 fw-bold mb-0">Pending DevSell Requests</h2>
                    <span class="text-muted small">{{ $counts['pending_sellers'] ?? 0 }} seller request(s)</span>
                </div>

                @if($pendingSellers->count() > 0)
                    <div class="row g-4 mb-5">
                        @foreach($pendingSellers as $seller)
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $seller->profile_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($seller->name) }}" class="rounded-circle" style="width: 52px; height: 52px; object-fit: cover;" alt="{{ $seller->name }}">
                                                <div>
                                                    <h3 class="h6 fw-bold mb-1">{{ $seller->devsell_display_name ?? $seller->name }}</h3>
                                                    <p class="small text-muted mb-0">{{ $seller->email }}</p>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if(($seller->devsell_active ?? false) && !empty($seller->devsell_editing))
                                                    <span class="badge bg-info text-dark">Editing in progress</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Waiting for approval</span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="small text-muted mb-1"><strong>Store:</strong> {{ $seller->devsell_store_name }}</p>
                                        <p class="small text-muted mb-1"><strong>Specialty:</strong> {{ $seller->devsell_specialty }}</p>
                                        <p class="small text-muted">{{ Str::limit($seller->devsell_bio, 110) }}</p>

                                        <div class="d-grid gap-2 d-md-flex pt-3 border-top">
                                            @if (($seller->devsell_active ?? false) && !empty($seller->devsell_editing))
                                                <form method="POST" action="{{ route('admin.devsell.approve', $seller) }}" class="flex-fill">
                                                    @csrf
                                                    <button type="submit" name="clear_editing" value="1" class="btn btn-outline-dark btn-sm w-100">Clear editing</button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.devsell.approve', $seller) }}" class="flex-fill">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.devsell.reject', $seller) }}" class="flex-fill">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Reject this DevSell request?')">Reject</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-light border rounded-4 mb-5">No pending DevSell requests right now.</div>
                @endif


                {{-- Templates Grid --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h5 fw-bold mb-0">{{ $currentLabel }}</h2>
                    <span class="text-muted small">{{ $listings->total() }} template(s) found</span>
                </div>

                @if($listings->count() > 0)
                    <div class="row g-4">
                        @foreach($listings as $listing)
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 shadow-sm border-0">
                                    @if(count($listing->preview_images))
                                        <img src="{{ Storage::url($listing->preview_images[0]) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <span class="text-muted">No preview</span>
                                        </div>
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title fw-bold mb-0">{{ $listing->title }}</h6>
                                            <span class="badge {{ $statusBadges[$listing->status] }} fs-6 px-3 py-2">{{ $statusLabels[$listing->status] ?? ucfirst($listing->status) }}</span>
                                        </div>
                                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($listing->description, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="small text-muted">By {{ $listing->user->name ?? 'Unknown' }}</span>
                                            <span class="fw-bold text-success">${{ number_format($listing->price, 2) }}</span>
                                        </div>
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-grid gap-2 d-md-flex">
                                                <a href="{{ route('admin.templates.show', $listing) }}" class="btn btn-outline-dark btn-sm flex-fill">Review</a>
                                                @if($listing->isPending())
                                                    <form method="POST" action="{{ route('admin.templates.approve', $listing) }}" class="d-inline flex-fill">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm w-100">Approve</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.templates.reject', $listing) }}" class="d-inline flex-fill">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Reject this template?')">Reject</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        {{ $listings->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="empty-state mb-4">
                            <svg class="empty-icon mb-3" width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h3 class="h5 fw-bold mb-2">No templates to review</h3>
                            <p class="text-muted mb-4">All caught up! There are no {{ strtolower($currentLabel) }} at the moment.</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </x-admin-sidebar>
</x-menu>

<style>
.admin-page {
    background: #f8fafc;
}

.admin-page__inner {
    max-width: 1200px;
}

.empty-icon {
    color: #64748b;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
    border-radius: 1.25rem !important;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.btn {
    border-radius: 0.75rem;
}

.alert {
    border-radius: 1.25rem;
}

.py-10 {
    padding-top: 6rem;
    padding-bottom: 6rem;
}
</style>
