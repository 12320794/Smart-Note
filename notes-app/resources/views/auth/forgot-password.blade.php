@extends('layouts.auth')
@section('title', 'Forgot Password – Smart Notes Hub')

@section('content')
<h2 class="auth-title">Forgot Password? 🔒</h2>
<p style="font-size: .85rem; color: #64748b; text-align: center; margin-bottom: 1.5rem;">
    No worries! Enter your email address and we'll send/log a link to reset your password.
</p>

{{-- Local development convenience link --}}
@if(session('local_reset_url'))
    <div style="background: rgba(16, 185, 129, 0.08); border: 1.5px solid rgba(16, 185, 129, 0.2); 
                border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; text-align: left;">
        <div style="font-size: .75rem; font-weight: 700; color: #059669; text-transform: uppercase; margin-bottom: .25rem;">
            💡 Local Development Assistant
        </div>
        <p style="font-size: .8rem; color: #047857; margin-bottom: .5rem;">
            Since mail mailer is set to log, here is the generated link for immediate use:
        </p>
        <a href="{{ session('local_reset_url') }}" class="btn-auth" 
           style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; padding: .5rem; font-size: .8rem; margin-top: 0; background: #10b981;">
            <i class="bi bi-link-45deg me-1"></i> Open Reset Password Page
        </a>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
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

    <button type="submit" class="btn-auth">
        <i class="bi bi-send-fill me-2"></i> Send Reset Link
    </button>
</form>

<div class="auth-footer">
    Remember your password? <a href="{{ route('login') }}">Back to Login</a>
</div>
@endsection
