<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    // Static reasons for reporting
    public static function getReasons()
    {
        return [
            'spam' => 'Spam or misleading content',
            'inappropriate' => 'Inappropriate content',
            'harassment' => 'Harassment or bullying',
            'violence' => 'Violent or dangerous content',
            'copyright' => 'Copyright violation',
            'impersonation' => 'Impersonation',
            'fake_account' => 'Fake account',
            'other' => 'Other'
        ];
    }
}
