<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Classroom;
use App\Models\ClassroomProgress;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    /**
     * Check if a student is eligible for a certificate
     * (has completed 100% of the course)
     */
    public function checkEligibility(int $userId, int $courseId): bool
    {
        // Verify if student is enrolled
        $enrollment = CourseStudent::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return false;
        }

        // Get total classes in the course
        $totalClasses = Classroom::where('course_id', $courseId)
            ->where('active', true)
            ->count();

        if ($totalClasses === 0) {
            return false;
        }

        // Get watched classes count
        $watchedClasses = ClassroomProgress::where('user_id', $userId)
            ->whereHas('classroom', function ($query) use ($courseId) {
                $query->where('course_id', $courseId)
                    ->where('active', true);
            })
            ->where('watched', true)
            ->count();

        // Check if completed 100%
        return $watchedClasses >= $totalClasses;
    }

    /**
     * Get the completion percentage for a student in a course
     */
    public function getCompletionPercentage(int $userId, int $courseId): float
    {
        $totalClasses = Classroom::where('course_id', $courseId)
            ->where('active', true)
            ->count();

        if ($totalClasses === 0) {
            return 0;
        }

        $watchedClasses = ClassroomProgress::where('user_id', $userId)
            ->whereHas('classroom', function ($query) use ($courseId) {
                $query->where('course_id', $courseId)
                    ->where('active', true);
            })
            ->where('watched', true)
            ->count();

        return round(($watchedClasses / $totalClasses) * 100, 2);
    }

    /**
     * Get the date when student started the course (first watched class)
     */
    public function getStartedAt(int $userId, int $courseId): ?\DateTime
    {
        $firstProgress = ClassroomProgress::where('user_id', $userId)
            ->whereHas('classroom', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('watched', true)
            ->whereNotNull('first_viewed_at')
            ->orderBy('first_viewed_at', 'asc')
            ->first();

        return $firstProgress?->first_viewed_at;
    }

    /**
     * Generate a certificate for a student
     */
    public function generateCertificate(int $userId, int $courseId): ?Certificate
    {
        // Check eligibility
        if (!$this->checkEligibility($userId, $courseId)) {
            return null;
        }

        // Check if certificate already exists
        $existingCertificate = Certificate::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingCertificate) {
            return $existingCertificate;
        }

        // Get started_at date
        $startedAt = $this->getStartedAt($userId, $courseId);

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'started_at' => $startedAt,
            'issued_at' => now(),
            'verification_code' => Certificate::generateVerificationCode(),
        ]);

        // Generate PDF
        try {
            $this->generatePDF($certificate);
        } catch (\Exception $e) {
            // Log error but don't fail certificate creation
            \Log::error('Failed to generate certificate PDF', [
                'certificate_id' => $certificate->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $certificate;
    }

    /**
     * Generate PDF for a certificate
     */
    public function generatePDF(Certificate $certificate): string
    {
        $certificate->load(['user', 'course']);

        // Render the certificate HTML
        $html = view('certificates.template', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
        ])->render();

        // Generate PDF using dompdf
        $pdf = \PDF::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'sans-serif',
                'dpi' => 150,
                'defaultMediaType' => 'print',
                'isFontSubsettingEnabled' => true,
            ]);

        // Define storage path
        $filename = 'certificates/' . $certificate->verification_code . '.pdf';
        $fullPath = storage_path('app/private/' . $filename);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save PDF
        $pdf->save($fullPath);

        // Update certificate with PDF path
        $certificate->update([
            'pdf_path' => 'private/' . $filename,
        ]);

        return $fullPath;
    }

    /**
     * Get all certificates for a student
     */
    public function getStudentCertificates(int $userId)
    {
        return Certificate::with(['course'])
            ->forUser($userId)
            ->recent()
            ->get();
    }

    /**
     * Verify a certificate by verification code
     */
    public function verifyCertificate(string $code): ?Certificate
    {
        return Certificate::with(['user', 'course'])
            ->where('verification_code', $code)
            ->first();
    }

    /**
     * Get certificate statistics for a user
     */
    public function getUserStatistics(int $userId): array
    {
        $certificates = Certificate::forUser($userId)->get();

        return [
            'total_certificates' => $certificates->count(),
            'total_hours' => $certificates->sum(function ($cert) {
                return $cert->course->total_hours ?? 0;
            }),
            'courses_completed' => $certificates->pluck('course_id')->unique()->count(),
            'latest_certificate' => $certificates->sortByDesc('issued_at')->first(),
        ];
    }
}
