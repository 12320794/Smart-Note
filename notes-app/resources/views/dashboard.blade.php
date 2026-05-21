@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- ─── PAGE HEADER & USER PROFILE CARD ─── --}}
<div class="row g-3 mb-4">
    {{-- Welcome & Profile Info --}}
    <div class="col-12 col-xl-8">
        <div class="card-surface p-4 h-100 d-flex align-items-center gap-3 gap-md-4 flex-wrap flex-md-nowrap" style="background: linear-gradient(135deg, rgba(99,102,241,0.04) 0%, rgba(139,92,246,0.04) 100%);">
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary); box-shadow: 0 8px 20px rgba(99,102,241,0.25);" alt="Avatar">
            @else
                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: #fff; box-shadow: 0 8px 20px rgba(99,102,241,0.25); flex-shrink: 0;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h1 style="font-size: 1.35rem; font-weight: 850; color: var(--text); margin-bottom: .2rem; display: flex; align-items: center; gap: .5rem;">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h1>
                <p style="font-size: .82rem; color: var(--text-muted); margin-bottom: .75rem;">
                    {{ Auth::user()->email }} • Member since {{ Auth::user()->created_at->format('F Y') }}
                </p>
                <div style="display: flex; gap: .5rem;">
                    <a href="{{ route('profile.index') }}" class="btn-outline-custom" style="padding: .35rem .85rem; font-size: .75rem;">
                        <i class="bi bi-person-fill-gear"></i> Edit Profile Details
                    </a>
                    <a href="{{ route('notes.create') }}" class="btn-primary-custom" style="padding: .35rem .85rem; font-size: .75rem; text-decoration: none;">
                        <i class="bi bi-plus-lg"></i> New Note
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Quick Profile Image Uploader --}}
    <div class="col-12 col-xl-4">
        <div class="card-surface p-4 h-100 d-flex flex-column justify-content-center">
            <h3 style="font-size: .88rem; font-weight: 700; color: var(--text); margin-bottom: .6rem; display: flex; align-items: center; gap: .4rem;">
                <i class="bi bi-camera-fill" style="color: var(--primary);"></i> Quick Profile Pic Upload
            </h3>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                {{-- Maintain current name/email so we don't clear them on file upload validation --}}
                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                
                <div style="display: flex; gap: .5rem; align-items: center;">
                    <input type="file" name="profile_image" class="form-control-custom {{ $errors->has('profile_image') ? 'is-invalid' : '' }}" accept="image/*" style="font-size: .75rem; padding: .45rem .75rem;" required>
                    <button type="submit" class="btn-primary-custom" style="padding: .45rem 1rem; font-size: .75rem; white-space: nowrap;">
                        Upload
                    </button>
                </div>
                @error('profile_image')
                    <div style="font-size:.7rem;color:#ef4444;margin-top:.3rem;">{{ $message }}</div>
                @enderror
            </form>
        </div>
    </div>
</div>

{{-- ─── STAT CARDS ─── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(99,102,241,.12);color:var(--primary);">
                <i class="bi bi-sticky-fill"></i>
            </div>
            <div>
                <div class="stat-val">{{ $stats['total'] }}</div>
                <div class="stat-lbl">Total Notes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(245,158,11,.12);color:#f59e0b;">
                <i class="bi bi-pin-angle-fill"></i>
            </div>
            <div>
                <div class="stat-val">{{ $stats['pinned'] }}</div>
                <div class="stat-lbl">Pinned</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,.12);color:#ef4444;">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div>
                <div class="stat-val">{{ $stats['high'] }}</div>
                <div class="stat-lbl">High Priority</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,.12);color:#10b981;">
                <i class="bi bi-folder2-open"></i>
            </div>
            <div>
                <div class="stat-val">{{ $stats['folders'] }}</div>
                <div class="stat-lbl">Folders</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(100,116,139,.12);color:#64748b;">
                <i class="bi bi-trash3-fill"></i>
            </div>
            <div>
                <div class="stat-val">{{ $stats['trashed'] }}</div>
                <div class="stat-lbl">In Trash</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ─── PINNED NOTES ─── --}}
    @if($pinnedNotes->count())
    <div class="col-12">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <h2 style="font-size:1rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:.4rem;">
                <i class="bi bi-pin-angle-fill" style="color:#f59e0b;"></i> Pinned Notes
            </h2>
        </div>
        <div class="row g-3">
            @foreach($pinnedNotes as $note)
                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                    @include('notes._card', ['note' => $note])
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ─── RECENT NOTES ─── --}}
    <div class="col-12 col-xl-8">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <h2 style="font-size:1rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:.4rem;">
                <i class="bi bi-clock-history" style="color:var(--primary);"></i> Recent Notes
            </h2>
            <a href="{{ route('notes.index') }}" style="font-size:.8rem;color:var(--primary);font-weight:600;">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if($recentNotes->isEmpty())
            <div class="empty-state card-surface" style="padding:3rem;">
                <div class="empty-icon"><i class="bi bi-journal-plus"></i></div>
                <h3 style="font-size:1.1rem;font-weight:600;margin-bottom:.5rem;">No notes yet</h3>
                <p style="margin-bottom:1.25rem;font-size:.875rem;">Start capturing your ideas!</p>
                <a href="{{ route('notes.create') }}" class="btn-primary-custom">
                    <i class="bi bi-plus-lg"></i> Create First Note
                </a>
            </div>
        @else
            <div class="row g-3">
                @foreach($recentNotes as $note)
                    <div class="col-12 col-sm-6">
                        @include('notes._card', ['note' => $note])
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ─── FOLDERS PANEL ─── --}}
    <div class="col-12 col-xl-4">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <h2 style="font-size:1rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:.4rem;">
                <i class="bi bi-folder2-open" style="color:#10b981;"></i> My Folders
            </h2>
            <a href="{{ route('categories.index') }}" style="font-size:.8rem;color:var(--primary);font-weight:600;">
                Manage <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if($categories->isEmpty())
            <div class="card-surface" style="padding:1.5rem;text-align:center;">
                <i class="bi bi-folder-plus" style="font-size:2rem;color:var(--text-muted);opacity:.4;"></i>
                <p style="margin-top:.5rem;font-size:.82rem;color:var(--text-muted);">No folders yet.</p>
                <a href="{{ route('categories.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:.4rem .9rem;margin-top:.5rem;">
                    Create Folder
                </a>
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:.6rem;">
                @foreach($categories as $cat)
                    <a href="{{ route('notes.index', ['category' => $cat->id]) }}" style="text-decoration:none;">
                        <div class="folder-card">
                            <div class="folder-icon" style="background:{{ $cat->color }};">
                                <i class="bi bi-{{ $cat->icon }}"></i>
                            </div>
                            <div>
                                <div style="font-size:.875rem;font-weight:600;color:var(--text);">{{ $cat->name }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">{{ $cat->notes_count }} notes</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted);font-size:.8rem;"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
