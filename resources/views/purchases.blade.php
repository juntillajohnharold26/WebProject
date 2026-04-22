<x-menu>
    <x-sidebar>
        <section class="py-5">
            <div class="container-fluid px-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="{{ url('/explore') }}" class="btn btn-outline-dark btn-sm">← Back</a>
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

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Premium UI Kit</h5>
                                    <p class="text-muted small mb-0">E-commerce template set for modern storefronts.</p>
                                </div>
                                <span class="badge bg-success">Completed</span>
                            </div>
                            <div class="row g-3 text-muted small">
                                <div class="col-6">Purchased</div>
                                <div class="col-6 text-end">Apr 12, 2026</div>
                                <div class="col-6">Amount</div>
                                <div class="col-6 text-end">$49</div>
                                <div class="col-6">License</div>
                                <div class="col-6 text-end">Commercial</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Design System Support</h5>
                                    <p class="text-muted small mb-0">Monthly maintenance package for your UI library.</p>
                                </div>
                                <span class="badge bg-warning text-dark">Active</span>
                            </div>
                            <div class="row g-3 text-muted small">
                                <div class="col-6">Started</div>
                                <div class="col-6 text-end">Mar 28, 2026</div>
                                <div class="col-6">Next renewal</div>
                                <div class="col-6 text-end">May 28, 2026</div>
                                <div class="col-6">Amount</div>
                                <div class="col-6 text-end">$120 / month</div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                        <tr>
                                            <td>Landing page bundle</td>
                                            <td><span class="badge bg-success">Delivered</span></td>
                                            <td>Apr 3, 2026</td>
                                            <td class="text-end">$79</td>
                                        </tr>
                                        <tr>
                                            <td>Mobile app mockups</td>
                                            <td><span class="badge bg-secondary">Pending</span></td>
                                            <td>Apr 18, 2026</td>
                                            <td class="text-end">$59</td>
                                        </tr>
                                        <tr>
                                            <td>Icon pack license</td>
                                            <td><span class="badge bg-success">Delivered</span></td>
                                            <td>Mar 30, 2026</td>
                                            <td class="text-end">$15</td>
                                        </tr>
                                    </tbody>
                                </table>
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
