<x-menu>
    <x-sidebar>
        <section class="seller-page py-4 py-xl-5">
            <div class="seller-page__inner mx-auto">
                <div class="seller-page__hero mb-4">
                    <div class="seller-page__hero-copy">
                        <a href="{{ url('/contact') }}" class="btn btn-outline-dark btn-sm mb-3">&larr; Back</a>
                        <h1 class="seller-page__title">Unlock DevSell access with your account credentials.</h1>
                        <p class="seller-page__lead mb-0">Use your DevBuy email and password, then finish your seller details to start sharing templates, UI kits, and premium digital assets.</p>
                    </div>

                    <div class="seller-page__status card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <p class="text-uppercase small text-muted mb-2">Access status</p>
                            <h2 class="h5 fw-bold mb-2">{{ $sellerAccess['active'] ? 'Seller access active' : 'Pending verification' }}</h2>
                            <p class="text-muted mb-0">
                                @if ($sellerAccess['active'])
                                    Activated on {{ $sellerAccess['joined_at'] }}.
                                @else
                                    Confirm your credentials below to enable your DevSell seller tools.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success seller-alert border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                <div class="row g-4 align-items-stretch">
                    <div class="col-xl-5">
                        <div class="seller-card seller-card--dark h-100">
                            <h2 class="seller-card__title">Build your creator profile and start listing polished design work.</h2>
                            <p class="seller-card__copy">This flow matches the button on the contact page, but adds a real gate: seller access only turns on after the account credentials are entered correctly.</p>

                            <div class="seller-benefits">
                                <div class="seller-benefit">
                                    <span>01</span>
                                    <div>
                                        <h3>Credential check</h3>
                                        <p>Use your current DevBuy email and password before seller tools are enabled.</p>
                                    </div>
                                </div>
                                <div class="seller-benefit">
                                    <span>02</span>
                                    <div>
                                        <h3>Seller profile</h3>
                                        <p>Add your store name, specialty, and portfolio link so buyers know what you offer.</p>
                                    </div>
                                </div>
                                <div class="seller-benefit">
                                    <span>03</span>
                                    <div>
                                        <h3>Ready to manage</h3>
                                        <p>Once saved, you can revisit this page anytime from the sidebar to update details.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="seller-preview">
                                <p class="seller-preview__label">What gets unlocked</p>
                                <div class="seller-preview__stats">
                                    <div>
                                        <strong>Templates</strong>
                                        <span>List product-ready files</span>
                                    </div>
                                    <div>
                                        <strong>UI Kits</strong>
                                        <span>Show your design systems</span>
                                    </div>
                                    <div>
                                        <strong>Assets</strong>
                                        <span>Share icons, mockups, and more</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="seller-card h-100">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
                                <div>
                                    <p class="text-uppercase small text-muted mb-1">Credential form</p>
                                    <h2 class="h4 fw-semibold mb-0">Join DevSell</h2>
                                </div>
                                <span class="small text-muted">All fields help set up your seller profile.</span>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger seller-alert border-0 mb-4">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('devsell.join.submit') }}" method="POST">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-12 col-xxl-6">
                                        <label class="form-label fw-medium" for="display_name">Display Name</label>
                                        <input id="display_name" type="text" name="display_name" value="{{ old('display_name', $application['display_name']) }}" class="form-control seller-input" required>
                                    </div>

                                    <div class="col-12 col-xxl-6">
                                        <label class="form-label fw-medium" for="store_name">Store Name</label>
                                        <input id="store_name" type="text" name="store_name" value="{{ old('store_name', $application['store_name']) }}" class="form-control seller-input" required>
                                    </div>

                                    <div class="col-12 col-xxl-6">
                                        <label class="form-label fw-medium" for="email">Email</label>
                                        <input id="email" type="email" name="email" value="{{ old('email', $application['email']) }}" class="form-control seller-input" required>
                                    </div>

                                    <div class="col-12 col-xxl-6">
                                        <label class="form-label fw-medium" for="password">Password</label>
                                        <input id="password" type="password" name="password" class="form-control seller-input" required>
                                    </div>

                                    <div class="col-12 col-xxl-6">
                                        <label class="form-label fw-medium" for="specialty">Specialty</label>
                                        <input id="specialty" type="text" name="specialty" value="{{ old('specialty', $application['specialty']) }}" class="form-control seller-input" placeholder="UI Kits, Dashboards, Branding..." required>
                                    </div>

                                    <div class="col-12 col-xxl-6">
                                        <label class="form-label fw-medium" for="portfolio">Portfolio URL</label>
                                        <input id="portfolio" type="url" name="portfolio" value="{{ old('portfolio', $application['portfolio']) }}" class="form-control seller-input" placeholder="https://yourportfolio.com">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-medium" for="bio">Seller Bio</label>
                                        <textarea id="bio" name="bio" class="form-control seller-input" rows="5" required>{{ old('bio', $application['bio']) }}</textarea>
                                    </div>
                                </div>

                                <div class="seller-card__footer">
                                    <p class="text-muted small mb-0">Your email and password are checked against the current DevBuy account before seller access is granted.</p>
                                    <button type="submit" class="btn btn-dark seller-submit">
                                        {{ $sellerAccess['active'] ? 'Update Seller Access' : 'Unlock DevSell Access' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .seller-page {
        width: 100%;
    }

    .seller-page__inner {
        width: 100%;
        max-width: 1160px;
    }

    .seller-page__hero {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 1.5rem;
        align-items: stretch;
    }

    .seller-page__hero > * {
        min-width: 0;
    }

    .seller-page__hero-copy {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: 1.75rem;
        padding: 2rem;
        border: 1px solid rgba(15, 23, 42, 0.06);
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.06);
        width: 100%;
    }

    .seller-page__eyebrow {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.08);
        color: #0f172a;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 1rem;
    }

    .seller-page__title {
        font-size: 2.5rem;
        line-height: 1.05;
        margin-bottom: 1rem;
        color: #0f172a;
    }

    @supports (font-size: clamp(2rem, 3vw, 3rem)) {
        .seller-page__title {
            font-size: clamp(2rem, 3vw, 3rem);
        }
    }

    .seller-page__lead {
        color: #475569;
        max-width: 48rem;
        font-size: 1.05rem;
        line-height: 1.8;
    }

    .seller-page__status {
        border-radius: 1.75rem;
        background: linear-gradient(145deg, #0f172a, #1e293b);
        color: #fff;
        width: 100%;
    }

    .seller-page__status .text-muted {
        color: rgba(255, 255, 255, 0.68) !important;
    }

    .seller-card {
        height: 100%;
        border-radius: 1.75rem;
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        padding: 2rem;
        width: 100%;
        overflow: visible;
    }

    .seller-card--dark {
        background: linear-gradient(160deg, #0f172a, #1f2937 60%, #334155);
        color: #f8fafc;
    }

    .seller-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 1rem;
    }

    .seller-card__title {
        font-size: 1.8rem;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .seller-card__copy {
        color: rgba(248, 250, 252, 0.74);
        line-height: 1.75;
        margin-bottom: 1.5rem;
    }

    .seller-benefits {
        display: grid;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .seller-benefit {
        display: grid;
        grid-template-columns: 52px minmax(0, 1fr);
        gap: 1rem;
        align-items: start;
        padding: 1rem;
        border-radius: 1.1rem;
        background: rgba(255, 255, 255, 0.08);
    }

    .seller-benefit span {
        display: grid;
        place-items: center;
        width: 52px;
        height: 52px;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.12);
        font-weight: 700;
    }

    .seller-benefit h3 {
        font-size: 1rem;
        margin-bottom: 0.35rem;
    }

    .seller-benefit p {
        margin-bottom: 0;
        color: rgba(248, 250, 252, 0.74);
        line-height: 1.65;
    }

    .seller-preview {
        padding: 1.2rem;
        border-radius: 1.25rem;
        background: rgba(255, 255, 255, 0.08);
    }

    .seller-preview__label {
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.8rem;
        color: rgba(248, 250, 252, 0.66);
    }

    .seller-preview__stats {
        display: grid;
        gap: 0.9rem;
    }

    .seller-preview__stats div {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        padding: 0.95rem 1rem;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.08);
    }

    .seller-preview__stats strong {
        font-size: 1rem;
    }

    .seller-preview__stats span {
        color: rgba(248, 250, 252, 0.68);
        font-size: 0.95rem;
    }

    .seller-alert {
        border-radius: 1.25rem;
        padding: 1rem 1.15rem;
    }

    .seller-input {
        border-radius: 1rem;
        border: 1px solid rgba(15, 23, 42, 0.12);
        padding: 1rem 1.05rem;
        background: #f8fafc;
        width: 100%;
        box-sizing: border-box;
        min-height: 56px;
        color: #0f172a !important;
        caret-color: #0f172a;
        font-size: 1rem;
        line-height: 1.5;
    }

    .seller-input:focus {
        border-color: rgba(15, 23, 42, 0.45);
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.08);
        background: #ffffff;
        color: #0f172a !important;
        caret-color: #0f172a;
    }

    .seller-input::placeholder {
        color: #64748b;
        opacity: 1;
    }

    textarea.seller-input {
        min-height: 160px;
        resize: vertical;
    }

    .seller-input:-webkit-autofill,
    .seller-input:-webkit-autofill:hover,
    .seller-input:-webkit-autofill:focus {
        -webkit-text-fill-color: #0f172a;
        transition: background-color 5000s ease-in-out 0s;
    }

    .seller-card__footer {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .form-label {
        color: #0f172a;
        margin-bottom: 0.55rem;
    }

    .seller-submit {
        width: 100%;
        border-radius: 1rem;
        padding: 0.95rem 1.25rem;
        font-weight: 600;
        font-size: 1rem;
    }

    .seller-card form .row {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
    }

    @media (max-width: 1199.98px) {
        .seller-page__hero {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767.98px) {
        .seller-page__hero {
            gap: 1rem;
        }

        .seller-page__hero-copy,
        .seller-card {
            padding: 1.5rem;
        }

        .seller-page__status .card-body {
            padding: 1.5rem !important;
        }

        .seller-page__title {
            font-size: 2rem;
            line-height: 1.15;
        }

        .seller-page__lead {
            font-size: 1rem;
            line-height: 1.7;
        }

        .seller-benefit {
            grid-template-columns: 1fr;
        }

        .seller-benefit span {
            width: 44px;
            height: 44px;
        }
    }
</style>
