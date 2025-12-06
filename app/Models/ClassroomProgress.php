<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomProgress extends Model
{
    protected $table = 'classroom_progress';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'classroom_id',
        'watched',
        'view_count',
        'first_viewed_at',
        'last_viewed_at',
        'watch_time_seconds',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'watched' => 'boolean',
        'view_count' => 'integer',
        'watch_time_seconds' => 'integer',
        'first_viewed_at' => 'datetime',
        'last_viewed_at' => 'datetime',
    ];

    /** Relationships */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Usuário excluído',
        ]);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class)->withDefault([
            'name' => 'Aula excluída',
        ]);
    }

    /** Scopes */
    public function scopeWatched($query)
    {
        return $query->where('watched', true);
    }

    public function scopeNotWatched($query)
    {
        return $query->where('watched', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }

    /** Helper Methods */
    public function markAsWatched(): bool
    {
        $this->watched = true;
        return $this->save();
    }

    public function markAsUnwatched(): bool
    {
        $this->watched = false;
        return $this->save();
    }

    public function incrementViewCount(): bool
    {
        $this->view_count++;
        $this->last_viewed_at = now();
        
        if (!$this->first_viewed_at) {
            $this->first_viewed_at = now();
        }
        
        return $this->save();
    }

    public function addWatchTime(int $seconds): bool
    {
        $this->watch_time_seconds += $seconds;
        return $this->save();
    }
}
