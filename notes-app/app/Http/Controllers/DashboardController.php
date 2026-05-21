<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard with statistics and recent notes.
     */
    public function index()
    {
        $userId = Auth::id();

        // Stats for the dashboard cards
        $stats = [
            'total'    => Note::forUser($userId)->count(),
            'pinned'   => Note::forUser($userId)->where('is_pinned', true)->count(),
            'high'     => Note::forUser($userId)->where('priority', 'high')->count(),
            'trashed'  => Note::onlyTrashed()->where('user_id', $userId)->count(),
            'folders'  => Category::where('user_id', $userId)->count(),
        ];

        // Recently edited notes (last 8)
        $recentNotes = Note::with(['tags', 'category'])
            ->forUser($userId)
            ->pinnedFirst()
            ->limit(8)
            ->get();

        // Pinned notes
        $pinnedNotes = Note::with(['tags', 'category'])
            ->forUser($userId)
            ->where('is_pinned', true)
            ->limit(4)
            ->get();

        // Folders with note counts
        $categories = Category::where('user_id', $userId)
            ->withCount('notes')
            ->get();

        return view('dashboard', compact('stats', 'recentNotes', 'pinnedNotes', 'categories'));
    }
}
