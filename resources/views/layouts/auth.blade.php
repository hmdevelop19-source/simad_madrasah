<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — SIMAD</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:      #4F46E5;
            --primary-dark: #3730A3;
            --surface:      #FFFFFF;
            --text:         #1E293B;
            --text-muted:   #64748B;
            --border:       #E2E8F0;
            --danger:       #EF4444;
            --success:      #22C55E;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #4F46E5 50%, #3730A3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* Auth card — container untuk form login/register */
        .auth-card {
            background: var(--surface);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(79,70,229,0.2);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Alert */
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            border-left: 3px solid transparent;
        }
        .alert-danger  { background: #FEF2F2; color: #DC2626; border-color: var(--danger); }
        .alert-success { background: #F0FDF4; color: #16A34A; border-color: var(--success); }
    </style>

    @stack('styles')
</head>
<body>

<div class="auth-card">
    {{--
        @yield('content') → diisi oleh halaman login/register
        Layout ini HANYA dipakai untuk halaman yang tidak butuh sidebar
    --}}
    @yield('content')
</div>

@stack('scripts')
</body>
</html>
