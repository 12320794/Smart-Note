@extends('layouts.app')
@section('title', 'All Notes')

@section('content')

{{-- ─── PAGE HEADER ─── --}}
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-sticky-fill" style="color:var(--primary);"></i> All Notes</h1>
    <a href="{{ route('notes.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-lg"></i> New Note
    </a>
</div>

{{-- ─── FILTER BAR ─── --}}
<form method="GET" action="{{ route('notes.index') }}" class="card-surface p-3 mb-4">
    <div class="row g-2 align-items-end">
        {{-- Search --}}
        <div class="col-12 col-md-4">
            <label class="form-label-custom">Search</label>
            <div style="position:relative;">
                <i class="bi bi-search" style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:var(--text-muted);"></i>
                <input type="text" name="search" class="form-control-custom" style="padding-left:2.5rem;"
                       placeholder="Title, content…" value="{{ request('search') }}">
            </div>
        </div>

        {{-- Folder --}}
        <div class="col-6 col-md-2">
            <label class="form-label-custom">Folder</label>
            <select name="category" class="form-control-custom">
                <option value="">All Folders</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tag --}}
        <div class="col-6 col-md-2">
            <label class="form-label-custom">Tag</label>
            <select name="tag" class="form-control-custom">
                <option value="">All Tags</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Priority --}}
        <div class="col-6 col-md-2">
            <label class="form-label-custom">Priority</label>
            <select name="priority" class="form-control-custom">
                <option value="">All</option>
                <option value="high"   {{ request('priority') === 'high'   ? 'selected' : '' }}>🔴 High</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                <option value="low"    {{ request('priority') === 'low'    ? 'selected' : '' }}>🟢 Low</option>
            </select>
        </div>

        {{-- Buttons --}}
        <div class="col-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn-primary-custom flex-fill">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>
            <a href="{{ route('notes.index') }}" class="btn-outline-custom" style="padding:.55rem .8rem;">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </div>
</form>

{{-- ─── RESULTS COUNT ─── --}}
<div style="margin-bottom:1rem;font-size:.82rem;color:var(--text-muted);">
    Showing <strong style="color:var(--text);">{{ $notes->total() }}</strong> note(s)
    @if(request('search')) – results for "<em>{{ request('search') }}</em>" @endif
</div>

{{-- ─── NOTES GRID ─── --}}
@if($notes->isEmpty())
    <div class="empty-state card-surface">
        <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
        <h3 style="font-size:1.1rem;font-weight:600;margin-bottom:.5rem;">No notes found</h3>
        <p style="margin-bottom:1.25rem;font-size:.875rem;">
            @if(request()->hasAny(['search','category','tag','priority']))
                Try adjusting your filters.
            @else
                Create your first note to get started!
            @endif
        </p>
        <a href="{{ route('notes.create') }}" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Create Note
        </a>
    </div>
@else
    <div class="row g-3">
        @foreach($notes as $note)
            <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                @include('notes._card', ['note' => $note])
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $notes->links() }}
    </div>
@endif

@endsection

@push('styles')
<style>
/* Style Laravel pagination to match design */
.pagination { gap:.3rem; }
.pagination .page-link {
    border-radius: 8px !important;
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text);
    font-size: .82rem;
    font-weight: 500;
    padding: .4rem .8rem;
    transition: all .2s;
}
.pagination .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}
.pagination .page-link:hover {
    background: var(--primary-light);
    border-color: var(--primary);
    color: var(--primary);
}
</style>
@endpush
