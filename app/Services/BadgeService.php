<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\ClassroomProgress;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\UserBadge;
use Illuminate\Support\Str;

class BadgeService
{
    /**
     * Check if a student is eligible for the course badge
     * (has completed 100% of the course)
     */
    public function checkEligibility(int $userId, int $courseId): bool
    {
        // Verify if student is enrolled
        $enrollment = CourseStudent::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (! $enrollment) {
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
     * Award course badge to a student for completing a course
     */
    public function awardCourseBadge(int $userId, int $courseId): ?array
    {
        // Check eligibility
        if (! $this->checkEligibility($userId, $courseId)) {
            return null;
        }

        // Get course with badge
        $course = Course::find($courseId);
        
        // Check if course has a badge
        if (! $course || ! $course->badge_name || ! $course->badge_image) {
            return null;
        }

        // Check if badge already awarded
        $existingBadge = UserBadge::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingBadge) {
            return null; // Already awarded
        }

        // Award the badge
        $userBadge = UserBadge::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'earned_at' => now(),
            'share_token' => Str::uuid()->toString(),
        ]);

        return [
            'badge' => $userBadge,
            'badge_name' => $course->badge_name,
            'badge_image' => $course->badge_image,
        ];
    }

    /**
     * Check and award badge after a class is marked as watched
     */
    public function checkAndAwardBadge(int $userId, int $courseId): ?array
    {
        return $this->awardCourseBadge($userId, $courseId);
    }

    /**
     * Get all badges for a user
     */
    public function getUserBadges(int $userId)
    {
        return UserBadge::where('user_id', $userId)
            ->with('course')
            ->orderBy('earned_at', 'desc')
            ->get()
            ->map(function ($userBadge) {
                return [
                    'id' => $userBadge->id,
                    'badge_name' => $userBadge->course->badge_name,
                    'badge_image' => $userBadge->course->badge_image,
                    'course_name' => $userBadge->course->name,
                    'earned_at' => $userBadge->earned_at,
                    'share_token' => $userBadge->share_token,
                ];
            });
    }

    /**
     * Get available badges for a user (not yet earned)
     * Returns ALL badges in the system, not just enrolled courses
     */
    public function getAvailableBadges(int $userId)
    {
        // Get earned course IDs
        $earnedCourseIds = UserBadge::where('user_id', $userId)
            ->pluck('course_id')
            ->toArray();

        // Get ALL courses with badges that user hasn't earned yet
        return Course::whereNotIn('id', $earnedCourseIds)
            ->whereNotNull('badge_name')
            ->whereNotNull('badge_image')
            ->where('active', true)
            ->get()
            ->map(function ($course) use ($userId) {
                // Check if user is enrolled
                $isEnrolled = CourseStudent::where('user_id', $userId)
                    ->where('course_id', $course->id)
                    ->exists();
                
                return [
                    'course_id' => $course->id,
                    'course_name' => $course->name,
                    'badge_name' => $course->badge_name,
                    'badge_image' => $course->badge_image,
                    'progress' => $isEnrolled ? $this->getCompletionPercentage($userId, $course->id) : 0,
                    'is_enrolled' => $isEnrolled,
                ];
            });
    }

    /**
     * Get badge progress for a specific course
     */
    public function getBadgeProgress(int $userId, int $courseId): ?array
    {
        $course = Course::find($courseId);
        
        if (! $course || ! $course->badge_name || ! $course->badge_image) {
            return null;
        }

        $percentage = $this->getCompletionPercentage($userId, $courseId);
        
        $earned = UserBadge::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        return [
            'badge_name' => $course->badge_name,
            'badge_image' => $course->badge_image,
            'course_name' => $course->name,
            'earned' => $earned,
            'progress' => $percentage,
        ];
    }

    /**
     * Generate LinkedIn share text for a badge
     */
    public function getLinkedInShareText(int $userId, int $courseId): string
    {
        $course = Course::find($courseId);
        
        if (! $course || ! $course->badge_name) {
            return 'Conquista desbloqueada!';
        }

        return "Acabei de conquistar a badge: {$course->badge_name}! 🏆";
    }
}
