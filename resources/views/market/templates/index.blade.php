<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
    @forelse ($listings as $listing)
        <div class="col">
            <div class="card h-100 shadow-sm overflow-hidden" style="border-radius: 18px; border: 1px solid rgba(0,0,0,0.08);">
                <a href="{{ route('templates.show', $listing) }}" class="text-decoration-none">
                    <img src="{{ $listing->preview_images[0] ?? 'https://via.placeholder.com/400x300?text=' . urlencode($listing->title) }}" 
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
                    <div class="d-flex align-items-center">
                        <img src="{{ $listing->user->profile_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($listing->user->name) }}" 
                             class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;" alt="{{ $listing->user->name }}">
                        <span class="ms-2 small text-muted">{{ $listing->user->devsell_display_name ?? $listing->user->name }}</span>
                    </div>
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

