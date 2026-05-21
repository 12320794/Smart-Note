{{-- Reusable Note Card Partial --}}
<div class="note-card" style="background:{{ $note->color ?? '#ffffff' }};"
     onclick="window.location='{{ route('notes.show', $note) }}'">

    {{-- Pin indicator --}}
    @if($note->is_pinned)
        <span class="note-pin"><i class="bi bi-pin-angle-fill"></i></span>
    @endif

    {{-- Folder badge --}}
    @if($note->category)
        <div style="margin-bottom:.4rem;">
            <span style="font-size:.65rem;font-weight:600;color:{{ $note->category->color }};
                         background:{{ $note->category->color }}22;padding:.1rem .5rem;border-radius:99px;">
                <i class="bi bi-folder-fill me-1"></i>{{ $note->category->name }}
            </span>
        </div>
    @endif

    {{-- Title --}}
    <div class="note-title">{{ $note->title ?: 'Untitled Note' }}</div>

    {{-- Content preview (strip HTML tags) --}}
    <div class="note-preview">{{ strip_tags($note->content) }}</div>

    {{-- Meta row --}}
    <div class="note-meta">
        {{-- Priority badge --}}
        <span class="priority-badge priority-{{ $note->priority }}">
            {{ ucfirst($note->priority) }}
        </span>

        {{-- Tags --}}
        @foreach($note->tags->take(2) as $tag)
            <span class="tag-badge" style="background:{{ $tag->color }};">
                {{ $tag->name }}
            </span>
        @endforeach
        @if($note->tags->count() > 2)
            <span style="font-size:.62rem;color:var(--text-muted);">+{{ $note->tags->count() - 2 }}</span>
        @endif

        {{-- Date --}}
        <span class="note-date">{{ $note->updated_at->diffForHumans() }}</span>
    </div>
</div>
