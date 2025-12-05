<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_admin' => 'boolean',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relasi ke likes (many-to-many dengan Photo)
    public function likes()
    {
        return $this->belongsToMany(Photo::class, 'likes');
    }

    // Relasi ke savedPhotos (many-to-many dengan Photo)
    public function savedPhotos()
    {
        return $this->belongsToMany(Photo::class, 'saves');
    }

    public function boards()
{
    return $this->hasMany(Board::class);
}

public function followers()
{
    return $this->hasMany(Follow::class, 'following_id');
}

public function following()
{
    return $this->hasMany(Follow::class, 'follower_id');
}

public function isFollowing(User $user)
{
    return $this->following()->where('following_id', $user->id)->exists();
}

public function followersCount()
{
    return $this->followers()->count();
}

public function followingCount()
{
    return $this->following()->count();
}

public function reports()
{
    return $this->hasMany(Report::class, 'reporter_id');
}

public function reportsReceived()
{
    return $this->morphMany(Report::class, 'reportable');
}
}
