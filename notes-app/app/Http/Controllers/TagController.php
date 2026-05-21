<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * List all tags.
     */
    public function index()
    {
        $tags = Tag::withCount('notes')->latest()->get();
        return view('tags.index', compact('tags'));
    }

    /**
     * Store a new tag.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50|unique:tags,name',
            'color' => 'nullable|string|max:7',
        ]);

        Tag::create([
            'name'  => trim($validated['name']),
            'color' => $validated['color'] ?? '#6366f1',
        ]);

        return back()->with('success', '🏷️ Tag created!');
    }

    /**
     * Update a tag.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50|unique:tags,name,' . $tag->id,
            'color' => 'nullable|string|max:7',
        ]);

        $tag->update($validated);
        return back()->with('success', '✏️ Tag updated!');
    }

    /**
     * Delete a tag.
     */
    public function destroy(Tag $tag)
    {
        $tag->notes()->detach();
        $tag->delete();
        return back()->with('success', '🗑️ Tag deleted!');
    }
}
