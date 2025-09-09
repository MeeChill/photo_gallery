<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'photo_id',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}
