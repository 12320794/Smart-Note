@extends('layouts.app')
@section('title', 'Tags')

@section('content')

<div class="page-header">
    <h1 class="page-title"><i class="bi bi-tags-fill" style="color:var(--secondary);"></i> Tags</h1>
    <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createTagModal">
        <i class="bi bi-plus-lg"></i> New Tag
    </button>
</div>

@if($tags->isEmpty())
    <div class="empty-state card-surface">
        <div class="empty-icon"><i class="bi bi-tags"></i></div>
        <h3 style="font-size:1.1rem;font-weight:600;margin-bottom:.5rem;">No tags yet</h3>
        <p style="margin-bottom:1.25rem;font-size:.875rem;">Tags help you filter and find notes quickly.</p>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createTagModal">
            <i class="bi bi-plus-lg"></i> Create First Tag
        </button>
    </div>
@else
    <div class="row g-3">
        @foreach($tags as $tag)
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="card-surface p-4">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
                    <span class="tag-badge" style="background:{{ $tag->color }};font-size:.85rem;padding:.3rem .9rem;">
                        # {{ $tag->name }}
                    </span>
                    <div style="display:flex;gap:.4rem;">
                        <button class="icon-btn edit-tag-btn"
                                data-id="{{ $tag->id }}" data-name="{{ $tag->name }}" data-color="{{ $tag->color }}"
                                title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('tags.destroy', $tag) }}"
                              onsubmit="return confirm('Delete tag \'{{ $tag->name }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn" style="color:#ef4444;" title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <a href="{{ route('notes.index', ['tag' => $tag->id]) }}" style="text-decoration:none;">
                    <div style="font-size:.82rem;color:var(--text-muted);">
                        <i class="bi bi-sticky me-1"></i>
                        {{ $tag->notes_count }} {{ Str::plural('note', $tag->notes_count) }}
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- ─── CREATE TAG MODAL ─── --}}
<div class="modal fade" id="createTagModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border);background:var(--surface);">
            <div class="modal-header" style="border-color:var(--border);padding:1.25rem 1.5rem;">
                <h5 class="modal-title" style="font-weight:700;color:var(--text);">
                    <i class="bi bi-tag-fill me-2" style="color:var(--primary);"></i>Create New Tag
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('tags.store') }}">
                @csrf
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="mb-3">
                        <label class="form-label-custom">Tag Name</label>
                        <input type="text" name="name" class="form-control-custom"
                               placeholder="e.g. urgent, reference, draft…" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Tag Color</label>
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                            @foreach(['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444','#f97316','#06b6d4','#84cc16'] as $c)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="color" value="{{ $c }}" style="display:none;"
                                           {{ $c === '#6366f1' ? 'checked' : '' }} class="tag-color-radio">
                                    <span style="display:block;width:30px;height:30px;border-radius:50%;background:{{ $c }};
                                                 border:3px solid transparent;transition:all .2s;" class="tag-color-swatch"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color:var(--border);padding:1rem 1.5rem;gap:.5rem;">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check2"></i> Create Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ─── EDIT TAG MODAL ─── --}}
<div class="modal fade" id="editTagModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border);background:var(--surface);">
            <div class="modal-header" style="border-color:var(--border);padding:1.25rem 1.5rem;">
                <h5 class="modal-title" style="font-weight:700;color:var(--text);">
                    <i class="bi bi-pencil-fill me-2" style="color:var(--primary);"></i>Edit Tag
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editTagForm" action="">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="mb-3">
                        <label class="form-label-custom">Tag Name</label>
                        <input type="text" name="name" id="editTagName" class="form-control-custom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Tag Color</label>
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                            @foreach(['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444','#f97316','#06b6d4','#84cc16'] as $c)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="color" value="{{ $c }}" style="display:none;" class="edit-tag-color-radio">
                                    <span style="display:block;width:30px;height:30px;border-radius:50%;background:{{ $c }};
                                                 border:3px solid transparent;transition:all .2s;" class="edit-tag-color-swatch"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color:var(--border);padding:1rem 1.5rem;gap:.5rem;">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.edit-tag-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('editTagName').value = btn.dataset.name;
        document.getElementById('editTagForm').action = `/tags/${btn.dataset.id}`;
        document.querySelectorAll('.edit-tag-color-radio').forEach(r => {
            r.nextElementSibling.style.border = r.value === btn.dataset.color
                ? '3px solid #1e293b' : '3px solid transparent';
            if (r.value === btn.dataset.color) r.checked = true;
        });
        new bootstrap.Modal(document.getElementById('editTagModal')).show();
    });
});

// Swatch init
[['tag-color-radio','tag-color-swatch'],['edit-tag-color-radio','edit-tag-color-swatch']].forEach(([rc,sc]) => {
    document.querySelectorAll('.'+rc).forEach(r => {
        r.addEventListener('change', function() {
            document.querySelectorAll('.'+sc).forEach(s => s.style.border='3px solid transparent');
            this.nextElementSibling.style.border='3px solid #1e293b';
        });
        if (r.checked) r.nextElementSibling.style.border='3px solid #1e293b';
    });
});
</script>
@endpush
