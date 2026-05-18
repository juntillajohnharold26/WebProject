@php
    $statusBadges = [
        'active' => 'bg-success',
        'pending' => 'bg-info text-dark',
        'draft' => 'bg-warning text-dark',
        'archived' => 'bg-secondary',
    ];

@endphp

<x-menu>
    <x-sidebar>
        <section class="templates-page py-4 py-xl-5">
            <div class="templates-page__inner mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ url('/account') }}" class="btn btn-outline-dark btn-sm mb-3" onclick="if (window.history.length > 1) { window.history.back(); return false; }">&larr; Back</a>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="small text-muted">Seller dashboard</span>
                        </div>
                    </div>
                </div>

                <div class="card rounded-4 border-0 shadow-sm mb-4 p-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-start">
                        <div>
                            <p class="text-uppercase small text-muted mb-2">Store Profile</p>
                            <h2 class="h4 fw-bold mb-2">{{ $storeName }}</h2>
                            <p class="mb-2 text-muted">{{ $storeSpecialty }}</p>
                            <p class="mb-0">{{ $storeBio }}</p>
                        </div>
                        <div class="d-flex flex-column gap-3 align-items-start align-items-lg-end">
                            <div class="d-flex flex-column flex-sm-row gap-2 align-items-start align-items-sm-center">
                                <div class="small text-muted">
                                    <p class="mb-1"><strong>{{ number_format($activeTemplates) }}</strong> active templates</p>
                                    <p class="mb-0"><strong>{{ number_format($pendingTemplates) }}</strong> pending review</p>
                                </div>
                            </div>
                            <div class="store-sales-summary text-start text-lg-end">
                                <div class="d-flex flex-wrap justify-content-start justify-content-lg-end gap-4 mb-2">
                                    <div>
                                        <span class="small text-uppercase text-muted d-block">Last 7 days</span>
                                        <strong class="fs-5">{{ number_format($recentSalesCount) }}</strong>
                                        <span class="small text-muted">sales</span>
                                    </div>
                                    <div>
                                        <span class="small text-uppercase text-muted d-block">Revenue</span>
                                        <strong class="fs-5">${{ number_format($recentRevenue, 2) }}</strong>
                                    </div>
                                    <div>
                                        <span class="small text-uppercase text-muted d-block">All-time revenue</span>
                                        <strong class="fs-5">${{ number_format($totalRevenue, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="store-sales-days d-flex flex-wrap justify-content-start justify-content-lg-end gap-2">
                                    @foreach($dailySales as $day)
                                        <div class="store-sales-day">
                                            <span class="small text-muted d-block">{{ $day['label'] }}</span>
                                            <strong>{{ number_format($day['sales']) }}</strong>
                                            <span class="small text-muted">${{ number_format($day['revenue'], 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-0">Your Templates</h1>
                    </div>
                    <a href="{{ route('seller.templates.create') }}" class="btn btn-dark px-4">+ New Template</a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card rounded-4 border-0 shadow-sm p-4 h-100">
                            <div class="mb-3">
                                <span class="small text-uppercase text-muted">Templates</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1">{{ number_format($totalTemplates) }}</h2>
                            <p class="text-muted mb-0">Listings you currently manage.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card rounded-4 border-0 shadow-sm p-4 h-100">
                            <div class="mb-3">
                                <span class="small text-uppercase text-muted">Sales</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1">{{ number_format($salesCount) }}</h2>
                            <p class="text-muted mb-0">Times your templates were purchased.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card rounded-4 border-0 shadow-sm p-4 h-100">
                            <div class="mb-3">
                                <span class="small text-uppercase text-muted">Reviews</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1">{{ number_format($totalReviews) }}</h2>
                            <p class="text-muted mb-0">Feedback received across your templates.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card rounded-4 border-0 shadow-sm p-4 h-100">
                            <div class="mb-3">
                                <span class="small text-uppercase text-muted">Rating</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1">{{ $averageRating ? number_format($averageRating, 1) : '—' }}</h2>
                            <p class="text-muted mb-0">Average rating based on reviews.</p>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

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
                                        <h6 class="card-title fw-bold">{{ $listing->title }}</h6>
                                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($listing->description, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="badge {{ $statusBadges[$listing->status ?? 'draft'] }} fs-6 px-3 py-2">{{ ucfirst($listing->status ?? 'draft') }}</span>
                                            <span class="fw-bold text-success">${{ number_format($listing->price, 2) }}</span>
                                        </div>
                                        <div class="mt-3">
                                            @if($listing->reviews_count > 0)
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="badge bg-success text-white">
                                                        <i class="fa-regular fa-thumbs-up me-1"></i>
                                                        {{ $listing->positive_reviews_count }}
                                                    </span>
                                                    <span class="badge bg-danger text-white">
                                                        <i class="fa-regular fa-thumbs-down me-1"></i>
                                                        {{ $listing->negative_reviews_count }}
                                                    </span>
                                                    <span class="small text-muted">({{ $listing->reviews_count }} reviews)</span>
                                                </div>
                                                @foreach($listing->reviews as $review)
                                                    <div class="border rounded-3 p-3 mb-2 bg-light">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="small fw-semibold">{{ $review->user->name }}</span>
                                                            <span class="small text-{{ $review->rating >= 4 ? 'success' : 'danger' }}">
                                                                <i class="fas fa-thumbs-{{ $review->rating >= 4 ? 'up' : 'down' }}"></i>
                                                                {{ $review->rating >= 4 ? 'Positive' : 'Negative' }}
                                                            </span>
                                                        </div>
                                                        <p class="small text-muted mb-0">{{ Str::limit($review->comment ?? 'No comment', 120) }}</p>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="small text-muted">No reviews yet</span>
                                            @endif
                                        </div>
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-grid gap-2 d-md-flex">
                                                <a href="{{ url('/seller/templates/' . $listing->id . '/edit') }}" class="btn btn-outline-primary btn-sm flex-fill">Edit</a>
                                                <form method="POST" action="{{ url('/seller/templates/' . $listing->id) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this template?')">Delete</button>
                                                </form>
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
                                <path d="M12 2L13.09 8.26L19.94 9L12 2Z" fill="currentColor"/>
                                <path d="M10.91 15.74L12 2L19.94 9L12 15.74L10.91 15.74Z" fill="currentColor"/>
                                <path opacity="0.4" d="M10.91 15.74L12 15.74L5.06 9L12 2L10.91 15.74Z" fill="currentColor"/>
                            </svg>
                            <h3 class="h5 fw-bold mb-2">No templates yet</h3>
                            <p class="text-muted mb-4">Create your first template listing to start selling your designs.</p>
                            <a href="{{ route('seller.templates.create') }}" class="btn btn-dark px-5">Create Template</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
.templates-page {
    background: #f8fafc;
}

.templates-page__inner {
    max-width: 1200px;
}

.empty-icon {
    color: #64748b;
}

.store-sales-summary {
    max-width: 680px;
}

.store-sales-day {
    min-width: 74px;
    padding: 0.55rem 0.7rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    background: #f8fafc;
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
</style>
