document.addEventListener('DOMContentLoaded', () => {
    // Initialize Quill editor
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Write your thoughts here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'blockquote', 'code-block'],
                ['clean']
            ]
        }
    });

    // App State
    let notes = [];
    let activeNoteId = null;
    let currentTagFilter = 'all';
    let currentTags = []; // For the active note

    // DOM Elements
    const notesListEl = document.getElementById('notes-list');
    const tagsFilterListEl = document.getElementById('tags-filter-list');
    const searchInput = document.getElementById('search-input');
    const newNoteBtn = document.getElementById('new-note-btn');

    const editorContainerWrapper = document.getElementById('editor-container-wrapper');
    const emptyState = document.getElementById('empty-state');

    const noteTitleInput = document.getElementById('note-title');
    const noteDateDisplay = document.getElementById('note-date');
    const tagInput = document.getElementById('tag-input');
    const noteTagsDisplay = document.getElementById('note-tags-display');
    const saveNoteBtn = document.getElementById('save-note-btn');
    const deleteNoteBtn = document.getElementById('delete-note-btn');

    // Fetch notes
    const fetchNotes = async () => {
        try {
            const response = await fetch('/api/notes');
            notes = await response.json();
            renderNotes();
            renderTagsFilter();
        } catch (error) {
            console.error('Error fetching notes:', error);
        }
    };

    // Render Notes List
    const renderNotes = (searchTerm = '') => {
        notesListEl.innerHTML = '';
        
        let filteredNotes = notes.filter(note => {
            const matchesSearch = note.title.toLowerCase().includes(searchTerm.toLowerCase()) || 
                                  note.content.toLowerCase().includes(searchTerm.toLowerCase());
            const matchesTag = currentTagFilter === 'all' || 
                               note.tags.some(tag => tag.name === currentTagFilter);
            return matchesSearch && matchesTag;
        });

        if (filteredNotes.length === 0) {
            notesListEl.innerHTML = '<div class="note-preview" style="text-align:center; padding: 1rem;">No notes found.</div>';
            return;
        }

        filteredNotes.forEach(note => {
            const noteEl = document.createElement('div');
            noteEl.className = `note-item ${note.id === activeNoteId ? 'active' : ''}`;
            
            const date = new Date(note.updated_at).toLocaleDateString();
            const previewText = note.content.replace(/<[^>]+>/g, '').substring(0, 50) + '...';
            
            noteEl.innerHTML = `
                <div class="note-title">${note.title || 'Untitled'}</div>
                <div class="note-preview">${previewText}</div>
                <div class="note-meta">
                    <span class="note-date-small">${date}</span>
                </div>
            `;
            
            noteEl.addEventListener('click', () => openNote(note.id));
            notesListEl.appendChild(noteEl);
        });
    };

    // Render Tags Filter List
    const renderTagsFilter = () => {
        const allTags = new Set();
        notes.forEach(note => {
            note.tags.forEach(tag => allTags.add(tag.name));
        });

        tagsFilterListEl.innerHTML = `<button class="tag-btn ${currentTagFilter === 'all' ? 'active' : ''}" data-tag="all">All Notes</button>`;
        
        allTags.forEach(tag => {
            const btn = document.createElement('button');
            btn.className = `tag-btn ${currentTagFilter === tag ? 'active' : ''}`;
            btn.dataset.tag = tag;
            btn.textContent = tag;
            btn.addEventListener('click', () => {
                currentTagFilter = tag;
                renderTagsFilter();
                renderNotes(searchInput.value);
            });
            tagsFilterListEl.appendChild(btn);
        });
        
        // Re-attach listener to 'all' button
        tagsFilterListEl.querySelector('[data-tag="all"]').addEventListener('click', () => {
            currentTagFilter = 'all';
            renderTagsFilter();
            renderNotes(searchInput.value);
        });
    };

    // Open Note
    const openNote = (id) => {
        activeNoteId = id;
        const note = notes.find(n => n.id === id);
        
        if (note) {
            noteTitleInput.value = note.title;
            quill.root.innerHTML = note.content;
            currentTags = note.tags.map(t => t.name);
            noteDateDisplay.textContent = 'Last edited: ' + new Date(note.updated_at).toLocaleString();
            
            renderCurrentTags();
            
            emptyState.classList.add('hidden');
            editorContainerWrapper.classList.remove('hidden');
            
            renderNotes(searchInput.value); // to update active state
        }
    };

    // New Note
    const createNewNote = () => {
        activeNoteId = null;
        noteTitleInput.value = '';
        quill.root.innerHTML = '';
        currentTags = [];
        noteDateDisplay.textContent = 'Unsaved Note';
        
        renderCurrentTags();
        
        emptyState.classList.add('hidden');
        editorContainerWrapper.classList.remove('hidden');
        
        renderNotes(searchInput.value); // to update active state
        noteTitleInput.focus();
    };

    // Render Current Tags for active note
    const renderCurrentTags = () => {
        noteTagsDisplay.innerHTML = '';
        currentTags.forEach(tag => {
            const tagEl = document.createElement('span');
            tagEl.className = 'tag-chip';
            tagEl.innerHTML = `${tag} <span class="remove-tag" data-tag="${tag}">&times;</span>`;
            noteTagsDisplay.appendChild(tagEl);
        });

        // Add listeners to remove buttons
        document.querySelectorAll('.remove-tag').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tagToRemove = e.target.dataset.tag;
                currentTags = currentTags.filter(t => t !== tagToRemove);
                renderCurrentTags();
            });
        });
    };

    // Tag Input Handling
    tagInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = tagInput.value.trim();
            if (val && !currentTags.includes(val)) {
                currentTags.push(val);
                renderCurrentTags();
            }
            tagInput.value = '';
        }
    });

    // Save Note
    const saveNote = async () => {
        const title = noteTitleInput.value;
        const content = quill.root.innerHTML;
        
        const noteData = {
            title,
            content,
            tags: currentTags
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const method = activeNoteId ? 'PUT' : 'POST';
        const url = activeNoteId ? `/api/notes/${activeNoteId}` : '/api/notes';

        saveNoteBtn.textContent = 'Saving...';
        
        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(noteData)
            });
            
            const savedNote = await response.json();
            
            if (activeNoteId) {
                const index = notes.findIndex(n => n.id === activeNoteId);
                notes[index] = savedNote;
            } else {
                notes.unshift(savedNote);
                activeNoteId = savedNote.id;
            }
            
            noteDateDisplay.textContent = 'Last edited: ' + new Date(savedNote.updated_at).toLocaleString();
            renderNotes(searchInput.value);
            renderTagsFilter();
            
        } catch (error) {
            console.error('Error saving note:', error);
            alert('Failed to save note');
        } finally {
            saveNoteBtn.textContent = 'Save';
        }
    };

    // Delete Note
    const deleteNote = async () => {
        if (!activeNoteId) return;
        
        if (confirm('Are you sure you want to delete this note?')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                await fetch(`/api/notes/${activeNoteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                notes = notes.filter(n => n.id !== activeNoteId);
                
                activeNoteId = null;
                emptyState.classList.remove('hidden');
                editorContainerWrapper.classList.add('hidden');
                
                renderNotes(searchInput.value);
                renderTagsFilter();
            } catch (error) {
                console.error('Error deleting note:', error);
            }
        }
    };

    // Event Listeners
    if(newNoteBtn) newNoteBtn.addEventListener('click', createNewNote);
    if(saveNoteBtn) saveNoteBtn.addEventListener('click', saveNote);
    if(deleteNoteBtn) deleteNoteBtn.addEventListener('click', deleteNote);
    if(searchInput) searchInput.addEventListener('input', (e) => renderNotes(e.target.value));

    // Initial Load
    fetchNotes();
});
