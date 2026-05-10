<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FreshMart - Toko Bahan Makanan Premium</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #3b82f6;
            --accent: #f59e0b;
            --background: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow);
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 600;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        /* User dropdown */
        .user-menu {
            position: relative;
        }
        .user-trigger {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            background: var(--primary-light, #d1fae5);
            border: none;
            border-radius: 50px;
            padding: 0.4rem 0.9rem 0.4rem 0.5rem;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-dark, #059669);
            transition: background 0.2s;
        }
        .user-trigger:hover { background: #a7f3d0; }
        .user-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .user-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            padding: 0.5rem;
            z-index: 999;
            display: none;
            animation: dropFade 0.2s ease;
        }
        .user-dropdown.open { display: block; }
        @keyframes dropFade {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .dropdown-header {
            padding: 0.5rem 0.75rem 0.75rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 0.35rem;
        }
        .dropdown-header .d-name  { font-weight: 700; font-size: 0.9rem; color: var(--text-main); }
        .dropdown-header .d-email { font-size: 0.75rem; color: var(--text-muted); }
        .dropdown-header .d-role  {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 1px 8px;
            border-radius: 20px;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .role-admin    { background: #fef3c7; color: #92400e; }
        .role-customer { background: #d1fae5; color: #065f46; }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-main);
            transition: background 0.15s;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
            font-family: inherit;
        }
        .dropdown-item:hover { background: #f1f5f9; }
        .dropdown-item.danger { color: #ef4444; }
        .dropdown-item.danger:hover { background: #fef2f2; }

        /* Toast Notifications */
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
            background: white;
            border: 1px solid var(--border);
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

        .cart-trigger {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: var(--text-main);
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--accent);
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 50%;
            font-weight: 700;
        }

        main {
            flex: 1;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            width: 100%;
        }

        footer {
            background-color: var(--text-main);
            color: white;
            padding: 3rem 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .footer-section p {
            color: #94a3b8;
        }

        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #334155;
            color: #64748b;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
        }

        .modal-container {
            background: var(--surface);
            padding: 2rem;
            border-radius: var(--radius);
            max-width: 600px;
            width: 90%;
            box-shadow: var(--shadow-lg);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 1rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
        }

        /* Utility classes */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .user-trigger span.user-name { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('home') }}" class="logo">
                <i class="fas fa-leaf"></i> FreshMart
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                @if(session('user_role') === 'admin')
                <li><a href="{{ route('admin.home') }}">Admin Panel</a></li>
                @endif
            </ul>
            <div style="display:flex;align-items:center;gap:1rem;">
                @if(session('user_role') !== 'admin')
                <button class="cart-trigger" id="open-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" id="cart-badge">0</span>
                </button>
                @endif

                @if(session('user_id'))
                <div class="user-menu">
                    <button class="user-trigger" id="user-menu-btn" aria-haspopup="true" aria-expanded="false">
                        <div class="user-avatar">
                            {{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}
                        </div>
                        <span class="user-name">{{ session('user_name') }}</span>
                        <i class="fas fa-chevron-down" style="font-size:0.7rem;"></i>
                    </button>
                    <div class="user-dropdown" id="user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-name">{{ session('user_name') }}</div>
                            <div class="d-email">{{ session('user_email') }}</div>
                            @if(session('user_role') === 'admin')
                                <span class="d-role role-admin">Admin</span>
                            @else
                                <span class="d-role role-customer">Customer</span>
                            @endif
                        </div>
                        @if(session('user_role') === 'admin')
                        <a href="{{ route('admin.home') }}" class="dropdown-item">
                            <i class="fas fa-gauge"></i> Admin Panel
                        </a>
                        @else
                        <a href="{{ route('home') }}" class="dropdown-item">
                            <i class="fas fa-store"></i> Toko
                        </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="dropdown-item danger">
                                <i class="fas fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-right-to-bracket"></i> Login
                </a>
                @endif
            </div>
        </nav>
    </header>

    <div class="toast-container" id="toast-container"></div>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>FreshMart</h3>
                <p>Menyediakan bahan makanan segar dan berkualitas langsung dari petani lokal untuk keluarga Anda.</p>
            </div>
            <div class="footer-section">
                <h3>Tautan Cepat</h3>
                <p>Tentang Kami</p>
                <p>Kontak</p>
                <p>Syarat & Ketentuan</p>
            </div>
            <div class="footer-section">
                <h3>Hubungi Kami</h3>
                <p><i class="fas fa-map-marker-alt"></i> Jl. Sehat No. 123, Jakarta</p>
                <p><i class="fas fa-phone"></i> +62 812 3456 7890</p>
                <p><i class="fas fa-envelope"></i> info@freshmart.id</p>
            </div>
        </div>
        <div class="copyright">
            &copy; {{ date('Y') }} FreshMart. All rights reserved.
        </div>
    </footer>

    <!-- Global Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cart badge
            @if(session('user_role') !== 'admin')
            window.updateCartBadge = function() {
                fetch('{{ route('cart.get') }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const badge = document.getElementById('cart-badge');
                            if (badge) {
                                let count = 0;
                                data.cart.forEach(item => count += item.quantity);
                                badge.textContent = count;
                            }
                        }
                    }).catch(() => {});
            };
            updateCartBadge();
            @endif

            // User dropdown toggle
            const menuBtn  = document.getElementById('user-menu-btn');
            const dropdown = document.getElementById('user-dropdown');
            if (menuBtn && dropdown) {
                menuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = dropdown.classList.toggle('open');
                    menuBtn.setAttribute('aria-expanded', isOpen);
                });
                document.addEventListener('click', function() {
                    dropdown.classList.remove('open');
                    menuBtn.setAttribute('aria-expanded', 'false');
                });
            }
            // Toast function
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                if (!container) return;
                const toast = document.createElement('div');
                toast.className = 'toast ' + type;
                const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
                toast.innerHTML = `<i class="fas ${icon}"></i><span>${message}</span>`;
                container.appendChild(toast);
                setTimeout(() => {
                    toast.style.animation = 'slideOut 0.35s ease forwards';
                    setTimeout(() => toast.remove(), 360);
                }, 4000);
            };

            // Show flash messages
            @if(session('success'))
                showToast('{{ addslashes(session('success')) }}', 'success');
            @endif
            @if(session('error'))
                showToast('{{ addslashes(session('error')) }}', 'error');
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>
