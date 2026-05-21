<?php

namespace App\Observers;

use App\Models\Note;
use App\Models\ActivityLog;

class NoteObserver
{
    private function logActivity(Note $note, string $activity)
    {
        if ($note->user_id) {
            ActivityLog::create([
                'user_id' => $note->user_id,
                'activity' => $activity . " note: " . ($note->title ?: 'Untitled')
            ]);
        }
    }

    public function created(Note $note): void
    {
        $this->logActivity($note, 'Created');
    }

    public function updated(Note $note): void
    {
        $this->logActivity($note, 'Updated');
    }

    public function deleted(Note $note): void
    {
        $this->logActivity($note, 'Archived');
    }

    public function restored(Note $note): void
    {
        $this->logActivity($note, 'Restored');
    }

    public function forceDeleted(Note $note): void
    {
        $this->logActivity($note, 'Permanently deleted');
    }
}
