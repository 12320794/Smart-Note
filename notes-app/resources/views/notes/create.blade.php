@extends('layouts.app')
@section('title', 'Create Note')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="bi bi-plus-circle-fill" style="color:var(--primary);"></i> Create New Note</h1>
        <p style="color:var(--text-muted);font-size:.85rem;margin-top:.2rem;">Capture your ideas with rich formatting.</p>
    </div>
    <a href="{{ route('notes.index') }}" class="btn-outline-custom">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('notes.store') }}" id="noteForm">
    @csrf

    <div class="row g-4">
        {{-- ─── LEFT COLUMN: Editor ─── --}}
        <div class="col-12 col-lg-8">
            <div class="card-surface p-4">

                {{-- Title --}}
                <div class="mb-3">
                    <label class="form-label-custom">Note Title</label>
                    <input type="text" name="title" class="form-control-custom"
                           style="font-size:1.1rem;font-weight:600;"
                           placeholder="Give your note a title…" value="{{ old('title') }}">
                </div>

                {{-- Rich Text Editor --}}
                <div class="mb-2">
                    <label class="form-label-custom">Content</label>
                    <textarea name="content" id="editor">{{ old('content') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ─── RIGHT COLUMN: Options ─── --}}
        <div class="col-12 col-lg-4">
            <div style="display:flex;flex-direction:column;gap:1rem;">

                {{-- Note Options Card --}}
                <div class="card-surface p-4">
                    <h2 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;color:var(--text);">
                        <i class="bi bi-sliders me-2" style="color:var(--primary);"></i>Note Options
                    </h2>

                    {{-- Priority --}}
                    <div class="mb-3">
                        <label class="form-label-custom">Priority Level</label>
                        <select name="priority" class="form-control-custom">
                            <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>🟢 Low</option>
                            <option value="medium" {{ old('priority','medium') === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>🔴 High</option>
                        </select>
                    </div>

                    {{-- Folder --}}
                    <div class="mb-3">
                        <label class="form-label-custom">Folder</label>
                        <select name="category_id" class="form-control-custom">
                            <option value="">No Folder</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Card Color --}}
                    <div class="mb-3">
                        <label class="form-label-custom">Card Color</label>
                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                            @foreach(['#ffffff','#fef9c3','#dcfce7','#dbeafe','#fce7f3','#ede9fe','#fee2e2','#ffedd5'] as $clr)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="color" value="{{ $clr }}" style="display:none;"
                                           {{ old('color','#ffffff') === $clr ? 'checked' : '' }} class="color-radio">
                                    <span style="display:block;width:28px;height:28px;border-radius:8px;
                                                 background:{{ $clr }};border:2px solid var(--border);
                                                 transition:all .2s;" class="color-swatch"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pin --}}
                    <div class="mb-3">
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                            <input type="checkbox" name="is_pinned" value="1" style="accent-color:var(--primary);width:16px;height:16px;"
                                   {{ old('is_pinned') ? 'checked' : '' }}>
                            <span style="font-size:.85rem;font-weight:500;color:var(--text);">
                                📌 Pin this note
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Tags Card --}}
                <div class="card-surface p-4">
                    <h2 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;color:var(--text);">
                        <i class="bi bi-tags-fill me-2" style="color:var(--primary);"></i>Tags
                    </h2>

                    {{-- Tag input --}}
                    <div class="mb-2">
                        <label class="form-label-custom">Add Tags (press Enter)</label>
                        <input type="text" id="tagInput" class="form-control-custom"
                               placeholder="e.g. work, ideas, important">
                    </div>

                    {{-- Tag display area --}}
                    <div id="tagList" style="display:flex;flex-wrap:wrap;gap:.4rem;min-height:2rem;"></div>
                    <div id="tagInputsHidden"></div>

                    {{-- Existing tags as suggestions --}}
                    @if($tags->count())
                    <div style="margin-top:.75rem;">
                        <div style="font-size:.7rem;color:var(--text-muted);margin-bottom:.4rem;">SUGGESTIONS</div>
                        <div style="display:flex;flex-wrap:wrap;gap:.35rem;">
                            @foreach($tags->take(12) as $tag)
                                <button type="button" class="tag-suggestion"
                                        data-name="{{ $tag->name }}" data-color="{{ $tag->color }}"
                                        style="font-size:.7rem;padding:.15rem .5rem;border-radius:99px;
                                               background:{{ $tag->color }}22;color:{{ $tag->color }};
                                               border:1px solid {{ $tag->color }}55;cursor:pointer;
                                               font-weight:600;transition:all .2s;">
                                    {{ $tag->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Save Button --}}
                <button type="submit" class="btn-primary-custom w-100 justify-content-center" style="padding:.8rem;">
                    <i class="bi bi-check2-circle me-2"></i> Save Note
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css">
@endpush

@push('scripts')
<script type="importmap">
{
    "imports": {
        "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.js",
        "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.1/"
    }
}
</script>
<script type="module">
import { ClassicEditor, Essentials, Bold, Italic, Underline, Strikethrough,
         Heading, Paragraph, List, Link, BlockQuote, Code, CodeBlock,
         FontColor, FontBackgroundColor, FontSize, Alignment,
         Indent, IndentBlock, Undo, Image, ImageUpload } from 'ckeditor5';

ClassicEditor.create(document.querySelector('#editor'), {
    plugins: [Essentials, Bold, Italic, Underline, Strikethrough,
              Heading, Paragraph, List, Link, BlockQuote, Code, CodeBlock,
              FontColor, FontBackgroundColor, FontSize, Alignment,
              Indent, IndentBlock, Undo],
    toolbar: {
        items: ['heading','|','bold','italic','underline','strikethrough','|',
                'fontColor','fontBackgroundColor','fontSize','|',
                'alignment','|','numberedList','bulletedList','|',
                'indent','outdent','|','link','blockQuote','code','codeBlock','|','undo','redo']
    }
}).catch(console.error);
</script>

<script>
/* ── Tag management ── */
const tagInput  = document.getElementById('tagInput');
const tagList   = document.getElementById('tagList');
const hiddenDiv = document.getElementById('tagInputsHidden');
const addedTags = new Set();

function addTag(name, color = '#6366f1') {
    name = name.trim();
    if (!name || addedTags.has(name)) return;
    addedTags.add(name);

    const pill = document.createElement('span');
    pill.style.cssText = `background:${color};color:#fff;font-size:.72rem;
        font-weight:600;padding:.2rem .55rem;border-radius:99px;
        display:inline-flex;align-items:center;gap:.3rem;cursor:pointer;`;
    pill.innerHTML = `${name} <i class="bi bi-x" style="font-size:.7rem;"></i>`;
    pill.onclick = () => { addedTags.delete(name); pill.remove();
        hiddenDiv.querySelector(`[data-tag="${name}"]`)?.remove(); };
    tagList.appendChild(pill);

    const hidden = document.createElement('input');
    hidden.type = 'hidden'; hidden.name = 'tags[]';
    hidden.value = name; hidden.setAttribute('data-tag', name);
    hiddenDiv.appendChild(hidden);
}

tagInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        addTag(tagInput.value);
        tagInput.value = '';
    }
});

document.querySelectorAll('.tag-suggestion').forEach(btn => {
    btn.addEventListener('click', () => addTag(btn.dataset.name, btn.dataset.color));
});

/* ── Color swatch selection ── */
document.querySelectorAll('.color-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.color-swatch').forEach(s => {
            s.style.border = '2px solid var(--border)';
            s.style.transform = 'scale(1)';
        });
        if (this.checked) {
            this.nextElementSibling.style.border = '2px solid var(--primary)';
            this.nextElementSibling.style.transform = 'scale(1.2)';
        }
    });
    if (radio.checked) {
        radio.nextElementSibling.style.border = '2px solid var(--primary)';
        radio.nextElementSibling.style.transform = 'scale(1.2)';
    }
});
</script>
@endpush
