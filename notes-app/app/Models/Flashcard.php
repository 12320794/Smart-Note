<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    protected $fillable = ['note_id', 'question', 'answer'];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
