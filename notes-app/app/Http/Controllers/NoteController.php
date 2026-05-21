<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of notes (dashboard-like all notes).
     */
    public function index(Request $request)
    {
        $query = Note::with(['tags', 'category'])
            ->forUser(Auth::id())
            ->pinnedFirst();

        // Search by term (title or content)
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by folder/category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('tags.id', $request->tag));
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        $notes      = $query->paginate(12)->withQueryString();
        $categories = Category::where('user_id', Auth::id())->get();
        $tags       = Tag::all();

        return view('notes.index', compact('notes', 'categories', 'tags'));
    }

    /**
     * Show the form for creating a new note.
     */
    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        $tags       = Tag::all();
        return view('notes.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created note.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'priority'    => 'in:low,medium,high',
            'is_pinned'   => 'boolean',
            'color'       => 'nullable|string|max:7',
            'tags'        => 'array',
            'tags.*'      => 'string|max:50',
        ]);

        $note = Note::create([
            'user_id'     => Auth::id(),
            'category_id' => $validated['category_id'] ?? null,
            'title'       => $validated['title'] ?? 'Untitled Note',
            'content'     => $validated['content'] ?? '',
            'priority'    => $validated['priority'] ?? 'medium',
            'is_pinned'   => $request->boolean('is_pinned'),
            'color'       => $validated['color'] ?? '#ffffff',
        ]);

        // Sync tags (create if not exists)
        if (!empty($validated['tags'])) {
            $tagIds = [];
            foreach ($validated['tags'] as $tagName) {
                $tag      = Tag::firstOrCreate(
                    ['name' => trim($tagName)],
                    ['color' => $this->randomColor()]
                );
                $tagIds[] = $tag->id;
            }
            $note->tags()->sync($tagIds);
        }

        return redirect()->route('notes.show', $note)
            ->with('success', '✅ Note created successfully!');
    }

    /**
     * Display the specified note.
     */
    public function show(Note $note)
    {
        $this->authorize($note);
        $note->load(['tags', 'category']);
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified note.
     */
    public function edit(Note $note)
    {
        $this->authorize($note);
        $note->load(['tags', 'category']);
        $categories = Category::where('user_id', Auth::id())->get();
        $tags       = Tag::all();
        return view('notes.edit', compact('note', 'categories', 'tags'));
    }

    /**
     * Update the specified note.
     */
    public function update(Request $request, Note $note)
    {
        $this->authorize($note);

        $validated = $request->validate([
            'title'       => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'priority'    => 'in:low,medium,high',
            'is_pinned'   => 'boolean',
            'color'       => 'nullable|string|max:7',
            'tags'        => 'array',
            'tags.*'      => 'string|max:50',
        ]);

        $note->update([
            'title'       => $validated['title'] ?? $note->title,
            'content'     => $validated['content'] ?? $note->content,
            'category_id' => $validated['category_id'] ?? null,
            'priority'    => $validated['priority'] ?? $note->priority,
            'is_pinned'   => $request->boolean('is_pinned'),
            'color'       => $validated['color'] ?? $note->color,
        ]);

        // Sync tags
        if (isset($validated['tags'])) {
            $tagIds = [];
            foreach ($validated['tags'] as $tagName) {
                $tag      = Tag::firstOrCreate(
                    ['name' => trim($tagName)],
                    ['color' => $this->randomColor()]
                );
                $tagIds[] = $tag->id;
            }
            $note->tags()->sync($tagIds);
        } else {
            $note->tags()->detach();
        }

        return redirect()->route('notes.show', $note)
            ->with('success', '✅ Note updated successfully!');
    }

    /**
     * Soft-delete a note (move to Trash).
     */
    public function destroy(Note $note)
    {
        $this->authorize($note);
        $note->delete();

        // Check referer to avoid redirecting to the now-deleted note's page (which would cause a 404).
        $referer = request()->headers->get('referer');
        $showUrl = route('notes.show', $note);
        $editUrl = route('notes.edit', $note);

        if ($referer === $showUrl || $referer === $editUrl) {
            return redirect()->route('notes.index')->with('success', '🗑️ Note moved to Trash.');
        }

        return back()->with('success', '🗑️ Note moved to Trash.');
    }

    /**
     * Toggle pin status.
     */
    public function togglePin(Note $note)
    {
        $this->authorize($note);
        $note->update(['is_pinned' => !$note->is_pinned]);
        $msg = $note->is_pinned ? '📌 Note pinned!' : '📌 Note unpinned!';
        return back()->with('success', $msg);
    }

    /**
     * Authorize that the note belongs to the current user.
     */
    private function authorize(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Return a random tag color.
     */
    private function randomColor(): string
    {
        $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#ef4444'];
        return $colors[array_rand($colors)];
    }
}
