<x-menu>
    <x-sidebar>
        <section class="py-5">
            <div class="container-fluid px-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-dark btn-sm">← Back</a>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-lg-8">
                        <h1 class="display-6 fw-bold">Your purchased assets and services.</h1>
                        <p class="text-muted">Keep track of your latest purchases, downloads, and active subscriptions in one polished place.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="#" class="btn btn-outline-dark">View invoices</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

            <div class="row g-4">
                @forelse($purchases as $purchase)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $purchase['title'] }}</h5>
                                        <p class="text-muted small mb-0">{{ $purchase['category'] }} by {{ $purchase['seller'] }}</p>
                                    </div>
                                    <span class="badge bg-success">{{ $purchase['status'] }}</span>
                                </div>
                                <div class="row g-3 text-muted small">
                                    <div class="col-6">Purchased</div>
                                    <div class="col-6 text-end">{{ \Carbon\Carbon::parse($purchase['purchased_at'])->format('M j, Y') }}</div>
                                    <div class="col-6">Quantity</div>
                                    <div class="col-6 text-end">{{ $purchase['quantity'] }}</div>
                                    <div class="col-6">Amount</div>
                                    <div class="col-6 text-end">${{ number_format($purchase['total'], 2) }}</div>
                                    <div class="col-6">License</div>
                                    <div class="col-6 text-end">Commercial</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm text-center p-5">
                            <h5 class="fw-bold mb-2">No purchases yet</h5>
                            <p class="text-muted mb-4">Buy a template and it will show here with a matching notification and inbox message.</p>
                            <a href="{{ url('/explore') }}" class="btn btn-dark px-4">Browse Templates</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="row g-4 mt-2">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <h5 class="card-title mb-1">Billing summary</h5>
                                    <p class="text-muted small mb-0">Recent transactions and delivery status for your latest orders.</p>
                                </div>
                                <a href="#" class="text-decoration-none">Download statement</a>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($purchases as $purchase)
                                            <tr>
                                                <td>{{ $purchase['title'] }}</td>
                                                <td><span class="badge bg-success">Delivered</span></td>
                                                <td>{{ \Carbon\Carbon::parse($purchase['purchased_at'])->format('M j, Y') }}</td>
                                                <td class="text-end">${{ number_format($purchase['total'], 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">No transactions yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }
    
    .btn {
        transition: all 0.3s ease;
        border-radius: 8px;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
    
    .badge {
        transition: all 0.3s ease;
        border-radius: 6px;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }
    
    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border: none;
        font-weight: 600;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
        transform: scale(1.01);
    }
</style>
