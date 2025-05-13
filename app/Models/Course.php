<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
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
        'active',
        'sales_link',
        'user_id',
    ];

    /** Relationships */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Usuário excluído',
        ]);
    }

    public function categories()
    {
        return $this->hasMany(CourseCategoryPivot::class, 'course_id');
    }

    public function authors()
    {
        return $this->hasMany(CourseAuthor::class, 'course_id');
    }

    public function students()
    {
        return $this->hasMany(CourseStudent::class, 'course_id');
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class, 'course_id');
    }

    public function classes()
    {
        return $this->hasMany(Classroom::class, 'course_id');
    }

    public function authorsInfo()
    {
        return $this->hasManyThrough(User::class, CourseAuthor::class, 'course_id', 'id', 'id', 'user_id');
    }

    public function categoriesInfo()
    {
        return $this->hasManyThrough(CategoryCourse::class, CourseCategoryPivot::class, 'course_id', 'id', 'id', 'category_course_id');
    }

    /** Cascade actions */
    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($course) {
            $course->categories()->delete();
            $course->authors()->delete();
            $course->students()->delete();
            $course->modules()->delete();
            $course->classes()->delete();
        });
    }
}
