@extends('layouts.app')
@section('title', $note->title ?: 'View Note')

@section('content')

<div class="page-header">
    <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.35rem;flex-wrap:wrap;">
            {{-- Folder breadcrumb --}}
            @if($note->category)
                <a href="{{ route('notes.index', ['category' => $note->category_id]) }}"
                   style="font-size:.75rem;font-weight:600;color:{{ $note->category->color }};
                          background:{{ $note->category->color }}22;padding:.15rem .55rem;border-radius:99px;text-decoration:none;">
                    <i class="bi bi-folder-fill me-1"></i>{{ $note->category->name }}
                </a>
                <i class="bi bi-chevron-right" style="font-size:.65rem;color:var(--text-muted);"></i>
            @endif
            <span class="priority-badge priority-{{ $note->priority }}">{{ ucfirst($note->priority) }}</span>
            @if($note->is_pinned)
                <span style="font-size:.75rem;color:#f59e0b;font-weight:600;">📌 Pinned</span>
            @endif
        </div>
        <h1 class="page-title" style="font-size:1.6rem;">{{ $note->title ?: 'Untitled Note' }}</h1>
        <p style="color:var(--text-muted);font-size:.8rem;margin-top:.3rem;">
            <i class="bi bi-clock me-1"></i>Last updated {{ $note->updated_at->diffForHumans() }}
            &nbsp;·&nbsp;
            <i class="bi bi-calendar me-1"></i>Created {{ $note->created_at->format('M d, Y') }}
        </p>
    </div>

    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
        {{-- Pin toggle --}}
        <form method="POST" action="{{ route('notes.pin', $note) }}">
            @csrf
            <button type="submit" class="btn-outline-custom" style="{{ $note->is_pinned ? 'background:var(--warning);color:#fff;border-color:var(--warning);' : '' }}">
                <i class="bi bi-pin-angle-fill"></i>
                {{ $note->is_pinned ? 'Unpin' : 'Pin' }}
            </button>
        </form>

        {{-- Print / PDF --}}
        <button onclick="window.print()" class="btn-outline-custom">
            <i class="bi bi-printer-fill"></i> Print / PDF
        </button>

        {{-- Edit --}}
        <a href="{{ route('notes.edit', $note) }}" class="btn-primary-custom">
            <i class="bi bi-pencil-fill"></i> Edit
        </a>

        {{-- Delete --}}
        <form method="POST" action="{{ route('notes.destroy', $note) }}"
              onsubmit="return confirm('Move to Trash?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-outline-custom" style="border-color:#ef4444;color:#ef4444;">
                <i class="bi bi-trash3"></i>
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    {{-- ─── NOTE CONTENT ─── --}}
    <div class="col-12 col-lg-8">
        <div class="card-surface p-4 p-md-5" style="min-height:400px;background:{{ $note->color ?: 'var(--surface)' }};">
            <div class="note-content-body" style="line-height:1.8;font-size:.95rem;color:var(--text);">
                {!! $note->content ?: '<p style="color:var(--text-muted);font-style:italic;">This note has no content yet.</p>' !!}
            </div>
        </div>
    </div>

    {{-- ─── SIDEBAR META ─── --}}
    <div class="col-12 col-lg-4">
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Tags --}}
            <div class="card-surface p-4">
                <h3 style="font-size:.85rem;font-weight:700;color:var(--text-muted);margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em;">
                    <i class="bi bi-tags-fill me-1"></i> Tags
                </h3>
                @if($note->tags->isEmpty())
                    <p style="font-size:.8rem;color:var(--text-muted);">No tags assigned.</p>
                @else
                    <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                        @foreach($note->tags as $tag)
                            <a href="{{ route('notes.index', ['tag' => $tag->id]) }}" style="text-decoration:none;">
                                <span class="tag-badge" style="background:{{ $tag->color }};font-size:.78rem;padding:.2rem .65rem;">
                                    {{ $tag->name }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="card-surface p-4">
                <h3 style="font-size:.85rem;font-weight:700;color:var(--text-muted);margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em;">
                    <i class="bi bi-info-circle-fill me-1"></i> Details
                </h3>
                <dl style="font-size:.82rem;display:grid;grid-template-columns:auto 1fr;gap:.35rem .75rem;align-items:center;">
                    <dt style="color:var(--text-muted);font-weight:600;">Priority</dt>
                    <dd><span class="priority-badge priority-{{ $note->priority }}">{{ ucfirst($note->priority) }}</span></dd>

                    <dt style="color:var(--text-muted);font-weight:600;">Folder</dt>
                    <dd style="color:var(--text);">{{ $note->category?->name ?? '—' }}</dd>

                    <dt style="color:var(--text-muted);font-weight:600;">Pinned</dt>
                    <dd style="color:var(--text);">{{ $note->is_pinned ? '📌 Yes' : 'No' }}</dd>

                    <dt style="color:var(--text-muted);font-weight:600;">Created</dt>
                    <dd style="color:var(--text);">{{ $note->created_at->format('M d, Y h:i A') }}</dd>

                    <dt style="color:var(--text-muted);font-weight:600;">Updated</dt>
                    <dd style="color:var(--text);">{{ $note->updated_at->format('M d, Y h:i A') }}</dd>
                </dl>
            </div>

            {{-- Quick actions --}}
            <div class="card-surface p-4">
                <h3 style="font-size:.85rem;font-weight:700;color:var(--text-muted);margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em;">
                    <i class="bi bi-lightning-fill me-1"></i> Quick Actions
                </h3>
                <div style="display:flex;flex-direction:column;gap:.5rem;">
                    <a href="{{ route('notes.edit', $note) }}" class="btn-outline-custom w-100 justify-content-center">
                        <i class="bi bi-pencil-fill"></i> Edit Note
                    </a>
                    <button onclick="window.print()" class="btn-outline-custom w-100 justify-content-center">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Export as PDF
                    </button>
                    <a href="{{ route('notes.create') }}" class="btn-outline-custom w-100 justify-content-center">
                        <i class="bi bi-copy"></i> New Note
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Styles for CKEditor-generated content */
.note-content-body h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: .75rem; }
.note-content-body h2 { font-size: 1.35rem; font-weight: 700; margin-bottom: .6rem; }
.note-content-body h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: .5rem; }
.note-content-body p  { margin-bottom: .85rem; }
.note-content-body ul,.note-content-body ol { padding-left: 1.5rem; margin-bottom: .85rem; }
.note-content-body li { margin-bottom: .3rem; }
.note-content-body blockquote {
    border-left: 4px solid var(--primary);
    padding: .75rem 1rem;
    background: var(--surface-2);
    border-radius: 0 8px 8px 0;
    margin: 1rem 0;
    color: var(--text-muted);
}
.note-content-body code {
    background: var(--surface-2);
    padding: .15rem .4rem;
    border-radius: 5px;
    font-family: 'Courier New', monospace;
    font-size: .85em;
    color: var(--primary);
}
.note-content-body pre {
    background: #0f172a;
    color: #e2e8f0;
    padding: 1rem 1.25rem;
    border-radius: 10px;
    overflow-x: auto;
    margin: 1rem 0;
}

@media print {
    .sidebar, .topnav, .page-header .btn-outline-custom,
    .page-header .btn-primary-custom, form { display: none !important; }
    .main-wrap { margin-left: 0 !important; }
    .col-lg-4 { display: none; }
    .col-lg-8 { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; }
}
</style>
@endpush
