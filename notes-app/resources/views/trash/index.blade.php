@extends('layouts.app')
@section('title', 'Trash Bin')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="bi bi-trash3-fill" style="color:#ef4444;"></i> Trash Bin</h1>
        <p style="color:var(--text-muted);font-size:.85rem;margin-top:.2rem;">
            Deleted notes are kept here. Restore or permanently delete them.
        </p>
    </div>
    @if($notes->total() > 0)
        <form method="POST" action="{{ route('trash.empty') }}"
              onsubmit="return confirm('Permanently delete ALL trashed notes? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" style="background:#ef4444;color:#fff;border:none;padding:.6rem 1.2rem;
                    border-radius:10px;font-weight:600;font-size:.875rem;cursor:pointer;
                    display:flex;align-items:center;gap:.4rem;transition:all .2s;"
                    onmouseover="this.style.background='#b91c1c'"
                    onmouseout="this.style.background='#ef4444'">
                <i class="bi bi-trash3-fill"></i> Empty Trash
            </button>
        </form>
    @endif
</div>

{{-- Info banner --}}
<div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);
            border-radius:12px;padding:.85rem 1.25rem;margin-bottom:1.5rem;
            display:flex;align-items:center;gap:.75rem;font-size:.82rem;color:#b91c1c;">
    <i class="bi bi-info-circle-fill" style="font-size:1rem;flex-shrink:0;"></i>
    <span>Notes in trash are <strong>soft-deleted</strong> and can be restored at any time.
    Permanently deleted notes cannot be recovered.</span>
</div>

@if($notes->isEmpty())
    <div class="empty-state card-surface">
        <div class="empty-icon"><i class="bi bi-trash3"></i></div>
        <h3 style="font-size:1.1rem;font-weight:600;margin-bottom:.5rem;">Trash is empty</h3>
        <p style="font-size:.875rem;">Deleted notes will appear here.</p>
    </div>
@else
    <div class="row g-3">
        @foreach($notes as $note)
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="card-surface p-4" style="opacity:.85;position:relative;">
                {{-- Deleted badge --}}
                <div style="position:absolute;top:.75rem;right:.75rem;background:#ef444422;
                            color:#ef4444;font-size:.65rem;font-weight:700;padding:.1rem .45rem;
                            border-radius:99px;text-transform:uppercase;">Deleted</div>

                <div style="font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:.35rem;
                            display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $note->title ?: 'Untitled Note' }}
                </div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:.75rem;
                            display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ strip_tags($note->content) ?: 'No content.' }}
                </div>
                <div style="font-size:.7rem;color:var(--text-muted);margin-bottom:1rem;">
                    <i class="bi bi-clock me-1"></i>Deleted {{ $note->deleted_at->diffForHumans() }}
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:.5rem;">
                    {{-- Restore --}}
                    <form method="POST" action="{{ route('trash.restore', $note->id) }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn-outline-custom w-100 justify-content-center" style="padding:.45rem;">
                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                        </button>
                    </form>

                    {{-- Permanent delete --}}
                    <form method="POST" action="{{ route('trash.force-delete', $note->id) }}"
                          onsubmit="return confirm('Permanently delete this note? This CANNOT be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn" style="color:#ef4444;border:1.5px solid #ef444444;" title="Delete Forever">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $notes->links() }}
    </div>
@endif

@endsection
