<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryCourse extends Model
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
        'user_id',
    ];

    /** Relationships */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Usuário excluído',
        ]);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(CourseCategoryPivot::class, '');
    }
}
