<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModule extends Model
{
    use SoftDeletes;

    protected array $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cover',
        'description',
        'status',
        'order',
        'active',
        'link',
        'release_date',
        'course_id',
        'user_id',
    ];

    protected $appends = [
        'release_date_br',
    ];

    /** Relationships */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Usuário excluído',
        ]);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class)->withDefault([
            'name' => 'Curso excluído',
        ]);
    }

    public function classes()
    {
        return $this->hasMany(Classroom::class, 'course_module_id');
    }
    /** Accessors */
    public function getReleaseDateBrAttribute(): ?string
    {
        if ($this->release_date) {
            return date('d/m/Y', strtotime($this->release_date));
        } else {
            return null;
        }
    }

    /** Cascade actions */
    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($course) {
            $course->classes()->delete();
        });
    }
}
