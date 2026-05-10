<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FreshMart') — Toko Bahan Makanan</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary:       #10b981;
            --primary-dark:  #059669;
            --primary-light: #d1fae5;
            --secondary:     #3b82f6;
            --danger:        #ef4444;
            --warning:       #f59e0b;
            --text-main:     #0f172a;
            --text-muted:    #64748b;
            --border:        #e2e8f0;
            --surface:       #ffffff;
            --bg:            #f0fdf4;
            --radius:        14px;
            --shadow:        0 4px 6px -1px rgb(0 0 0 / 0.08), 0 2px 4px -2px rgb(0 0 0 / 0.06);
            --shadow-lg:     0 20px 40px -8px rgb(0 0 0 / 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 30%, #047857 60%, #10b981 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* decorative blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            opacity: 0.12;
            pointer-events: none;
        }
        body::before {
            width: 500px; height: 500px;
            background: #34d399;
            top: -150px; right: -150px;
        }
        body::after {
            width: 400px; height: 400px;
            background: #6ee7b7;
            bottom: -120px; left: -120px;
        }

        /* ── Brand header ── */
        .auth-brand {
            text-align: center;
            margin-bottom: 1.75rem;
            position: relative;
            z-index: 1;
        }
        .auth-brand .brand-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: #fff;
            margin-bottom: 0.75rem;
            backdrop-filter: blur(6px);
        }
        .auth-brand h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .auth-brand p {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }

        /* ── Card ── */
        .auth-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 2.25rem 2.5rem;
            width: 100%;
            max-width: 460px;
            box-shadow: var(--shadow-lg);
            position: relative;
            z-index: 1;
        }

        .auth-card-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.25rem;
        }
        .auth-card-subtitle {
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-bottom: 1.75rem;
        }

        /* ── Form elements ── */
        .form-group {
            margin-bottom: 1.1rem;
        }
        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .input-wrapper input {
            width: 100%;
            padding: 0.7rem 1rem 0.7rem 2.6rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text-main);
            background: #f8fafc;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            outline: none;
        }
        .input-wrapper input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.12);
        }
        .input-wrapper input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--primary);
        }
        /* eye toggle on the right */
        .input-wrapper .eye-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.95rem;
            transition: color 0.2s;
            padding: 0;
        }
        .input-wrapper .eye-toggle:hover { color: var(--primary); }
        .input-wrapper input.has-eye { padding-right: 2.8rem; }

        .field-error {
            color: var(--danger);
            font-size: 0.78rem;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* ── Checkbox ── */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .checkbox-group input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }
        .checkbox-group label {
            font-size: 0.875rem;
            color: var(--text-muted);
            cursor: pointer;
        }

        /* ── Button ── */
        .btn-auth {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16,185,129,0.35);
        }
        .btn-auth:active { transform: translateY(0); }

        /* ── Auth footer link ── */
        .auth-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        .auth-footer a:hover { color: var(--primary-dark); text-decoration: underline; }

        /* ── Divider ── */
        .auth-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.25rem 0;
        }

        /* ── Toast ── */
        .toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            max-width: 340px;
        }
        .toast {
            padding: 0.85rem 1.1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            box-shadow: var(--shadow-lg);
            animation: slideIn 0.35s ease forwards;
        }
        .toast.success { background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; }
        .toast.error   { background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
        .toast i { margin-top: 1px; flex-shrink: 0; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(50px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(50px); }
        }

        /* ── Responsive ── */
        @media (max-width: 500px) {
            .auth-card { padding: 1.75rem 1.25rem; }
        }
    </style>
</head>
<body>
    <!-- Toast container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Brand -->
    <div class="auth-brand">
        <div class="brand-icon"><i class="fas fa-leaf"></i></div>
        <h1>FreshMart</h1>
        <p>Bahan makanan segar & berkualitas</p>
    </div>

    <!-- Card -->
    <div class="auth-card">
        @yield('content')
    </div>

    <!-- Flash messages as toasts -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ addslashes(session('success')) }}', 'success');
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ addslashes(session('error')) }}', 'error');
        });
    </script>
    @endif

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast ' + type;
            const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
            toast.innerHTML = `<i class="fas ${icon}"></i><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.35s ease forwards';
                setTimeout(() => toast.remove(), 360);
            }, 4000);
        }
        window.showToast = showToast;
    </script>
    @stack('scripts')
</body>
</html>
