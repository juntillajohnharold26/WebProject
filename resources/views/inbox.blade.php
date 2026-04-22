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
                        <h1 class="display-6 fw-bold">Inbox</h1>
                        <p class="text-muted">Your latest messages from sellers, collaborators, and support.</p>
                    </div>
                </div>

                <div class="list-group">
                    <a href="{{ url('/messages/1') }}" class="list-group-item list-group-item-action rounded-4 shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">DevSell Team</h5>
                                <p class="mb-1 text-muted small">Your store listing has been approved and is now live.</p>
                            </div>
                            <small class="text-muted">1h ago</small>
                        </div>
                    </a>

                    <a href="{{ url('/messages/2') }}" class="list-group-item list-group-item-action rounded-4 shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">Support</h5>
                                <p class="mb-1 text-muted small">Your request has been received and is being reviewed.</p>
                            </div>
                            <small class="text-muted">Yesterday</small>
                        </div>
                    </a>

                    <a href="{{ url('/messages/3') }}" class="list-group-item list-group-item-action rounded-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">DevBuy Team</h5>
                                <p class="mb-1 text-muted small">Weekly digest: new templates and trending UI kits.</p>
                            </div>
                            <small class="text-muted">2 days ago</small>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .list-group-item {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.06);
        background-color: #fff;
    }

    .list-group-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .list-group-item h5 {
        margin-bottom: 0.25rem;
    }
</style>
