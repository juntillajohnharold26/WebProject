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
@endphp

<x-menu>
    <x-admin-sidebar>
        <section class="admin-page py-4 py-xl-5">
            <div class="admin-page__inner mx-auto">
                <div class="mb-4">
                    <a href="{{ url('/admin') }}" class="btn btn-outline-dark btn-sm mb-3" onclick="if (window.history.length > 1) { window.history.back(); return false; }">&larr; Back</a>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="small text-muted">Admin / Review Template</span>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">{{ session('error') }}</div>
                @endif

                <div class="row g-4">
                    {{-- Preview Images Column --}}
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-0">
                                @if(count($listing->preview_images))
                                    <div id="previewCarousel" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner rounded-4">
                                            @foreach($listing->preview_images as $index => $image)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <img src="{{ Storage::url($image) }}" class="d-block w-100" style="height: 450px; object-fit: cover;" alt="Preview {{ $index + 1 }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if(count($listing->preview_images) > 1)
                                            <button class="carousel-control-prev" type="button" data-bs-target="#previewCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#previewCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded-4" style="height: 450px;">
                                        <span class="text-muted">No preview images available</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Thumbnail Strip --}}
                        @if(count($listing->preview_images) > 1)
                            <div class="d-flex gap-2 overflow-auto pb-2">
                                @foreach($listing->preview_images as $index => $image)
                                    <img src="{{ Storage::url($image) }}" class="rounded-3" style="width: 100px; height: 75px; object-fit: cover; cursor: pointer;" data-bs-target="#previewCarousel" data-bs-slide-to="{{ $index }}">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Details Column --}}
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h1 class="h4 fw-bold mb-0">{{ $listing->title }}</h1>
                                    <span class="badge {{ $statusBadges[$listing->status] }} fs-6 px-3 py-2">{{ $statusLabels[$listing->status] ?? ucfirst($listing->status) }}</span>
                                </div>

                                <p class="text-muted mb-4">{{ $listing->description }}</p>

                                <div class="mb-4">
                                    <p class="text-uppercase small text-muted mb-2">Price</p>
                                    <h2 class="h3 fw-bold text-success mb-0">${{ number_format($listing->price, 2) }}</h2>
                                </div>

                                <div class="mb-4">
                                    <p class="text-uppercase small text-muted mb-2">Category</p>
                                    <span class="badge bg-dark fs-6 px-3 py-2">{{ $listing->category }}</span>
                                </div>

                                <div class="mb-4">
                                    <p class="text-uppercase small text-muted mb-2">Tags</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @forelse($listing->tags as $tag)
                                            <span class="badge bg-secondary">{{ $tag }}</span>
                                        @empty
                                            <span class="text-muted small">No tags</span>
                                        @endforelse
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="mb-4">
                                    <p class="text-uppercase small text-muted mb-2">Submitted By</p>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $listing->user->profile_avatar ?? 'https://static.vecteezy.com/system/resources/previews/046/010/545/non_2x/user-icon-simple-design-free-vector.jpg' }}" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" alt="{{ $listing->user->name }}">
                                        <div>
                                            <p class="fw-bold mb-0">{{ $listing->user->name ?? 'Unknown' }}</p>
                                            <p class="text-muted small mb-0">{{ $listing->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-uppercase small text-muted mb-2">Submitted On</p>
                                    <p class="mb-0">{{ $listing->created_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>

                                <hr class="my-4">

                                {{-- Action Buttons --}}
                                @if($listing->isPending())
                                    <div class="d-grid gap-3">
                                        <form method="POST" action="{{ route('admin.templates.approve', $listing) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-lg w-100 rounded-3">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                                    <path d="M20 6L9 17l-5-5"/>
                                                </svg>
                                                Approve Template
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.templates.reject', $listing) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-lg w-100 rounded-3" onclick="return confirm('Are you sure you want to reject this template?')">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                                    <path d="M18 6L6 18M6 6l12 12"/>
                                                </svg>
                                                Reject Template
                                            </button>
                                        </form>
                                    </div>
                                @elseif($listing->isActive())
                                    <div class="alert alert-success rounded-4 border-0">
                                        <p class="mb-0 fw-medium">This template was approved and is currently live on the marketplace.</p>
                                    </div>
                                @elseif($listing->isArchived())
                                    <div class="alert alert-secondary rounded-4 border-0">
                                        <p class="mb-0 fw-medium">This template was rejected and is archived.</p>
                                    </div>
                                @endif

                                <a href="{{ Storage::url($listing->zip_path) }}" class="btn btn-outline-dark w-100 rounded-3 mt-3" target="_blank">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                                    </svg>
                                    Download ZIP
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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

.card {
    border-radius: 1.25rem !important;
    overflow: hidden;
}

.btn {
    border-radius: 0.75rem;
}

.alert {
    border-radius: 1.25rem;
}

.carousel-control-prev,
.carousel-control-next {
    width: 10%;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 0 1rem 1rem 0;
    margin: 1rem;
    width: 48px;
    height: 48px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: all 0.3s ease;
}

.carousel:hover .carousel-control-prev,
.carousel:hover .carousel-control-next {
    opacity: 1;
}

.carousel-control-next {
    border-radius: 1rem 0 0 1rem;
}
</style>
