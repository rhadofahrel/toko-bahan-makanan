@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h2 class="auth-card-title">Selamat Datang!</h2>
    <p class="auth-card-subtitle">Masuk ke akun FreshMart Anda</p>

    {{-- Validation errors global --}}
    @if ($errors->any())
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ addslashes($errors->first()) }}', 'error');
            });
        </script>
        @endpush
    @endif

    <form id="login-form" action="{{ route('login.post') }}" method="POST" novalidate>
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@email.com"
                    autocomplete="email"
                    required
                >
                <i class="fas fa-envelope input-icon"></i>
            </div>
            @error('email')
                <div class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    class="has-eye"
                    required
                >
                <i class="fas fa-lock input-icon"></i>
                <button type="button" class="eye-toggle" id="toggle-password" aria-label="Tampilkan password">
                    <i class="fas fa-eye" id="eye-icon"></i>
                </button>
            </div>
            @error('password')
                <div class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="checkbox-group">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="btn-auth" id="submit-btn">
            <i class="fas fa-right-to-bracket"></i>
            Login
        </button>
    </form>

    <hr class="auth-divider">

    <div class="auth-footer">
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar di sini</a>
    </div>
@endsection

@push('scripts')
<script>
    // Show/hide password
    const toggleBtn  = document.getElementById('toggle-password');
    const passwordEl = document.getElementById('password');
    const eyeIcon    = document.getElementById('eye-icon');

    toggleBtn.addEventListener('click', function() {
        const isHidden = passwordEl.type === 'password';
        passwordEl.type = isHidden ? 'text' : 'password';
        eyeIcon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
    });

    // Loading state on submit
    document.getElementById('login-form').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    });
</script>
@endpush
