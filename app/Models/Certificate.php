<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'started_at',
        'issued_at',
        'verification_code',
        'pdf_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'issued_at' => 'datetime',
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

    /** Scopes */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('issued_at', 'desc');
    }

    /** Helper Methods */
    
    /**
     * Generate a unique verification code
     */
    public static function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (self::where('verification_code', $code)->exists());

        return $code;
    }

    /**
     * Get the public verification URL
     */
    public function getVerificationUrl(): string
    {
        return route('certificates.verify', ['code' => $this->verification_code]);
    }

    /**
     * Get public certificate URL
     */
    public function getPublicUrl(): string
    {
        return route('certificates.public', ['code' => $this->verification_code]);
    }

    /**
     * Get public PDF URL
     */
    public function getPublicPdfUrl(): string
    {
        return route('certificates.public.pdf', ['code' => $this->verification_code]);
    }

    /**
     * Get LinkedIn certification URL
     * This URL allows users to add the certification to their LinkedIn profile
     */
    public function getLinkedInCertificationUrl(): string
    {
        // LinkedIn Add to Profile URL format
        // https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME
        
        $params = [
            'startTask' => 'CERTIFICATION_NAME',
            'name' => $this->course->name,
            'organizationName' => config('app.name', 'RSquad Academy'),
            'issueYear' => $this->issued_at->year,
            'issueMonth' => $this->issued_at->month,
            'certUrl' => $this->getPublicUrl(),
            'certId' => $this->verification_code,
        ];

        return 'https://www.linkedin.com/profile/add?' . http_build_query($params);
    }

    /**
     * Get LinkedIn share URL (for posting)
     */
    public function getLinkedInShareUrl(): string
    {
        $url = $this->getPublicUrl();
        $text = "Concluí o curso '{$this->course->name}' na " . config('app.name', 'RSquad Academy') . "!";
        
        return 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url);
    }

    /**
     * Get shareable text for social media
     */
    public function getShareableText(): string
    {
        return "🎓 Certificado de Conclusão\n\n" .
               "Curso: {$this->course->name}\n" .
               "Concluído em: {$this->issued_at->format('d/m/Y')}\n" .
               "Carga horária: {$this->course->total_hours}h\n\n" .
               "Verifique a autenticidade: {$this->getVerificationUrl()}";
    }

    /**
     * Get the duration in days between start and completion
     */
    public function getDurationInDays(): int
    {
        if (!$this->started_at) {
            return 0;
        }

        return $this->started_at->diffInDays($this->issued_at);
    }

    /**
     * Get formatted period text
     */
    public function getFormattedPeriod(): string
    {
        if (!$this->started_at) {
            return $this->issued_at->format('d/m/Y');
        }

        return $this->started_at->format('d/m/Y') . ' a ' . $this->issued_at->format('d/m/Y');
    }

    /**
     * Check if PDF exists
     */
    public function hasPdf(): bool
    {
        if (!$this->pdf_path) {
            return false;
        }

        return file_exists(storage_path('app/' . $this->pdf_path));
    }

    /**
     * Get PDF full path
     */
    public function getPdfFullPath(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }

        return storage_path('app/' . $this->pdf_path);
    }
}
