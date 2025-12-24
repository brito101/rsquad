<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'earned_at',
        'share_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'earned_at' => 'datetime',
    ];

    /** Relationships */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Usuário excluído',
        ]);
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class)->withDefault([
            'name' => 'Badge excluída',
        ]);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class)->withDefault([
            'name' => 'Curso excluído',
        ]);
    }
}
