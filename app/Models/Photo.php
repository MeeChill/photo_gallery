<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'category',
        'width',
        'height',
        'downloads',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke likes (many-to-many dengan User)
    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    // Relasi ke saves (many-to-many dengan User)
    public function saves()
    {
        return $this->belongsToMany(User::class, 'saves');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function commentsCount()
{
    return $this->comments()->count();
}

public function getCommentsCountAttribute()
{
    return $this->comments()->count();
}

    // Check if current user liked this photo
    public function isLiked()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    // Check if current user saved this photo
    public function isSaved()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->saves()->where('user_id', auth()->id())->exists();
    }



public function latestComments($limit = 5)
{
    return $this->comments()->latest()->take($limit)->get();
}
}
