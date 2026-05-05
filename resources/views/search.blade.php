<x-menu>
    <x-sidebar>
        <section class="py-5 search-hero bg-light mt-4">
            <div class="container-fluid px-4">
                <div class="row mb-5">
                    <div class="col-12">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-dark btn-sm">← Back</a>
                    </div>
                </div>

                <div class="hero-card rounded-4 shadow-sm p-5 mb-5">
                    <div class="row align-items-center gy-4">
                        <div class="col-lg-8">
                            <h1 class="display-6 fw-bold">Find the right design for your project.</h1>
                            <p class="text-muted">Search our marketplace and filter recommendations to match your brand,
                                industry, or product flow.</p>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-lg-8">
                        @if(request('q') || $listings->isNotEmpty())
                            <div class="alert alert-info mb-4">
                                <strong>{{ $listings->total() }} results</strong>
                                @if(request('q'))
                                    for "{{ request('q') }}"
                                @endif
                                @if(request('tag'))
                                    tagged "{{ request('tag') }}"
                                @endif
                                @if(request('category'))
                                    in {{ request('category') }}
                                @endif
                            </div>
                        @endif
                        @include('market.templates.index', ['listings' => $listings])
                    </div>

                        <div class="col-lg-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 2rem;">
                                <h5 class="fw-bold mb-4">Filters</h5>
                                
                                <form method="GET" action="{{ url('/search') }}" class="mb-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">Search</label>
                                        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Title, keyword...">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">Tag</label>
                                        <select name="tag" class="form-select form-select-sm">
                                            <option value="">All tags</option>
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag }}" {{ request('tag') == $tag ? 'selected' : '' }}>{{ ucfirst($tag) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">Category</label>
                                        <select name="category" class="form-select form-select-sm">
                                            <option value="">All categories</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold small mb-1">Min Price</label>
                                            <input type="number" name="min_price" step="0.01" value="{{ request('min_price', $minPrice ?? 0) }}" class="form-control form-control-sm" min="0">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold small mb-1">Max Price</label>
                                            <input type="number" name="max_price" step="0.01" value="{{ request('max_price', '') }}" class="form-control form-control-sm" min="0">
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-dark w-100 mt-3 fw-semibold py-2">Apply Filters</button>
                                    @if(request()->hasAny(['q', 'tag', 'category', 'min_price', 'max_price']))
                                        <a href="{{ url('/search') }}" class="btn btn-outline-secondary w-100 mt-2 btn-sm">Clear All</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .search-hero {
        background: radial-gradient(circle at top left, rgba(0, 0, 0, 0.08), transparent 32%),
            radial-gradient(circle at bottom right, rgba(0, 0, 0, 0.06), transparent 20%),
            #f8f9fb;
    }

    .hero-card {
        background: linear-gradient(180deg, #ffffff 0%, #f4f6f8 100%);
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 18px;
        background-color: #fff;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.13);
    }

    .card-body {
        padding: 1.4rem;
    }

    .card-footer {
        background-color: #fff;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        padding: 1rem 1.4rem;
    }

    .btn {
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 22px rgba(0, 0, 0, 0.14);
    }
</style>
