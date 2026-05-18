<x-menu>
    <x-sidebar>
        <div class="container-fluid px-4 py-4">
            <h2 class="fw-bold mb-4">Shopping Cart</h2>

            @if(session('error'))
                <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">{{ session('error') }}</div>
            @endif

            @if($listings->isEmpty())
                <div class="bg-white rounded-4 shadow-sm p-5 text-center">
                    <div class="text-muted mb-3" style="font-size: 3rem;">🛒</div>
                    <h5 class="fw-bold mb-2">Your cart is empty</h5>
                    <p class="text-muted mb-4">Browse our marketplace and add templates to your cart.</p>
                    <a href="{{ url('/explore') }}" class="btn btn-dark px-4">Explore Templates</a>
                </div>
            @else
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="bg-white rounded-4 shadow-sm p-4">
                            @foreach($listings as $listing)
                                <div class="d-flex gap-3 align-items-center mb-4 pb-4 border-bottom">
                                    <img src="{{ ! empty($listing->preview_images) ? Storage::url($listing->preview_images[0]) : 'https://via.placeholder.com/120x90?text=' . urlencode($listing->title) }}"
                                         class="rounded-3" style="width: 120px; height: 90px; object-fit: cover;" alt="{{ $listing->title }}">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $listing->title }}</h6>
                                        <p class="text-muted small mb-1">{{ $listing->category }}</p>
                                        <p class="text-muted small mb-0">by {{ $listing->user->devsell_display_name ?? $listing->user->name }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold mb-1">${{ number_format($listing->price, 2) }}</p>
                                        <p class="text-muted small mb-2">Qty: {{ $cart[$listing->id] ?? 1 }}</p>
                                        <form method="POST" action="{{ route('cart.remove', $listing) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 2rem;">
                            <h5 class="fw-bold mb-4">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Items ({{ count($cart) }})</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold fs-5">${{ number_format($total, 2) }}</span>
                            </div>
                            <a href="{{ route('cart.checkout') }}" class="btn btn-dark w-100 fw-bold py-3 text-center" style="border-radius: 12px; display: inline-block;">
                                Proceed to Checkout
                            </a>
                            <a href="{{ url('/explore') }}" class="btn btn-outline-dark w-100 mt-2 btn-sm">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-sidebar>
</x-menu>

<style>
    .card {
        transition: all 0.3s ease;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    .btn-dark {
        transition: all 0.3s ease;
    }
    .btn-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    .btn-outline-danger:hover {
        transform: translateY(-1px);
    }
</style>

