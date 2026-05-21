@extends('layouts.auth')
@section('title', 'Login – Smart Notes Hub')

@section('content')
<h2 class="auth-title">Welcome Back 👋</h2>

<form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Email --}}
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-icon-wrap">
            <i class="bi bi-envelope-fill"></i>
            <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="form-group">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: .35rem;">
            <label class="form-label" style="margin-bottom: 0;">Password</label>
            <a href="{{ route('password.request') }}" style="font-size: .78rem; font-weight: 600; color: var(--primary); text-decoration: none;">
                Forgot Password?
            </a>
        </div>
        <div class="input-icon-wrap" style="position: relative;">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="password" id="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="••••••••" required style="padding-right: 2.5rem;">
            <button type="button" onclick="togglePasswordVisibility('password', 'toggleIcon')" 
                    style="position: absolute; right: .75rem; top: 50%; transform: translateY(-50%); 
                           background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0;">
                <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Remember me --}}
    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <input type="checkbox" name="remember" id="remember" style="accent-color:#6366f1; width: 15px; height: 15px; cursor: pointer;">
        <label for="remember" style="font-size:.82rem;color:#64748b;cursor:pointer;user-select:none;">Remember me</label>
    </div>

    <button type="submit" class="btn-auth">
        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
    </button>
</form>

<div class="auth-footer">
    Don't have an account? <a href="{{ route('register') }}">Create one free</a>
</div>

<script>
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye-slash-fill');
        icon.classList.add('bi-eye-fill');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-fill');
        icon.classList.add('bi-eye-slash-fill');
    }
}
</script>
@endsection
