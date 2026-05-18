<x-menu>
    <x-sidebar>
        <style>
            .reaction-input {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }
            .reaction-input label {
                width: 56px;
                min-width: 56px;
                height: 56px;
                padding: 0;
                cursor: pointer;
            }
            .reaction-input i {
                font-size: 1.2rem;
            }
            .reaction-input .reaction-option {
                padding: 0;
                transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
            }
            .reaction-input .reaction-option.active {
                transform: translateY(-1px);
            }
            .reaction-summary {
                flex-wrap: wrap;
            }

            .review-comment-input {
                color: #0f172a;
                background-color: #fff;
            }

            .review-comment-input::placeholder {
                color: #6c757d;
                opacity: 1;
            }

            .review-comment-input:focus {
                color: #0f172a;
                background-color: #fff;
                caret-color: #0f172a;
            }
        </style>
        <div class="container-fluid px-4 py-4">
            <a href="{{ url('/explore') }}" class="btn btn-outline-dark btn-sm mb-4" onclick="if (window.history.length > 1) { window.history.back(); return false; }">&larr; Back</a>

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

                    @php
                        $currentUserId = session('user_id') ? (int) session('user_id') : null;
                        $isOwner = $currentUserId !== null && $listing->user_id === $currentUserId;
                    @endphp

                    <!-- Likes and Reviews -->
                    <div class="bg-white rounded-4 shadow-sm p-5 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="fw-bold mb-1">Reviews</h4>
                                <div class="reaction-summary d-flex align-items-center gap-2">
                                    <span class="badge bg-success text-white">
                                        <i class="fa-regular fa-thumbs-up me-1"></i>
                                        {{ $listing->thumbsUpCount() }}
                                    </span>
                                    <span class="badge bg-danger text-white">
                                        <i class="fa-regular fa-thumbs-down me-1"></i>
                                        {{ $listing->thumbsDownCount() }}
                                    </span>
                                    <span class="text-muted">({{ $listing->getReviewCount() }} reviews)</span>
                                    <span class="text-muted">{{ $listing->thumbsUpPercentage() }}% positive</span>
                                </div>
                            </div>
                        </div>

                        <!-- Review Form -->
                        @if($currentUserId && !$isOwner)
                            @php
                                $userReview = $listing->reviews->where('user_id', $currentUserId)->first();
                            @endphp
                            <div class="border rounded-3 p-4 mb-4" style="background-color: #f8f9fa;">
                                <h6 class="fw-bold mb-3">{{ $userReview ? 'Update Your Review' : 'Write a Review' }}</h6>
                                <form method="POST" action="{{ route('templates.review', $listing) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Your reaction</label>
                                        @php
                                            $selectedRating = $userReview?->rating ?? 5;
                                        @endphp
                                        <div class="reaction-input">
                                            <label class="reaction-option btn btn-outline-success {{ $selectedRating >= 4 ? 'active' : '' }} d-flex align-items-center justify-content-center" title="Thumbs up" aria-label="Thumbs up" aria-pressed="{{ $selectedRating >= 4 ? 'true' : 'false' }}">
                                                <input type="radio" name="rating" value="5" class="d-none" {{ $selectedRating >= 4 ? 'checked' : '' }}>
                                                <i class="fa-regular fa-thumbs-up"></i>
                                                <span class="visually-hidden">Thumbs up</span>
                                            </label>
                                            <label class="reaction-option btn btn-outline-danger {{ $selectedRating < 4 ? 'active' : '' }} d-flex align-items-center justify-content-center" title="Thumbs down" aria-label="Thumbs down" aria-pressed="{{ $selectedRating < 4 ? 'true' : 'false' }}">
                                                <input type="radio" name="rating" value="1" class="d-none" {{ $selectedRating < 4 ? 'checked' : '' }}>
                                                <i class="fa-regular fa-thumbs-down"></i>
                                                <span class="visually-hidden">Thumbs down</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label fw-semibold">Comment (Optional)</label>
                                        <textarea class="form-control review-comment-input" id="comment" name="comment" rows="3" placeholder="Share your thoughts about this template...">{{ $userReview ? $userReview->comment : '' }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ $userReview ? 'Update Review' : 'Submit Review' }}</button>
                                </form>
                            </div>
                        @endif

                        <!-- Reviews List -->
                        @if($listing->reviews->count() > 0)
                            <div class="reviews-list">
                                @foreach($listing->reviews as $review)
                                    <div class="review-item border-bottom pb-3 mb-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <img src="{{ $review->user->profile_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}"
                                                 class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $review->user->name }}">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h6 class="mb-0 fw-semibold">{{ $review->user->name }}</h6>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="text-{{ $review->isThumbsUp() ? 'success' : 'danger' }}">
                                                            <i class="fa-regular fa-thumbs-{{ $review->isThumbsUp() ? 'up' : 'down' }}"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @if($review->comment)
                                                    <p class="text-muted mb-1">{{ $review->comment }}</p>
                                                @endif
                                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-comments text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">No reviews yet. Be the first to review this template!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 sticky-top" style="top: 2rem;">
                        <a href="{{ route('sellers.show', $listing->user) }}" class="d-flex align-items-center mb-4 text-decoration-none text-dark">
                            <img src="{{ $listing->user->profile_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($listing->user->name ?? 'Seller') }}"
                                 class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="{{ $listing->user->name }}">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $listing->user->devsell_display_name ?? $listing->user->name ?? 'Seller' }}</h6>
                                <small class="text-muted">{{ $listing->user->devsell_specialty ?? 'Designer' }}</small>
                            </div>
                        </a>
                        <a href="{{ route('sellers.show', $listing->user) }}" class="btn btn-outline-dark w-100 mb-4">
                            <i class="fa-regular fa-user me-1"></i>
                            Visit Seller
                        </a>
                        <div class="mb-4">
                        @if ($isOwner)
                            <button type="button" class="btn btn-secondary w-100 fw-bold fs-5 py-3" style="border-radius: 12px;" disabled>
                                You cannot buy your own template
                            </button>
                        @else
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
                        @endif
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

<script>
    document.querySelectorAll('.reaction-input').forEach((group) => {
        const options = group.querySelectorAll('.reaction-option');

        options.forEach((option) => {
            option.addEventListener('click', () => {
                options.forEach((item) => {
                    item.classList.remove('active');
                    item.setAttribute('aria-pressed', 'false');
                });

                option.classList.add('active');
                option.setAttribute('aria-pressed', 'true');
                option.querySelector('input[type="radio"]').checked = true;
            });
        });
    });
</script>
