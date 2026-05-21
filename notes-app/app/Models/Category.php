<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['user_id', 'name', 'color', 'icon'];

    /** Category belongs to a user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Category has many notes */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
