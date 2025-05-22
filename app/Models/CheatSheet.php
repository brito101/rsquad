<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheatSheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'content', 'cheat_sheet_category_id', 'views', 'status', 'uri', 'user_id'];

    /** Relationships */
    public function category()
    {
        return $this->BelongsTo(CheatSheetCategory::class, 'cheat_sheet_category_id', 'id')->withDefault(['title' => 'Excluída']);
    }
}
