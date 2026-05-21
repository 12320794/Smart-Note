<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = ['note_id', 'reminder_date', 'status'];

    protected $casts = [
        'reminder_date' => 'datetime',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
