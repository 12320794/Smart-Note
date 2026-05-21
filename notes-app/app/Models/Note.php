<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'priority',
        'is_pinned',
        'is_private',
        'color',
    ];

    protected $casts = [
        'is_pinned'  => 'boolean',
        'is_private' => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    /** Note belongs to a user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Note belongs to a folder/category */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** Note has many tags (many-to-many) */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    /** Only notes belonging to the authenticated user */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /** Pinned notes first */
    public function scopePinnedFirst($query)
    {
        return $query->orderByDesc('is_pinned')->orderByDesc('updated_at');
    }

    /** Filter by priority */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /** Full-text search across title and content */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('content', 'like', "%{$term}%");
        });
    }
}
