<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_id',
        'comment',
    ];

    protected $with = ['user']; // Eager load user relationship

    protected $appends = ['time_ago', 'can_edit', 'can_delete'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    // Accessor for time ago
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Accessor for edit permission
    public function getCanEditAttribute()
    {
        return auth()->check() && auth()->id() === $this->user_id;
    }

    // Accessor for delete permission
    public function getCanDeleteAttribute()
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->id() === $this->user_id ||
               auth()->id() === $this->photo->user_id;
    }

    // Scope for recent comments
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(7));
    }

    // Scope for this week
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // Scope for this month
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }
}
