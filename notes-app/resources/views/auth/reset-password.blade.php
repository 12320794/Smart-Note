@extends('layouts.auth')
@section('title', 'Reset Password – Smart Notes Hub')

@section('content')
<h2 class="auth-title">Reset Password 🔐</h2>
<p style="font-size: .85rem; color: #64748b; text-align: center; margin-bottom: 1.5rem;">
    Enter your new password below to reset.
</p>

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    {{-- Email --}}
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-icon-wrap">
            <i class="bi bi-envelope-fill"></i>
            <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   placeholder="you@example.com" value="{{ old('email', $email) }}" required readonly>
        </div>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="form-group">
        <label class="form-label">New Password</label>
        <div class="input-icon-wrap" style="position: relative;">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="password" id="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="••••••••" required style="padding-right: 2.5rem;">
            <button type="button" onclick="togglePasswordVisibility('password', 'toggleIcon1')" 
                    style="position: absolute; right: .75rem; top: 50%; transform: translateY(-50%); 
                           background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0;">
                <i class="bi bi-eye-slash-fill" id="toggleIcon1"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Confirm Password --}}
    <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <div class="input-icon-wrap" style="position: relative;">
            <i class="bi bi-shield-lock-fill"></i>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input"
                   placeholder="Repeat new password" required style="padding-right: 2.5rem;">
            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'toggleIcon2')" 
                    style="position: absolute; right: .75rem; top: 50%; transform: translateY(-50%); 
                           background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0;">
                <i class="bi bi-eye-slash-fill" id="toggleIcon2"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn-auth">
        <i class="bi bi-shield-check-fill me-2"></i> Reset Password
    </button>
</form>

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
