<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
    @forelse ($listings as $listing)
        <div class="col">
            <div class="card h-100 shadow-sm overflow-hidden" style="border-radius: 18px; border: 1px solid rgba(0,0,0,0.08);">
                <a href="{{ route('templates.show', $listing) }}" class="text-decoration-none">
                    <img src="{{ ! empty($listing->preview_images) ? Storage::url($listing->preview_images[0]) : 'https://via.placeholder.com/400x300?text=' . urlencode($listing->title) }}" 
                         class="card-img-top" alt="{{ $listing->title }}" style="height: 220px; object-fit: cover;">
                </a>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge fw-semibold px-2 py-1" style="background-color: #000000; color: white; font-size: 0.8rem; border-radius: 6px;">
                            {{ $listing->category }}
                        </span>
                        <span class="fw-bold fs-5">${{ number_format($listing->price, 2) }}</span>
                    </div>
                    <h6 class="fw-bold mb-2">{{ Str::limit($listing->title, 50) }}</h6>
                    <p class="text-muted small mb-2">{{ Str::limit($listing->description, 80) }}</p>
                    <a href="{{ route('sellers.show', $listing->user) }}" class="d-flex align-items-center mb-2 text-decoration-none">
                        <img src="{{ $listing->user->profile_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($listing->user->name) }}" 
                             class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;" alt="{{ $listing->user->name }}">
                        <span class="ms-2 small text-muted">{{ $listing->user->devsell_display_name ?? $listing->user->name }}</span>
                    </a>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @if($listing->reviews_count > 0)
                            <span class="badge bg-success text-white">
                                <i class="fa-regular fa-thumbs-up me-1"></i>
                                {{ $listing->positive_reviews_count }}
                            </span>
                            <span class="badge bg-danger text-white">
                                <i class="fa-regular fa-thumbs-down me-1"></i>
                                {{ $listing->negative_reviews_count }}
                            </span>
                            <span class="small text-muted">({{ $listing->reviews_count }} reviews)</span>
                        @else
                            <span class="small text-muted">No reviews yet</span>
                        @endif
                    </div>
                    @php
                        $currentUserId = session('user_id') ? (int) session('user_id') : null;
                        $isOwner = $currentUserId !== null && $listing->user_id === $currentUserId;
                    @endphp
                    @if ($isOwner)
                        <button type="button" class="btn btn-secondary btn-sm w-100" style="border-radius: 8px;" disabled>
                            Owned by you
                        </button>
                    @else
                        <form method="POST" action="{{ route('cart.add', $listing) }}">
                            @csrf
                            <button type="submit" class="btn btn-dark btn-sm w-100" style="border-radius: 8px;">Add to Cart</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-muted mb-3">No templates found.</h5>
            <a href="{{ url('/explore') }}" class="btn btn-outline-dark">Browse all templates</a>
        </div>
    @endforelse
</div>

{{ $listings->appends(request()->query())->links() }}
