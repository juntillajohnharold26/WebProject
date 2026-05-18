<x-menu>
    <x-sidebar>
        <section class="py-5">
            <div class="container-fluid px-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="{{ url('/cart') }}" class="btn btn-outline-dark btn-sm">← Back to Cart</a>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="bg-white rounded-4 shadow-sm p-5">
                            <h1 class="display-6 fw-bold mb-3">Checkout</h1>
                            <p class="text-muted mb-4">Review your order and complete payment to confirm your purchase.</p>

                            @if ($errors->any())
                                <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">Order summary</h5>
                                @foreach ($listings as $listing)
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $listing->title }}</h6>
                                            <p class="text-muted small mb-1">{{ $listing->category }} · {{ $listing->user->devsell_display_name ?? $listing->user->name }}</p>
                                            <p class="text-muted small mb-0">Qty: {{ $cart[$listing->id] ?? 1 }}</p>
                                        </div>
                                        <div class="text-end">
                                            <p class="fw-bold mb-1">${{ number_format($listing->price * ($cart[$listing->id] ?? 1), 2) }}</p>
                                            <p class="text-muted small mb-0">Unit: ${{ number_format($listing->price, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row g-3 align-items-center">
                                <div class="col-sm-6">
                                    <span class="text-muted">Transaction ID</span>
                                    <p class="fw-semibold mb-0">{{ $transactionId }}</p>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <span class="text-muted">Order total</span>
                                    <p class="fw-semibold mb-0 fs-4">${{ number_format($total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 2rem;">
                            <h5 class="fw-bold mb-4">Payment details</h5>

                            <form method="POST" action="{{ route('cart.pay') }}">
                                @csrf
                                <input type="hidden" name="transaction_id" value="{{ $transactionId }}">

                                <div class="mb-3">
                                    <label class="form-label fw-medium small mb-2" for="payment_method">Payment method</label>
                                    <select id="payment_method" name="payment_method" class="form-select form-select-sm">
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method }}">{{ $method }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium small mb-2">Amount</label>
                                    <div class="p-3 rounded-4 bg-light text-dark fw-semibold">${{ number_format($total, 2) }}</div>
                                </div>

                                <button type="submit" class="btn btn-dark w-100 fw-semibold py-3">Pay ${{ number_format($total, 2) }}</button>
                            </form>

                            <div class="mt-4 small text-muted">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .card {
        transition: all 0.3s ease;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .form-select,
    .form-control {
        border-radius: 12px;
        border: 1px solid rgba(15, 23, 42, 0.12);
    }
</style>
