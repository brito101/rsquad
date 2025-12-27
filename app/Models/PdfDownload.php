<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PdfDownload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'download_id',
        'downloadable_type',
        'downloadable_id',
        'file_name',
        'downloaded_at',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    /**
     * Get the user who downloaded the PDF.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the downloadable model (Course, CourseModule, or Classroom).
     */
    public function downloadable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if user can download based on rate limit.
     * Maximum 5 downloads per file per hour.
     */
    public static function canDownload(int $userId, string $type, int $id): bool
    {
        $oneHourAgo = now()->subHour();

        $downloadCount = self::where('user_id', $userId)
            ->where('downloadable_type', $type)
            ->where('downloadable_id', $id)
            ->where('downloaded_at', '>=', $oneHourAgo)
            ->count();

        return $downloadCount < 5;
    }

    /**
     * Log a PDF download.
     */
    public static function logDownload(int $userId, string $downloadId, string $type, int $id, string $fileName, string $ipAddress): self
    {
        return self::create([
            'user_id' => $userId,
            'download_id' => $downloadId,
            'downloadable_type' => $type,
            'downloadable_id' => $id,
            'file_name' => $fileName,
            'downloaded_at' => now(),
            'ip_address' => $ipAddress,
        ]);
    }
}
