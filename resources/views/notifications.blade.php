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
                        <h1 class="display-6 fw-bold">Notifications</h1>
                        <p class="text-muted">See updates about your account, orders, and marketplace activity.</p>
                    </div>
                </div>

                <div class="list-group">
                    <div class="list-group-item rounded-4 shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">Order confirmed</h5>
                                <p class="mb-1 text-muted small">Your order for the E-commerce UI Kit has been confirmed.</p>
                            </div>
                            <span class="badge bg-success">New</span>
                        </div>
                        <small class="text-muted">10 minutes ago</small>
                    </div>

                    <div class="list-group-item rounded-4 shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">Billing update</h5>
                                <p class="mb-1 text-muted small">Your payment method was successfully updated.</p>
                            </div>
                            <span class="badge bg-secondary">Info</span>
                        </div>
                        <small class="text-muted">Yesterday</small>
                    </div>

                    <div class="list-group-item rounded-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">New feature</h5>
                                <p class="mb-1 text-muted small">We added new dashboard templates to the Explore page.</p>
                            </div>
                            <span class="badge bg-primary">Update</span>
                        </div>
                        <small class="text-muted">2 days ago</small>
                    </div>
                </div>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .list-group-item {
        transition: all 0.3s ease;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .badge {
        font-size: 0.75rem;
    }
</style>
