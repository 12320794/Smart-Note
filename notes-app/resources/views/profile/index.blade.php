@extends('layouts.app')
@section('title', 'Profile & Settings')

@section('content')

<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-circle" style="color:var(--primary);"></i> Profile & Settings</h1>
</div>

<div class="row g-4">

    {{-- ─── LEFT: Profile Card ─── --}}
    <div class="col-12 col-lg-4">
        <div class="card-surface p-4 text-center">
            {{-- Avatar --}}
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" 
                     style="width:90px;height:90px;border-radius:50%;object-fit:cover;margin-bottom:1rem;
                            box-shadow:0 8px 24px rgba(99,102,241,.35); border: 2.5px solid var(--primary);" alt="Avatar">
            @else
                <div style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));
                            display:inline-flex;align-items:center;justify-content:center;
                            font-size:2.25rem;font-weight:800;color:#fff;margin-bottom:1rem;
                            box-shadow:0 8px 24px rgba(99,102,241,.35);">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            <h2 style="font-size:1.1rem;font-weight:700;color:var(--text);margin-bottom:.25rem;">
                {{ $user->name }}
            </h2>
            <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:1.5rem;">
                {{ $user->email }}
            </p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;text-align:left;">
                <div style="background:var(--surface-2);border-radius:10px;padding:.75rem;">
                    <div style="font-size:1.25rem;font-weight:800;color:var(--primary);">
                        {{ Auth::user()->notes()->count() }}
                    </div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:500;">Total Notes</div>
                </div>
                <div style="background:var(--surface-2);border-radius:10px;padding:.75rem;">
                    <div style="font-size:1.25rem;font-weight:800;color:#10b981;">
                        {{ Auth::user()->categories()->count() }}
                    </div>
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:500;">Folders</div>
                </div>
            </div>
            <div style="margin-top:.75rem;background:var(--surface-2);border-radius:10px;padding:.75rem;text-align:left;">
                <div style="font-size:.72rem;color:var(--text-muted);font-weight:500;">Member Since</div>
                <div style="font-size:.85rem;font-weight:600;color:var(--text);">
                    {{ $user->created_at->format('F Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- ─── RIGHT: Settings Forms ─── --}}
    <div class="col-12 col-lg-8">
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Update Profile --}}
            <div class="card-surface p-4">
                <h2 style="font-size:.95rem;font-weight:700;margin-bottom:1.25rem;color:var(--text);">
                    <i class="bi bi-person-fill me-2" style="color:var(--primary);"></i>Profile Information
                </h2>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Full Name</label>
                            <input type="text" name="name" class="form-control-custom {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div style="font-size:.75rem;color:#ef4444;margin-top:.3rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Email Address</label>
                            <input type="email" name="email" class="form-control-custom {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div style="font-size:.75rem;color:#ef4444;margin-top:.3rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Profile Picture</label>
                            <input type="file" name="profile_image" class="form-control-custom {{ $errors->has('profile_image') ? 'is-invalid' : '' }}" accept="image/*">
                            <div style="font-size:.72rem;color:var(--text-muted);margin-top:.25rem;">Supported formats: JPEG, PNG, JPG, GIF (Max. 2MB)</div>
                            @error('profile_image')<div style="font-size:.75rem;color:#ef4444;margin-top:.3rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check2-circle"></i> Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="card-surface p-4">
                <h2 style="font-size:.95rem;font-weight:700;margin-bottom:1.25rem;color:var(--text);">
                    <i class="bi bi-shield-lock-fill me-2" style="color:#10b981;"></i>Change Password
                </h2>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Current Password</label>
                            <input type="password" name="current_password"
                                   class="form-control-custom {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                   placeholder="••••••••">
                            @error('current_password')<div style="font-size:.75rem;color:#ef4444;margin-top:.3rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">New Password</label>
                            <input type="password" name="password"
                                   class="form-control-custom {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Min. 8 characters">
                            @error('password')<div style="font-size:.75rem;color:#ef4444;margin-top:.3rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control-custom"
                                   placeholder="Repeat new password">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-primary-custom" style="background:#10b981;">
                                <i class="bi bi-key-fill"></i> Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Theme / Appearance --}}
            <div class="card-surface p-4">
                <h2 style="font-size:.95rem;font-weight:700;margin-bottom:1.25rem;color:var(--text);">
                    <i class="bi bi-palette-fill me-2" style="color:#f59e0b;"></i>Appearance
                </h2>
                <div style="display:flex;align-items:center;justify-content:space-between;
                            background:var(--surface-2);border-radius:12px;padding:1rem 1.25rem;">
                    <div>
                        <div style="font-size:.875rem;font-weight:600;color:var(--text);">Dark Mode</div>
                        <div style="font-size:.78rem;color:var(--text-muted);">Toggle between light and dark theme</div>
                    </div>
                    <button id="profileThemeToggle" onclick="
                        const cur = document.documentElement.getAttribute('data-theme');
                        const next = cur === 'dark' ? 'light' : 'dark';
                        document.documentElement.setAttribute('data-theme', next);
                        localStorage.setItem('snhTheme', next);
                        this.textContent = next === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
                    " class="btn-outline-custom" style="white-space:nowrap;">
                        🌙 Dark Mode
                    </button>
                </div>

                <div style="margin-top:1rem;background:var(--surface-2);border-radius:12px;padding:1rem 1.25rem;">
                    <div style="font-size:.875rem;font-weight:600;color:var(--text);margin-bottom:.5rem;">Keyboard Shortcuts</div>
                    <div style="display:grid;grid-template-columns:auto 1fr;gap:.35rem .75rem;font-size:.8rem;">
                        <kbd style="background:var(--border);padding:.15rem .45rem;border-radius:5px;font-family:monospace;">N</kbd>
                        <span style="color:var(--text-muted);">New Note</span>
                        <kbd style="background:var(--border);padding:.15rem .45rem;border-radius:5px;font-family:monospace;">Ctrl+/</kbd>
                        <span style="color:var(--text-muted);">Focus Search</span>
                        <kbd style="background:var(--border);padding:.15rem .45rem;border-radius:5px;font-family:monospace;">Ctrl+D</kbd>
                        <span style="color:var(--text-muted);">Toggle Dark Mode</span>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="card-surface p-4" style="border-color:rgba(239,68,68,.3);">
                <h2 style="font-size:.95rem;font-weight:700;margin-bottom:.75rem;color:#ef4444;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Danger Zone
                </h2>
                <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:1rem;">
                    Permanently delete your account and all your notes. This action cannot be undone.
                </p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline-custom" style="border-color:#ef4444;color:#ef4444;">
                        <i class="bi bi-box-arrow-right"></i> Log Out All Devices
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Set theme toggle button text on load
const cur = document.documentElement.getAttribute('data-theme');
const btn = document.getElementById('profileThemeToggle');
if (btn) btn.textContent = cur === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
</script>
@endpush
