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
}
