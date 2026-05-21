<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * List all folders for the authenticated user.
     */
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())
            ->withCount('notes')
            ->latest()
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Store a new folder.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'color' => 'nullable|string|max:7',
            'icon'  => 'nullable|string|max:50',
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name'    => $validated['name'],
            'color'   => $validated['color'] ?? '#6366f1',
            'icon'    => $validated['icon']  ?? 'folder',
        ]);

        return back()->with('success', '📁 Folder created!');
    }

    /**
     * Update an existing folder.
     */
    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'color' => 'nullable|string|max:7',
            'icon'  => 'nullable|string|max:50',
        ]);

        $category->update($validated);
        return back()->with('success', '✏️ Folder updated!');
    }

    /**
     * Delete a folder (notes become uncategorized).
     */
    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) abort(403);
        $category->delete();
        return back()->with('success', '🗑️ Folder deleted!');
    }
}
