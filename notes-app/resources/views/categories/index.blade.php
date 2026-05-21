@extends('layouts.app')
@section('title', 'Folders')

@section('content')

<div class="page-header">
    <h1 class="page-title"><i class="bi bi-folder2-open" style="color:#10b981;"></i> My Folders</h1>
    <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createFolderModal">
        <i class="bi bi-plus-lg"></i> New Folder
    </button>
</div>

@if($categories->isEmpty())
    <div class="empty-state card-surface">
        <div class="empty-icon"><i class="bi bi-folder-plus"></i></div>
        <h3 style="font-size:1.1rem;font-weight:600;margin-bottom:.5rem;">No folders yet</h3>
        <p style="margin-bottom:1.25rem;font-size:.875rem;">Organize your notes with folders.</p>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createFolderModal">
            <i class="bi bi-plus-lg"></i> Create First Folder
        </button>
    </div>
@else
    <div class="row g-3">
        @foreach($categories as $cat)
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="card-surface p-4">
                {{-- Folder Icon & Color --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                    <div class="folder-icon" style="background:{{ $cat->color }};width:52px;height:52px;font-size:1.5rem;">
                        <i class="bi bi-{{ $cat->icon }}"></i>
                    </div>
                    <div style="display:flex;gap:.4rem;">
                        <button class="icon-btn edit-folder-btn"
                                data-id="{{ $cat->id }}" data-name="{{ $cat->name }}"
                                data-color="{{ $cat->color }}" data-icon="{{ $cat->icon }}"
                                title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                              onsubmit="return confirm('Delete this folder? Notes will become uncategorized.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn" style="color:#ef4444;" title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <a href="{{ route('notes.index', ['category' => $cat->id]) }}" style="text-decoration:none;">
                    <div style="font-size:1rem;font-weight:700;color:var(--text);margin-bottom:.25rem;">
                        {{ $cat->name }}
                    </div>
                    <div style="font-size:.8rem;color:var(--text-muted);">
                        {{ $cat->notes_count }} {{ Str::plural('note', $cat->notes_count) }}
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- ─── CREATE FOLDER MODAL ─── --}}
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border);background:var(--surface);">
            <div class="modal-header" style="border-color:var(--border);padding:1.25rem 1.5rem;">
                <h5 class="modal-title" style="font-weight:700;color:var(--text);">
                    <i class="bi bi-folder-plus me-2" style="color:var(--primary);"></i>Create New Folder
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="mb-3">
                        <label class="form-label-custom">Folder Name</label>
                        <input type="text" name="name" class="form-control-custom"
                               placeholder="e.g. Work, Personal, Ideas…" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Folder Color</label>
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;" id="createColorSwatches">
                            @foreach(['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444','#f97316'] as $c)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="color" value="{{ $c }}" style="display:none;"
                                           {{ $c === '#6366f1' ? 'checked' : '' }} class="folder-color-radio">
                                    <span style="display:block;width:32px;height:32px;border-radius:50%;background:{{ $c }};
                                                 border:3px solid transparent;transition:all .2s;" class="folder-color-swatch"></span>
                                </label>
                            @endforeach
                        </div>
                        <input type="hidden" name="color" id="selectedColor" value="#6366f1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Icon</label>
                        <select name="icon" class="form-control-custom">
                            <option value="folder">📁 Folder</option>
                            <option value="folder2">📂 Open Folder</option>
                            <option value="briefcase">💼 Briefcase</option>
                            <option value="book">📖 Book</option>
                            <option value="heart">❤️ Heart</option>
                            <option value="star">⭐ Star</option>
                            <option value="lightning">⚡ Lightning</option>
                            <option value="gear">⚙️ Gear</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-color:var(--border);padding:1rem 1.5rem;gap:.5rem;">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check2"></i> Create Folder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ─── EDIT FOLDER MODAL ─── --}}
<div class="modal fade" id="editFolderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border);background:var(--surface);">
            <div class="modal-header" style="border-color:var(--border);padding:1.25rem 1.5rem;">
                <h5 class="modal-title" style="font-weight:700;color:var(--text);">
                    <i class="bi bi-pencil-fill me-2" style="color:var(--primary);"></i>Edit Folder
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editFolderForm" action="">
                @csrf @method('PUT')
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="mb-3">
                        <label class="form-label-custom">Folder Name</label>
                        <input type="text" name="name" id="editFolderName" class="form-control-custom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Folder Color</label>
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                            @foreach(['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444','#f97316'] as $c)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="color" value="{{ $c }}" style="display:none;" class="edit-color-radio">
                                    <span style="display:block;width:32px;height:32px;border-radius:50%;background:{{ $c }};
                                                 border:3px solid transparent;transition:all .2s;" class="edit-color-swatch"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Icon</label>
                        <select name="icon" id="editFolderIcon" class="form-control-custom">
                            <option value="folder">📁 Folder</option>
                            <option value="folder2">📂 Open Folder</option>
                            <option value="briefcase">💼 Briefcase</option>
                            <option value="book">📖 Book</option>
                            <option value="heart">❤️ Heart</option>
                            <option value="star">⭐ Star</option>
                            <option value="lightning">⚡ Lightning</option>
                            <option value="gear">⚙️ Gear</option>
                        </select>
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
// Edit folder modal population
document.querySelectorAll('.edit-folder-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id    = btn.dataset.id;
        const name  = btn.dataset.name;
        const color = btn.dataset.color;
        const icon  = btn.dataset.icon;

        document.getElementById('editFolderName').value = name;
        document.getElementById('editFolderIcon').value = icon;
        document.getElementById('editFolderForm').action = `/folders/${id}`;

        // Set color radio
        document.querySelectorAll('.edit-color-radio').forEach(r => {
            const swatch = r.nextElementSibling;
            if (r.value === color) {
                r.checked = true;
                swatch.style.border = '3px solid #1e293b';
            } else {
                r.checked = false;
                swatch.style.border = '3px solid transparent';
            }
        });

        new bootstrap.Modal(document.getElementById('editFolderModal')).show();
    });
});

// Color swatch click handlers
function initSwatches(radioClass, swatchClass) {
    document.querySelectorAll('.' + radioClass).forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.' + swatchClass).forEach(s => s.style.border = '3px solid transparent');
            this.nextElementSibling.style.border = '3px solid #1e293b';
        });
        if (radio.checked) radio.nextElementSibling.style.border = '3px solid #1e293b';
    });
}
initSwatches('folder-color-radio', 'folder-color-swatch');
initSwatches('edit-color-radio', 'edit-color-swatch');
</script>
@endpush
