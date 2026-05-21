<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    /** Tag belongs to many notes */
    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }
}
