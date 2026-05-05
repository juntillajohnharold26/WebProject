<x-menu>
    <x-sidebar>
        <div class="container-fluid px-4 py-4">
            <div class="bg-white rounded-4 shadow-sm p-5 mb-5">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-5">
                        <h1 class="display-6 fw-bold lh-sm">Browse Premium Designs & Templates.</h1>
                        <p class="text-muted mt-3">Explore curated collections of business templates, user interface
                            systems, and ready-to-launch digital assets.</p>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="#styles" class="btn btn-dark btn-sm px-4">Browse Styles</a>
                            <a href="#featured" class="btn btn-outline-dark btn-sm px-4">Featured Designs</a>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div id="devbuyCarousel" class="carousel slide rounded-4 overflow-hidden shadow"
                            data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">

                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#devbuyCarousel" data-bs-slide-to="0"
                                    class="active" aria-current="true"></button>
                                <button type="button" data-bs-target="#devbuyCarousel" data-bs-slide-to="1"></button>
                                <button type="button" data-bs-target="#devbuyCarousel" data-bs-slide-to="2"></button>
                            </div>

                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=900&q=80"
                                        class="d-block w-100 carousel-img" alt="Web Design Templates">
                                    <div
                                        class="carousel-caption d-flex flex-column align-items-start text-start pb-4 ps-4">
                                        <h5 class="fw-bold text-white mb-1">Web Design Templates</h5>
                                        <p class="text-white-50 small mb-0">Modern, responsive layouts for every need.
                                        </p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=900&q=80"
                                        class="d-block w-100 carousel-img" alt="UI/UX Kits">
                                    <div
                                        class="carousel-caption d-flex flex-column align-items-start text-start pb-4 ps-4">
                                        <h5 class="fw-bold text-white mb-1">UI/UX Design Kits</h5>
                                        <p class="text-white-50 small mb-0">Complete design systems and component
                                            libraries.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=900&q=80"
                                        class="d-block w-100 carousel-img" alt="E-commerce Assets">
                                    <div
                                        class="carousel-caption d-flex flex-column align-items-start text-start pb-4 ps-4">
                                        <h5 class="fw-bold text-white mb-1">E-commerce Assets</h5>
                                        <p class="text-white-50 small mb-0">Shop-ready templates and marketplace
                                            branding.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="styles" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-semibold mb-0">Browse by Style</h4>
                <p class="text-muted mb-0">Choose the look that fits your brand.</p>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4">
                <div class="col">
                    <a href="{{ url('/search?tag=futuristic') }}" class="text-decoration-none d-block h-100">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=640&q=80"
                            class="card-img-top style-img" alt="Futuristic style">
                        <div class="card-body text-center" style="background-color: #007bff; color: white;">
                            <h5 class="card-title mb-0">Futuristic</h5>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ url('/search?tag=minimal') }}" class="text-decoration-none d-block h-100">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?w=640&q=80"
                            class="card-img-top style-img" alt="Minimal style">
                        <div class="card-body text-center" style="background-color: #f8f9fa; color: #000000;">
                            <h5 class="card-title mb-0">Minimal</h5>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ url('/search?tag=dark') }}" class="text-decoration-none d-block h-100">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=640&q=80"
                            class="card-img-top style-img" alt="Dark style">
                        <div class="card-body text-center" style="background-color: #000000; color: white;">
                            <h5 class="card-title mb-0">Dark</h5>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ url('/search?tag=creative') }}" class="text-decoration-none d-block h-100">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?w=640&q=80"
                            class="card-img-top style-img" alt="Creative style">
                        <div class="card-body text-center" style="background-color: #6f42c1; color: white;">
                            <h5 class="card-title mb-0">Creative</h5>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </section>

        <section id="featured" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-semibold mb-0">Featured Designs</h4>
                <p class="text-muted mb-0">Top selections ready to use.</p>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                <div class="col">
                    <div class="card h-100 shadow-sm overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1522542550221-31fd19575a2d?w=800&q=80"
                            class="card-img-top featured-img" alt="Landing Page">
                        <div class="card-body">
                            <h5 class="fw-bold">Landing Page</h5>
                            <p class="text-muted small">A polished home page layout built for clear conversion paths.
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Ready to launch</span>
                            <a href="{{ url('/search') }}" class="btn btn-sm"
                                style="background-color: #000000; border-color: #000000; color: white;">Browse</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1547658719-da2b51169166?w=800&q=80"
                            class="card-img-top featured-img" alt="Animated Design">
                        <div class="card-body">
                            <h5 class="fw-bold">Animated Design</h5>
                            <p class="text-muted small">Motion-ready visuals and engaging interface elements.</p>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Flexible use</span>
                            <a href="{{ url('/search') }}" class="btn btn-sm"
                                style="background-color: #000000; border-color: #000000; color: white;">Browse</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&q=80"
                            class="card-img-top featured-img" alt="E-commerce UI Kit">
                        <div class="card-body">
                            <h5 class="fw-bold">E-commerce UI Kit</h5>
                            <p class="text-muted small">A complete storefront experience with product, cart, and
                                checkout visuals.</p>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Shop-ready</span>
                            <a href="{{ url('/search') }}" class="btn btn-sm"
                                style="background-color: #000000; border-color: #000000; color: white;">Browse</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="text-center mb-5">
            <a href="{{ url('/search') }}" class="btn btn-lg"
                style="background-color: #000000; border-color: #000000; color: white;">Explore more designs</a>
        </div>

    </x-sidebar>
</x-menu>

<style>
    .card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 18px;
        background-color: #fff;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        padding: 1.4rem;
    }

    .card img {
        transition: all 0.3s ease;
    }

    .card:hover img {
        transform: scale(1.05);
    }

    .card-footer {
        background-color: #fff;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        padding: 1rem 1.4rem;
    }

    .card-footer .btn {
        padding: 0.7rem 1.2rem;
        border-radius: 10px;
        font-weight: 600;
    }

    .btn {
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 22px rgba(0, 0, 0, 0.14);
    }

    .carousel {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    .carousel-img {
        height: 420px;
        object-fit: cover;
        filter: brightness(0.75);
    }

    .carousel-caption {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.5), transparent);
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 0 0 1rem 1rem;
    }

    .style-img {
        height: 200px;
        object-fit: cover;
    }

    .featured-img {
        height: 220px;
        object-fit: cover;
    }
</style>
