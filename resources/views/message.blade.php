<x-menu>
    <x-sidebar>
        <section class="py-5">
            <div class="container-fluid px-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-dark btn-sm">← Back</a>
                    </div>
                </div>

                <div class="row align-items-center mb-4">
                    <div class="col-lg-8">
                        <h1 class="display-6 fw-bold">{{ $message['subject'] }}</h1>
                        <p class="text-muted">From {{ $message['sender'] }} · {{ $message['time'] }}</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold">Message</h5>
                        <p class="text-muted mb-0">{{ $message['body'] }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ url('/contact') }}" class="btn btn-dark btn-sm">Contact Support</a>
                    </div>
                </div>
            </div>
        </section>
    </x-sidebar>
</x-menu>

<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    }
</style>
