<x-menu>
    <x-sidebar>
        <div class="container-fluid px-4 py-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-dark btn-sm mb-4">&larr; Back</a>

            <div class="row g-5">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="bg-white rounded-4 shadow-sm p-5 mb-4">
                        <h1 class="display-6 fw-bold mb-4">{{ $listing->title }}</h1>
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="badge fs-6 px-3 py-2 fw-semibold" style="background-color: #000000; color: white; border-radius: 10px;">
                                {{ $listing->category }}
                            </span>
                            <span class="fw-bold fs-4">${{ number_format($listing->price, 2) }}</span>
                        </div>

                        <!-- Preview Images Carousel -->
                        @if($listing->preview_images && count($listing->preview_images) > 0)
                        <div id="previewCarousel{{ $listing->id }}" class="carousel slide shadow-sm rounded-4 mb-5 overflow-hidden" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($listing->preview_images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($image) }}" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="{{ $listing->title }} preview {{ $index + 1 }}">
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#previewCarousel{{ $listing->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#previewCarousel{{ $listing->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                        @else
                        <img src="https://via.placeholder.com/800x500?text={{ urlencode($listing->title) }}" class="img-fluid rounded-3 shadow-sm mb-4" style="height: 500px; object-fit: cover;" alt="{{ $listing->title }}">
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-3">Description</h4>
                                <p class="lead text-muted">{!! nl2br(e($listing->description)) !!}</p>
                            </div>
                            <div class="col-md-4">
                                @if($listing->tags && count($listing->tags) > 0)
                                <h6 class="fw-bold mb-3">Tags</h6>
                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    @foreach($listing->tags as $tag)
                                    <span class="badge bg-light border px-2 py-1 small text-dark">{{ $tag }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Stub (expand later) -->
                    <div class="bg-white rounded-4 shadow-sm p-5">
                        <h4 class="fw-bold mb-4">Reviews</h4>
                        <p class="text-muted">Reviews coming soon. Be the first to review this template!</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 sticky-top" style="top: 2rem;">
                        <div class="d-flex align-items-center mb-4">
                            <img src="{{ $listing->user->profile_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($listing->user->name ?? 'Seller') }}" 
                                 class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="{{ $listing->user->name }}">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $listing->user->devsell_display_name ?? $listing->user->name ?? 'Seller' }}</h6>
                                <small class="text-muted">{{ $listing->user->devsell_specialty ?? 'Designer' }}</small>
                            </div>
                        </div>
                        <div class="mb-4">
                            <form method="POST" action="{{ route('templates.buy', $listing) }}" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-dark w-100 fw-bold fs-5 py-3" style="border-radius: 12px;">
                                    Buy Now - ${{ number_format($listing->price, 2) }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('cart.add', $listing) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-dark w-100 fw-bold py-3" style="border-radius: 12px;">Add to Cart</button>
                            </form>
                        </div>
                        <hr>
                        <h6 class="fw-bold mb-3">Template Details</h6>
                        <ul class="list-unstyled small text-muted">
                            <li><strong>Files:</strong> {{ basename($listing->zip_path) }}</li>
                            <li><strong>Published:</strong> {{ $listing->created_at->format('M j, Y') }}</li>
                            <li><strong>Category:</strong> {{ $listing->category }}</li>
                            <li><strong>Downloads:</strong> 0 (demo)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </x-sidebar>
</x-menu>

<style>
    .carousel-control-prev, .carousel-control-next {
        width: 5%;
        opacity: 0.7;
        filter: invert(1);
    }
    .carousel-control-prev:hover, .carousel-control-next:hover {
        opacity: 1;
    }
    .card {
        transition: all 0.3s ease;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }
    .btn-dark {
        transition: all 0.3s ease;
    }
    .btn-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
</style>
