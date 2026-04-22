<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevBuy - Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, rgba(255, 255, 255, 0.08), transparent 30%),
                linear-gradient(140deg, #050505 0%, #111111 46%, #000000 100%);
            color: #f5f5f5;
            display: grid;
            place-items: center;
            font-family: Inter, system-ui, sans-serif;
            margin: 0;
            padding: 1.5rem;
        }

        .signup-shell {
            width: min(1120px, 100%);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.01)),
                #0d0d0d;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.45);
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            min-height: 640px;
        }

        .signup-panel {
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1.8rem;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), transparent 70%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.02), transparent 55%);
        }

        .signup-panel h1 {
            font-size: clamp(2.4rem, 2.2vw, 3rem);
            line-height: 1.05;
            margin-bottom: 0.5rem;
        }

        .signup-panel p {
            color: rgba(245, 245, 245, 0.72);
            max-width: 34rem;
            line-height: 1.8;
        }

        .signup-panel .pill {
            width: fit-content;
            padding: 0.85rem 1.2rem;
            border-radius: 999px;
            background: #ffffff;
            color: #111111;
            font-weight: 600;
        }

        .signup-panel .feature-list {
            display: grid;
            gap: 1rem;
            padding-top: 1rem;
        }

        .signup-panel .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            color: rgba(248, 249, 250, 0.85);
        }

        .signup-panel .feature-item span {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.85rem;
            background: rgba(255, 255, 255, 0.14);
            display: grid;
            place-items: center;
            font-weight: 700;
            color: #ffffff;
        }

        .signup-card {
            padding: 3rem 3.5rem;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)),
                #f5f5f5;
            color: #111111;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .signup-card h2 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }

        .signup-card .form-control {
            border-radius: 1rem;
            padding: 1rem 1rem;
            border: 1px solid #b7b7b7;
            background: #ffffff;
            color: #111111;
        }

        .signup-card .form-control:focus {
            border-color: #111111;
            box-shadow: 0 0 0 0.2rem rgba(17, 17, 17, 0.12);
        }

        .signup-card .btn-primary {
            border-radius: 999px;
            padding: 0.95rem 1.25rem;
            font-weight: 600;
            border-color: #111111;
            background: #111111;
            color: #ffffff;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.16);
        }

        .signup-card .btn-primary:hover,
        .signup-card .btn-primary:focus {
            border-color: #000000;
            background: #000000;
            color: #ffffff;
        }

        .signup-card .link-row {
            text-align: center;
            margin-top: 1.5rem;
        }

        .signup-card .link-row a {
            color: #111111;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-card .link-row a:hover {
            text-decoration: underline;
        }

        .signup-card .text-muted {
            color: #5c5c5c !important;
        }

        .signup-card .text-danger {
            color: #303030 !important;
        }

        .form-label {
            font-weight: 600;
            color: #111111;
        }

        .alert-custom {
            background: #111111;
            color: #f5f5f5;
            border: 1px solid rgba(17, 17, 17, 0.08);
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            margin-bottom: 1.3rem;
            font-size: 0.95rem;
        }

        @media (max-width: 992px) {
            .signup-shell {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .signup-panel,
            .signup-card {
                padding: 2.5rem 1.75rem;
            }
        }
    </style>
</head>

<body>
    <main class="signup-shell">
        <section class="signup-panel">
            <span class="pill">Start with DevBuy</span>
            <div>
                <h1>Create your DevBuy account.</h1>
                <p>Register and access services, assets, and a personalized explore experience designed for your projects.</p>
            </div>
        </section>

        <section class="signup-card">
            <h2>Create account</h2>
            <p class="text-muted">Fill in the details below to get started.</p>

            @if (session('error'))
                <div class="alert-custom">{{ session('error') }}</div>
            @endif

            <form action="{{ route('signup.submit') }}" method="POST" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                @error('name')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror
                @error('email')
                    <div class="text-danger mb-2">{{ $message }}</div>
                @enderror
                @error('password')
                    <div class="text-danger mb-3">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn btn-primary w-100">Create account</button>
            </form>

            <div class="link-row">
                <p class="mb-0 text-muted">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
            </div>
        </section>
    </main>
</body>

</html>

