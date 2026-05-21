<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'avatar', 'theme', 'profile_image'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /** User has many notes */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /** User has many folders/categories */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
