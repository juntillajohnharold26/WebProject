<x-menu>
    <x-sidebar>
        <section class="seller-store py-4 py-xl-5">
            <div class="container-fluid px-4">
                <a href="{{ url('/explore') }}" class="btn btn-outline-dark btn-sm mb-4" onclick="if (window.history.length > 1) { window.history.back(); return false; }">&larr; Back</a>

                <div class="seller-store__header bg-white shadow-sm mb-4">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                            <img src="{{ $seller->profile_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($seller->name) }}"
                                 class="seller-store__avatar" alt="{{ $seller->devsell_display_name ?? $seller->name }}">
                            <div>
                                <p class="text-uppercase small text-muted fw-semibold mb-1">Seller Store</p>
                                <h1 class="h2 fw-bold mb-1">{{ $seller->devsell_store_name ?: ($seller->devsell_display_name ?? $seller->name) }}</h1>
                                <p class="text-muted mb-2">{{ $seller->devsell_specialty ?: 'Digital template seller' }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge text-bg-dark">{{ $listings->total() }} active templates</span>
                                    @if($seller->devsell_joined_at)
                                        <span class="badge text-bg-light border">Joined {{ $seller->devsell_joined_at->format('M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($seller->devsell_portfolio)
                            <a href="{{ $seller->devsell_portfolio }}" class="btn btn-outline-dark" target="_blank" rel="noopener noreferrer">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                                Portfolio
                            </a>
                        @endif
                    </div>

                    @if($seller->devsell_bio)
                        <p class="seller-store__bio text-muted mb-0 mt-4">{{ $seller->devsell_bio }}</p>
                    @endif
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                    <div>
                        <h2 class="h4 fw-bold mb-1">Templates by {{ $seller->devsell_display_name ?? $seller->name }}</h2>
                        <p class="text-muted mb-0">Browse the other templates this seller is offering.</p>
                    </div>
                </div>

                @include('market.templates.index', ['listings' => $listings])
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .seller-store__header {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.25rem;
        padding: 2rem;
    }

    .seller-store__avatar {
        width: 96px;
        height: 96px;
        border-radius: 999px;
        object-fit: cover;
        border: 1px solid rgba(15, 23, 42, 0.12);
    }

    .seller-store__bio {
        max-width: 760px;
        line-height: 1.7;
    }
</style>
