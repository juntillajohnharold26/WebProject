@php
    $categories = ['UI Kit', 'Dashboard', 'Landing', 'E-commerce', 'Mobile', 'Components', 'Icon Pack'];
    $statuses = ['draft' => 'Draft', 'active' => 'Active', 'archived' => 'Archived'];
@endphp

<x-menu>
    <x-sidebar>
        <section class="edit-template-page py-4">
            <div class="edit-template__inner mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('seller.templates.index') }}" class="btn btn-outline-dark btn-sm mb-2">&larr; Dashboard</a>
                        <h1 class="h3 fw-bold mb-0">Edit Template</h1>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('seller.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-5">
                                    <h3 class="h5 fw-bold mb-4">Details</h3>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">Title *</label>
                                        <input type="text" name="title" value="{{ old('title', $template->title) }}" class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">Description *</label>
                                        <textarea name="description" rows="5" class="form-control rounded-3 @error('description') is-invalid @enderror" required>{{ old('description', $template->description) }}</textarea>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold mb-2">Category *</label>
                                            <select name="category" class="form-select rounded-3 @error('category') is-invalid @enderror" required>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat }}" {{ old('category', $template->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold mb-2">Price ($)*</label>
                                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $template->price) }}" class="form-control rounded-3 @error('price') is-invalid @enderror" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">Tags</label>
                                        <input type="text" name="tags" value="{{ old('tags', implode(', ', $template->tags ?? [])) }}" class="form-control rounded-3">
                                    </div>

                                    <div class="border-top pt-4">
                                        <label class="form-label fw-semibold mb-3">Status</label>
                                        <div class="row g-3">
                                            @foreach($statuses as $value => $label)
                                                <div class="col-auto">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status" id="status_{{ $value }}" value="{{ $value }}" {{ old('status', $template->status) == $value ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="status_{{ $value }}">{{ $label }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <h3 class="h5 fw-bold mb-4">Files</h3>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">ZIP File</label>
                                        <p class="mb-1"><strong>{{ basename(Storage::url($template->zip_path)) }}</strong></p>
                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="delete_zip" id="delete_zip" class="form-check-input">
                                            <label class="form-check-label small text-danger" for="delete_zip">Delete &amp; replace</label>
                                        </div>
                                        <input type="file" name="zip" class="form-control @error('zip') is-invalid @enderror" accept=".zip">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-2">Preview Images ({{ count($template->preview_images) }})</label>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            @foreach($template->preview_images ?? [] as $preview)
                                                <img src="{{ Storage::url($preview) }}" style="width:60px; height:40px; object-fit:cover; border-radius:0.375rem; border:1px solid #eee;" alt="Preview">
                                            @endforeach
                                        </div>
                                        <input type="file" name="preview_images[]" class="form-control" accept="image/*" multiple>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('seller.templates.index') }}" class="btn btn-outline-secondary rounded-3">Cancel</a>
                                        <button type="submit" class="btn btn-dark rounded-3 fw-bold py-2">Update Template</button>
                                    </div>
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
.edit-template-page {
    background: #f8fafc;
}

.edit-template__inner {
    max-width: 1200px;
}

.form-control, .form-control::placeholder {
    background-color: white;
    color: black;
}
.form-control:focus {
    border-color: #0f172a;
    box-shadow: 0 0 0 0.25rem rgba(15, 23, 42, 0.1);
}

.card {
    border-radius: 1.5rem;
}
</style>

