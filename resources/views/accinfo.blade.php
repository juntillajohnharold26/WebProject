@php
    $statusConfig = [
        'active' => [
            'label' => 'Active',
            'badge' => 'account-badge account-badge--active',
        ],
        'inactive' => [
            'label' => 'Inactive',
            'badge' => 'account-badge account-badge--inactive',
        ],
        'busy' => [
            'label' => 'Busy',
            'badge' => 'account-badge account-badge--busy',
        ],
    ];

    $currentStatus = old('status', $profile['status']);
    $statusBadge = $statusConfig[$currentStatus] ?? $statusConfig['active'];
    $isSellerActive = $sellerAccess['active'] ?? false;
@endphp

<x-menu>
    <x-sidebar>
        <section class="account-page py-4 py-xl-5">
            <div class="account-page__inner mx-auto">
                <div class="account-page__hero bg-white rounded-4 shadow-sm p-4 p-lg-5 mb-4">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                        <div>
                            <a href="{{ url('/explore') }}" class="btn btn-outline-dark btn-sm mb-3" onclick="if (window.history.length > 1) { window.history.back(); return false; }">&larr; Back</a>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="small text-muted">Manage your public profile</span>
                            </div>
                            <h1 class="h3 fw-bold mb-2">Account Info</h1>
                            <p class="text-muted mb-0">Review your account details, update your profile, and keep your information current.</p>
                        </div>

                        <div class="account-page__summary card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <p class="text-uppercase small text-muted mb-2">Profile status</p>
                                <h2 class="h5 fw-bold mb-3">{{ old('name', $profile['name']) }}</h2>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="{{ $statusBadge['badge'] }}">{{ $statusBadge['label'] }}</span>
                                    <span class="account-badge {{ $isSellerActive ? 'account-badge--seller' : 'account-badge--muted' }}">
                                        {{ $isSellerActive ? 'In DevSell' : 'Not in DevSell' }}
                                    </span>
                                </div>

                                @if ($isSellerActive)
                                    <div class="seller-summary rounded-4 bg-white bg-opacity-10 p-3 mb-3">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 align-items-start">
                                            <div>
                                                <p class="small text-uppercase text-muted mb-2">Seller account</p>
                                                <h2 class="h5 fw-semibold mb-1">{{ $sellerAccess['store_name'] ?: $sellerAccess['display_name'] }}</h2>
                                                <p class="small text-muted mb-0">{{ $sellerAccess['specialty'] ?: 'Manage your shop and listings' }}</p>
                                                <p class="small text-muted mb-0">Joined {{ $sellerAccess['joined_at'] }}</p>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 mt-2 mt-sm-0">
                                                <a href="{{ route('seller.templates.index') }}" class="btn btn-light btn-sm">Seller dashboard</a>
                                                <a href="{{ route('devsell.join', ['edit' => 1]) }}" class="btn btn-outline-light btn-sm">Edit seller profile</a>

                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="seller-summary rounded-4 bg-white bg-opacity-10 p-3 mb-3">
                                        <p class="small text-uppercase text-muted mb-2">Seller access</p>
                                        <p class="mb-2">Join DevSell to manage your seller profile, list templates, and track your listings.</p>
                                        <a href="{{ route('devsell.join') }}" class="btn btn-light btn-sm">Activate DevSell</a>
                                    </div>
                                @endif

                                <p class="small mb-0 account-page__summary-copy">
                                    {{ $isSellerActive ? 'Your seller access is already enabled.' : 'Join DevSell to start selling your digital work.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                <div class="row g-4 align-items-stretch">
                    <div class="col-xl-4 col-lg-5">
                        <div class="card account-card border-0 shadow-sm h-100">
                            <div class="card-body text-center px-4 px-xl-5 py-5">
                                <div class="account-avatar mx-auto mb-4">
                                    <img src="{{ old('avatar', $profile['avatar']) }}" class="rounded-circle img-fluid border border-2 border-secondary-subtle" alt="Profile photo">
                                </div>

                                <h2 class="h4 fw-bold mb-1">{{ old('name', $profile['name']) }}</h2>
                                <p class="text-muted mb-4">{{ old('bio', $profile['bio']) }}</p>

                                <div class="account-details text-start">
                                    <div class="account-detail">
                                        <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                            <span class="small text-uppercase text-muted">Email</span>
                                            <span class="badge text-bg-dark">Verified</span>
                                        </div>
                                        <p class="mb-0">{{ old('email', $profile['email']) }}</p>
                                    </div>

                                    <div class="account-detail">
                                        <span class="small text-uppercase text-muted d-block mb-2">Display Name</span>
                                        <p class="mb-0">{{ old('name', $profile['name']) }}</p>
                                    </div>

                                    <div class="account-detail">
                                        <span class="small text-uppercase text-muted d-block mb-2">Bio</span>
                                        <p class="mb-0">{{ old('bio', $profile['bio']) ?: 'No bio yet' }}</p>
                                    </div>

                                    <div class="account-detail">
                                        <span class="small text-uppercase text-muted d-block mb-2">Role</span>
                                        <p class="mb-0">{{ old('role', $profile['role']) }}</p>
                                    </div>

                                    <div class="account-detail">
                                        <span class="small text-uppercase text-muted d-block mb-2">Location</span>
                                        <p class="mb-0">{{ old('location', $profile['location']) }}</p>
                                    </div>

                                    <div class="account-detail">
                                        <span class="small text-uppercase text-muted d-block mb-2">DevSell</span>
                                        <p class="mb-0">{{ $isSellerActive ? 'Already joined DevSell' : 'Not in DevSell yet' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-7">
                        <div class="card account-card border-0 shadow-sm h-100">
                            <div class="card-body px-4 px-xl-5 py-5">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
                                    <div>
                                        <p class="text-uppercase small text-muted mb-1">Edit profile</p>
                                        <h2 class="h4 fw-semibold mb-0">Update your details</h2>
                                    </div>
                                    <span class="small text-muted">Changes are saved to your account profile.</span>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger rounded-4 border-0 mb-4">
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ url('/account') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="account-status-picker mb-4">
                                        <label class="form-label fw-medium d-block mb-3">Profile Visibility Status</label>
                                        <div class="account-status-picker__grid">
                                            @foreach ($statusOptions as $value => $label)
                                                <div>
                                                    <input
                                                        class="account-status-picker__input"
                                                        type="radio"
                                                        name="status"
                                                        id="status_{{ $value }}"
                                                        value="{{ $value }}"
                                                        {{ old('status') === $value ? 'checked' : '' }}
                                                    >
                                                    <label class="account-status-picker__label" for="status_{{ $value }}">
                                                        <span class="account-status-picker__dot account-status-picker__dot--{{ $value }}"></span>
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="small text-muted mb-0 mt-2">Pick how your profile should appear to other users.</p>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium" for="name">Display Name</label>
                                            <input id="name" type="text" name="name" value="{{ old('name', $profile['name']) }}" class="form-control account-input" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium" for="email">Email Address</label>
                                            <input id="email" type="email" name="email" value="{{ old('email', $profile['email']) }}" class="form-control account-input" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium" for="role">Role</label>
<input id="role" type="text" name="role" value="{{ old('role') }}" class="form-control account-input">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium" for="location">Location</label>
<input id="location" type="text" name="location" value="{{ old('location') }}" class="form-control account-input">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-medium" for="bio">Profile Bio</label>
                                            <textarea id="bio" name="bio" class="form-control account-input" rows="4">{{ old('bio', $profile['bio']) }}</textarea>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-medium" for="avatar_file">Upload Avatar</label>
                                            <input id="avatar_file" type="file" name="avatar_file" accept="image/*" class="form-control account-input">
                                            <div class="form-text small text-muted">Optional. Upload an image to use as your profile photo. If left empty, the existing/default avatar will remain.</div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                                        <a href="{{ url('/account') }}" class="btn btn-outline-secondary">Reset</a>
                                        <button type="submit" class="btn btn-dark px-4">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .account-page {
        width: 100%;
    }

    .account-page__inner {
        width: min(100%, 1120px);
    }

    .account-page__hero {
        border: 1px solid rgba(15, 23, 42, 0.06);
    }

    .account-page__summary {
        min-width: 260px;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(55, 65, 81, 0.94));
        color: #fff;
        border-radius: 1.5rem;
    }

    .account-page__summary .text-muted,
    .account-page__summary-copy {
        color: rgba(255, 255, 255, 0.72) !important;
    }

    .account-card {
        border-radius: 1.5rem;
        background-color: #fff;
    }

    .account-avatar {
        width: 132px;
        height: 132px;
    }

    .account-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .account-details {
        display: grid;
        gap: 1rem;
    }

    .account-detail {
        padding: 1rem 1.1rem;
        border-radius: 1rem;
        background-color: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.08);
        overflow-wrap: break-word;
        word-break: break-word;
    }

    .account-input {
        border-radius: 0.9rem;
        border: 1px solid rgba(15, 23, 42, 0.12);
        padding: 0.85rem 1rem;
        background-color: #f8fafc;
        color: #0f172a;
    }

    .account-input:focus {
        border-color: rgba(15, 23, 42, 0.45);
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.08);
        background-color: #fff;
        color: #0f172a;
    }

    .account-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0.55rem 0.95rem;
        border-radius: 0.85rem;
        font-size: 0.95rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .account-badge--active {
        background: rgba(34, 197, 94, 0.14);
        border-color: rgba(34, 197, 94, 0.36);
        color: #bbf7d0;
    }

    .account-badge--inactive {
        background: rgba(148, 163, 184, 0.16);
        border-color: rgba(148, 163, 184, 0.3);
        color: #e2e8f0;
    }

    .account-badge--busy {
        background: rgba(245, 158, 11, 0.16);
        border-color: rgba(245, 158, 11, 0.32);
        color: #fde68a;
    }

    .account-badge--seller {
        background: rgba(96, 165, 250, 0.18);
        border-color: rgba(96, 165, 250, 0.34);
        color: #dbeafe;
    }

    .account-badge--muted {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.12);
        color: #f8fafc;
    }

    .account-status-picker {
        padding: 1.2rem;
        border-radius: 1.25rem;
        background: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.08);
    }

    .account-status-picker__grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.85rem;
    }

    .account-status-picker__input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .account-status-picker__label {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        width: 100%;
        padding: 0.95rem 1rem;
        border-radius: 1rem;
        border: 1px solid rgba(15, 23, 42, 0.1);
        background: #fff;
        color: #0f172a;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .account-status-picker__label:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .account-status-picker__input:checked + .account-status-picker__label {
        border-color: rgba(15, 23, 42, 0.35);
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.08);
        background: #ffffff;
    }

    .account-status-picker__dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        flex-shrink: 0;
    }

    .account-status-picker__dot--active {
        background: #22c55e;
    }

    .account-status-picker__dot--inactive {
        background: #94a3b8;
    }

    .account-status-picker__dot--busy {
        background: #f59e0b;
    }

    @media (min-width: 992px) {
        .account-page {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
        }
    }

    @media (max-width: 991.98px) {
        .account-page__summary {
            min-width: 0;
        }
    }

    @media (max-width: 767.98px) {
        .account-status-picker__grid {
            grid-template-columns: 1fr;
        }
    }
</style>
