<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    /**
     * Display all soft-deleted notes (trash bin).
     */
    public function index()
    {
        $notes = Note::onlyTrashed()
            ->where('user_id', Auth::id())
            ->latest('deleted_at')
            ->paginate(12);

        return view('trash.index', compact('notes'));
    }

    /**
     * Restore a soft-deleted note.
     */
    public function restore($id)
    {
        $note = Note::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $note->restore();
        return back()->with('success', '♻️ Note restored successfully!');
    }

    /**
     * Permanently delete a note.
     */
    public function forceDelete($id)
    {
        $note = Note::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $note->forceDelete();
        return back()->with('success', '❌ Note permanently deleted!');
    }

    /**
     * Empty the entire trash for the user.
     */
    public function emptyTrash()
    {
        Note::onlyTrashed()->where('user_id', Auth::id())->forceDelete();
        return back()->with('success', '🗑️ Trash emptied!');
    }
}
