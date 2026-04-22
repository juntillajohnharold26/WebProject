@php
    $statusBadges = [
        'active' => 'bg-success',
        'draft' => 'bg-warning text-dark',
        'archived' => 'bg-secondary',
    ];
@endphp

<x-menu>
    <x-sidebar>
        <section class="templates-page py-4 py-xl-5">
            <div class="templates-page__inner mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <a href="{{ url('/explore') }}" class="btn btn-outline-dark btn-sm mb-3">&larr; Explore</a>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="small text-muted">Seller dashboard</span>
                        </div>
                        <h1 class="h3 fw-bold mb-0">Your Templates</h1>
                    </div>
                    <a href="{{ route('seller.templates.create') }}" class="btn btn-dark px-4">
                        + New Template
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                @if($listings->count() > 0)
                    <div class="row g-4">
                        @foreach($listings as $listing)
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 shadow-sm border-0">
                                    @if(count($listing->preview_images))
                                        <img src="{{ Storage::url($listing->preview_images[0]) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <span class="text-muted">No preview</span>
                                        </div>
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title fw-bold">{{ $listing->title }}</h6>
                                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($listing->description, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="badge {{ $statusBadges[$listing->status ?? 'draft'] }} fs-6 px-3 py-2">{{ ucfirst($listing->status ?? 'draft') }}</span>
                                            <span class="fw-bold text-success">${{ number_format($listing->price, 2) }}</span>
                                        </div>
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-grid gap-2 d-md-flex">
                                                <a href="{{ url('/seller/templates/' . $listing->id . '/edit') }}" class="btn btn-outline-primary btn-sm flex-fill">Edit</a>
                                                <form method="POST" action="{{ url('/seller/templates/' . $listing->id) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this template?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        {{ $listings->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="empty-state mb-4">
                            <svg class="empty-icon mb-3" width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L13.09 8.26L19.94 9L12 2Z" fill="currentColor"/>
                                <path d="M10.91 15.74L12 2L19.94 9L12 15.74L10.91 15.74Z" fill="currentColor"/>
                                <path opacity="0.4" d="M10.91 15.74L12 15.74L5.06 9L12 2L10.91 15.74Z" fill="currentColor"/>
                            </svg>
                            <h3 class="h5 fw-bold mb-2">No templates yet</h3>
                            <p class="text-muted mb-4">Create your first template listing to start selling your designs.</p>
                            <a href="{{ route('seller.templates.create') }}" class="btn btn-dark px-5">Create Template</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
.templates-page {
    background: #f8fafc;
}

.templates-page__inner {
    max-width: 1200px;
}

.empty-icon {
    color: #64748b;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
    border-radius: 1.25rem !important;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.btn {
    border-radius: 0.75rem;
}

.alert {
    border-radius: 1.25rem;
}
</style>
