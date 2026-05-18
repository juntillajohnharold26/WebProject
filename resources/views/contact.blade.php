<x-menu>
    <x-sidebar>
        <section class="contact-page py-5">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="{{ url('/explore') }}" class="btn btn-outline-dark btn-sm" onclick="if (window.history.length > 1) { window.history.back(); return false; }">&larr; Back</a>
                    </div>
                </div>

                <div class="row justify-content-center text-center mb-4">
                    <div class="col-lg-9">
                        <h1 class="display-5 fw-light mb-3">We're here Monday through Friday for questions, project requests, or help selling your design work.</h1>
                        <p class="lead text-muted mb-0">Choose the path you need. If you want to join DevSell, we'll still ask for your account credentials before seller access is enabled.</p>
                    </div>
                </div>

                <div class="row g-4 justify-content-center">
                    <div class="col-xl-6 col-lg-7">
                        <div class="card contact-card border-0 shadow-sm p-4 p-lg-5 h-100">
                            @if ($sellerActive)
                                <span class="badge bg-dark-subtle text-dark align-self-start mb-3">Seller access active</span>
                            @endif

                            <h2 class="contact-card__title">Want to DevSell?</h2>
                            <p class="contact-card__copy">Join DevSell and create designs to sell. Share templates, UI kits, and polished assets with customers around the world.</p>
                            <a href="{{ route('devsell.join') }}" class="btn btn-dark contact-card__cta">
                                {{ $sellerActive ? 'Manage DevSell Access' : 'Join DevSell' }}
                            </a>
                            <p class="text-muted small mb-0 mt-3">You'll enter your DevBuy email and password on the next page before seller access is granted.</p>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="card contact-card border-0 shadow-sm p-4 p-lg-5 h-100">
                            <h2 class="h5 fw-semibold">Need support?</h2>
                            <p class="text-muted mb-4">Email us at <a href="mailto:hello@devbuy.com">shop@devbuy.com</a> or use the form below to send a message.</p>
                            <ul class="list-unstyled mb-0 text-muted">
                                <li><strong>Business hours:</strong> Mon-Fri, 9am-6pm</li>
                            </ul>
                            <div class="social-media mt-4">
                                <p class="small text-uppercase text-muted fw-semibold mb-3">Follow us</p>

                                <div class="social-media__grid">
                                    <a href="https://twitter.com/devbuy" class="social-media__link" target="_blank" rel="noopener noreferrer" aria-label="Visit DevBuy on X">
                                        <img src="https://cdn.simpleicons.org/x/111827" alt="X logo" class="social-media__icon">
                                    </a>
                                    <a href="https://www.instagram.com/devbuy" class="social-media__link" target="_blank" rel="noopener noreferrer" aria-label="Visit DevBuy on Instagram">
                                        <img src="https://cdn.simpleicons.org/instagram/E4405F" alt="Instagram logo" class="social-media__icon">
                                    </a>
                                    <a href="https://www.facebook.com/devbuy" class="social-media__link" target="_blank" rel="noopener noreferrer" aria-label="Visit DevBuy on Facebook">
                                        <img src="https://cdn.simpleicons.org/facebook/1877F2" alt="Facebook logo" class="social-media__icon">
                                    </a>
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
    .contact-page {
        width: 100%;
    }

    .contact-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-radius: 1.75rem;
        background: #ffffff;
    }

    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    .contact-card__title {
        font-size: clamp(1.9rem, 2.6vw, 2.4rem);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .contact-card__copy {
        color: #475569;
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 1.75rem;
    }

    .contact-card__cta {
        width: 100%;
        border-radius: 1rem;
        padding: 0.95rem 1.2rem;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .btn {
        transition: all 0.3s ease;
        border-radius: 0.8rem;
        font-weight: 600;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .badge {
        transition: all 0.3s ease;
        border-radius: 999px;
    }

    .badge:hover {
        transform: scale(1.05);
    }

    .display-5 {
        color: #334155;
        line-height: 1.3;
    }

    .social-media__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
    }

    .social-media__link {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.9rem 1rem;
        border-radius: 1rem;
        text-decoration: none;
        color: #0f172a;
        background: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
    }

    .social-media__link:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        background: #ffffff;
        color: #0f172a;
    }

    .social-media__icon {
        width: 32px;
        height: 32px;
        object-fit: contain;
        flex-shrink: 0;
    }

    .social-media__link span {
        font-weight: 600;
    }

    @media (max-width: 575.98px) {
        .social-media__grid {
            grid-template-columns: 1fr;
        }
    }
</style>
