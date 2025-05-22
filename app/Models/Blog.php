<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'subtitle', 'cover', 'content', 'views', 'status', 'uri', 'user_id'];

    /** Relationships */
    public function categories()
    {
        return $this->hasMany(BlogCategoriesPivot::class);
    }
}
