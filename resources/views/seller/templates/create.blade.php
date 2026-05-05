@php
    $categories = ['UI Kit', 'Dashboard', 'Landing', 'E-commerce', 'Mobile', 'Components', 'Icon Pack'];
    $statuses = ['draft' => 'Save as Draft', 'active' => 'Submit for Review', 'archived' => 'Archive'];

@endphp

<x-menu>
    <x-sidebar>
        <section class="create-template-page py-4">
            <div class="create-template__inner mx-auto">
                <a href="{{ url()->previous() }}" class="btn btn-outline-dark btn-sm mb-4">&larr; Back</a>
                <h1 class="h2 fw-bold mb-4">Create New Template</h1>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
                @endif

                <form action="{{ route('seller.templates.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-5">
                                    <h3 class="h5 fw-bold mb-4">Template Details</h3>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">Title *</label>
                                        <input type="text" name="title" value="{{ old('title') }}" class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror" placeholder="e.g. Modern Admin Dashboard UI Kit" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">Description *</label>
                                        <textarea name="description" rows="5" class="form-control rounded-3 @error('description') is-invalid @enderror" placeholder="Describe your template, features, usage, what's included..." required>{{ old('description') }}</textarea>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold mb-2">Category *</label>
                                            <select name="category" class="form-select rounded-3 @error('category') is-invalid @enderror" required>
                                                <option value="">Select category</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold mb-2">Price ($)*</label>
                                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', 19.99) }}" class="form-control rounded-3 @error('price') is-invalid @enderror" placeholder="29.99" required>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="form-label fw-semibold mb-2">Tags</label>
                                        <p class="form-text small mb-2">Comma separated (max 10)</p>
                                        <input type="text" name="tags" value="{{ old('tags') }}" class="form-control rounded-3" placeholder="figma, tailwind, dashboard, admin, responsive">
                                    </div>

                                    <div class="mt-5 pt-4 border-top">
                                        <label class="form-label fw-semibold mb-2">Publishing Status</label>
                                        <div class="row g-3">
                                            @foreach($statuses as $value => $label)
                                                <div class="col-auto">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status" id="status_{{ $value }}" value="{{ $value }}" {{ old('status', 'draft') == $value ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-medium" for="status_{{ $value }}">
                                                            {{ $label }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="alert alert-info rounded-3 mt-3 mb-0 small">
                                            <strong>Note:</strong> Selecting "Submit for Review" will send your template to our admin team for approval before it appears on the marketplace.
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <h3 class="h5 fw-bold mb-4">Upload Files</h3>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">Template ZIP * (max 50MB)</label>
                                        <input type="file" name="zip" class="form-control @error('zip') is-invalid @enderror" accept=".zip" required>
                                        <div class="form-text">ZIP containing your template files (Figma, HTML, etc.)</div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">Preview Images (1-5, max 5MB each)</label>
                                        <div class="dropzone-preview">
                                            <input type="file" name="preview_images[]" class="form-control @error('preview_images') is-invalid @enderror" accept="image/*" multiple>
                                        </div>
                                        <div class="form-text small mt-1">High-quality screenshots/demos. First image used as cover.</div>
                                    </div>

                                    <hr class="my-4">

                                    <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3 fs-6">
                                        Create Template
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
.create-template-page {
    background: #f8fafc;
}

.create-template__inner {
    max-width: 1200px;
}

.form-control, .form-control::placeholder, .form-select {
    background-color: white;
    color: black;
}
.form-control:focus, .form-select:focus {
    border-color: #0f172a;
    box-shadow: 0 0 0 0.25rem rgba(15, 23, 42, 0.1);
}

.card {
    border-radius: 1.5rem;
}

.needs-validation {
    position: relative;
}

@media (max-width: 991px) {
    .dropzone-preview input[type="file"] {
        margin-bottom: 1rem;
    }
}
</style>

<script>
// Preview upload helper
document.addEventListener('DOMContentLoaded', function() {
    const previewInput = document.querySelector('input[name="preview_images[]"]');
    const dropzone = previewInput.parentElement;
    
    previewInput.addEventListener('change', function() {
        dropzone.classList.add('border-primary');
        setTimeout(() => dropzone.classList.remove('border-primary'), 2000);
    });
});
</script>
