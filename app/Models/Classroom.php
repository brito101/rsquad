<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
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
        'order',
        'status',
        'order',
        'active',
        'link',
        'vimeo_id',
        'vimeo_uri',
        'vimeo_thumbnail',
        'vimeo_player_url',
        'pdf_file',
        'release_date',
        'course_id',
        'course_module_id',
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

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id')->withDefault([
            'name' => 'Módulo excluído',
        ]);
    }

    public function progress()
    {
        return $this->hasMany(ClassroomProgress::class);
    }

    public function pdfDownloads()
    {
        return $this->morphMany(PdfDownload::class, 'downloadable');
    }

    /** Helpers */
    public function hasPdf(): bool
    {
        return ! empty($this->pdf_file);
    }

    public function getPdfPath(): ?string
    {
        if (! $this->hasPdf()) {
            return null;
        }

        return storage_path('app/private/pdfs/classes/'.$this->pdf_file);
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
}
