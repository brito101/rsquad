<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Workshop extends Model
{
    use SoftDeletes;

    protected array $dates = ['deleted_at', 'scheduled_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'cover',
        'video_type',
        'video_url',
        'is_public',
        'featured',
        'status',
        'scheduled_at',
        'duration',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
        'featured' => 'boolean',
        'scheduled_at' => 'datetime',
        'duration' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** Relationships */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Usuário excluído',
        ]);
    }

    /**
     * Generate slug from title
     */
    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = 1;
        
        while (self::where('slug', $slug)->exists()) {
            $slug = Str::slug($title) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    /**
     * Get the video embed URL based on video type
     */
    public function getVideoEmbedUrl(): ?string
    {
        if ($this->video_type === 'none' || empty($this->video_url)) {
            return null;
        }

        if ($this->video_type === 'youtube') {
            // Extract YouTube ID from various URL formats
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $this->video_url, $matches);
            if (isset($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        } elseif ($this->video_type === 'vimeo') {
            // Extract Vimeo ID from URL
            preg_match('/vimeo\.com\/(?:video\/)?(\d+)/i', $this->video_url, $matches);
            if (isset($matches[1])) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }
        }

        return $this->video_url;
    }

    /**
     * Scope for public workshops
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for private workshops (students only)
     */
    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    /**
     * Scope for published workshops
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for featured workshops
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
