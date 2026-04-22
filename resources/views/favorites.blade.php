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
                        <h1 class="display-6 fw-bold">Saved designs and templates.</h1>
                        <p class="text-muted">A curated view of your favorite products, UI components, and design concepts ready for your next project.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="#" class="btn btn-outline-dark">Manage favorites</a>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    <div class="col">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="https://via.placeholder.com/600x360?text=Portfolio+Grid" class="card-img-top" alt="Portfolio Grid">
                            <div class="card-body">
                                <h5 class="card-title">Portfolio Grid</h5>
                                <p class="card-text text-muted">A visually-rich layout for showcasing products, case studies, and featured work.</p>
                            </div>
                            <div class="card-footer bg-white border-0 py-3">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Template</span>
                                    <span>Saved 4 days ago</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="https://via.placeholder.com/600x360?text=Dashboard+UI" class="card-img-top" alt="Dashboard UI">
                            <div class="card-body">
                                <h5 class="card-title">Dashboard UI</h5>
                                <p class="card-text text-muted">A clean analytics workspace with widgets, charts, and quick actions.</p>
                            </div>
                            <div class="card-footer bg-white border-0 py-3">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Design system</span>
                                    <span>Saved 1 week ago</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="https://via.placeholder.com/600x360?text=Landing+Page" class="card-img-top" alt="Landing Page">
                            <div class="card-body">
                                <h5 class="card-title">Launch Landing Page</h5>
                                <p class="card-text text-muted">A conversion-focused home page designed for startups and product launches.</p>
                            </div>
                            <div class="card-footer bg-white border-0 py-3">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Landing page</span>
                                    <span>Saved 10 days ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-3">
                    <div class="col-lg-12">
                        <div class="card border-0 shadow-sm p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-2">Ready to turn favorites into a project?</h5>
                                    <p class="text-muted mb-0">Take any saved design and start a new order, or share a reference with your creative team.</p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <a href="#" class="btn btn-dark">Start a project</a>
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
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .card img {
        transition: all 0.3s ease;
        height: 200px;
        object-fit: cover;
    }
    
    .card:hover img {
        transform: scale(1.05);
    }
    
    .card-footer {
        transition: all 0.3s ease;
    }
    
    .card:hover .card-footer {
        background-color: rgba(0, 0, 0, 0.02);
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
    }
    
    .badge:hover {
        transform: scale(1.1);
    }
</style>
