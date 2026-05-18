<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevBuy - Verify Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, rgba(255, 255, 255, 0.08), transparent 30%),
                linear-gradient(140deg, #050505 0%, #111111 46%, #000000 100%);
            color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Inter, system-ui, sans-serif;
            margin: 0;
            padding: 1.5rem;
        }

        .signup-shell {
            width: min(920px, 100%);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.01)),
                #0d0d0d;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.45);
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            min-height: 520px;
        }

        .signup-panel {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1rem;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), transparent 70%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.02), transparent 55%);
        }

        .signup-panel h1 {
            font-size: 2.6rem;
            line-height: 1.08;
            margin-bottom: 0.5rem;
        }

        .signup-panel p {
            color: rgba(245, 245, 245, 0.72);
            line-height: 1.8;
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
            padding: 1rem;
            border: 1px solid #b7b7b7;
            background: #ffffff;
            color: #111111;
            letter-spacing: 0.35rem;
            font-size: 1.35rem;
            font-weight: 700;
            text-align: center;
        }

        .signup-card .form-control:focus {
            border-color: #111111;
            box-shadow: 0 0 0 0.2rem rgba(17, 17, 17, 0.12);
        }

        .signup-card .btn-primary,
        .signup-card .btn-outline-dark {
            border-radius: 999px;
            padding: 0.95rem 1.25rem;
            font-weight: 600;
        }

        .signup-card .btn-primary {
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

        .signup-card .text-muted {
            color: #5c5c5c !important;
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
            <div>
                <h1>Check your email.</h1>
                <p>DevBuy sent a one-time code to finish creating your account.</p>
            </div>
        </section>

        <section class="signup-card">
            <h2>Verify signup</h2>
            <p class="text-muted">
                Enter the 6-digit code sent to <strong>{{ $pendingVerification['email'] }}</strong>.
                @if ($pendingVerification['expires_at'])
                    It expires at {{ $pendingVerification['expires_at'] }}.
                @endif
            </p>

            @if (session('success'))
                <div class="alert-custom">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-custom">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('signup.verify.submit') }}" method="POST" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="code">Verification Code</label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" class="form-control" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary w-100">Verify and Create Account</button>
            </form>

            <form action="{{ route('signup.verify.resend') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-dark w-100">Send New Code</button>
            </form>
        </section>
    </main>
</body>

</html>
