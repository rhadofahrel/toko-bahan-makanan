@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
    <h2 class="auth-card-title">Buat Akun Baru</h2>
    <p class="auth-card-subtitle">Daftar dan mulai belanja bahan makanan segar</p>

    <form id="register-form" action="{{ route('register.post') }}" method="POST" novalidate>
        @csrf

        {{-- Nama Lengkap --}}
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <div class="input-wrapper">
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap"
                    autocomplete="name"
                    required
                >
                <i class="fas fa-user input-icon"></i>
            </div>
            @error('name')
                <div class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>
            @enderror
        </div>

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
                    placeholder="Minimal 6 karakter"
                    autocomplete="new-password"
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

        {{-- Konfirmasi Password --}}
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <div class="input-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    autocomplete="new-password"
                    class="has-eye"
                    required
                >
                <i class="fas fa-shield-halved input-icon"></i>
                <button type="button" class="eye-toggle" id="toggle-confirm" aria-label="Tampilkan konfirmasi password">
                    <i class="fas fa-eye" id="eye-icon-confirm"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-auth" id="submit-btn">
            <i class="fas fa-user-plus"></i>
            Daftar / Register
        </button>
    </form>

    <hr class="auth-divider">

    <div class="auth-footer">
        Sudah punya akun?
        <a href="{{ route('login') }}">Login di sini</a>
    </div>
@endsection

@push('scripts')
<script>
    // Show/hide password
    function toggleEye(inputId, iconId, btnId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        document.getElementById(btnId).addEventListener('click', function() {
            const hidden = input.type === 'password';
            input.type   = hidden ? 'text' : 'password';
            icon.className = hidden ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    }
    toggleEye('password',              'eye-icon',         'toggle-password');
    toggleEye('password_confirmation', 'eye-icon-confirm', 'toggle-confirm');

    // Loading state on submit
    document.getElementById('register-form').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendaftarkan...';
    });

    // Show validation errors as toast
    @if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ addslashes($errors->first()) }}', 'error');
    });
    @endif
</script>
@endpush
