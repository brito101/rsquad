<?php

namespace App\Console\Commands;

use App\Models\CourseStudent;
use App\Services\BadgeService;
use Illuminate\Console\Command;

class AwardMissingBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badges:award-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Award badges to students who completed courses but haven\'t received badges yet';

    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        parent::__construct();
        $this->badgeService = $badgeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processando badges faltantes...');

        $enrollments = CourseStudent::with(['user', 'course'])->get();
        $badgesAwarded = 0;

        foreach ($enrollments as $enrollment) {
            $userId = $enrollment->user_id;
            $courseId = $enrollment->course_id;

            // Check if course has badge
            if (! $enrollment->course->badge_name || ! $enrollment->course->badge_image) {
                continue;
            }

            // Check if student completed the course (100%)
            $completion = $this->badgeService->getCompletionPercentage($userId, $courseId);

            if ($completion >= 100) {
                // Try to award badge
                $result = $this->badgeService->awardCourseBadge($userId, $courseId);

                if ($result) {
                    $this->info("✓ Badge '{$result['badge_name']}' concedida para {$enrollment->user->name} (Curso: {$enrollment->course->name})");
                    $badgesAwarded++;
                }
            }
        }

        $this->info("\nTotal de badges concedidas: {$badgesAwarded}");

        return Command::SUCCESS;
    }
}
