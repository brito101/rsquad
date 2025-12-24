<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseBadge extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'course_badge';

    protected $dates = ['deleted_at'];

    protected $fillable = ['course_id', 'badge_id'];

    /** Relationship */
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
