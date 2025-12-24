<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\UserBadge;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    /**
     * Display a listing of user's badges
     */
    public function index()
    {
        $user = Auth::user();
        
        $earnedBadges = $this->badgeService->getUserBadges($user->id);
        $availableBadges = $this->badgeService->getAvailableBadges($user->id);

        return view('academy.badges.index', compact('earnedBadges', 'availableBadges'));
    }

    /**
     * Generate share token for LinkedIn
     */
    public function generateShareToken(Request $request, $badgeId)
    {
        $userBadge = UserBadge::where('user_id', Auth::id())
            ->where('id', $badgeId)
            ->firstOrFail();

        $shareText = $this->badgeService->getLinkedInShareText(Auth::id(), $userBadge->course_id);

        return response()->json([
            'success' => true,
            'share_text' => $shareText,
            'share_url' => route('badges.public', ['token' => $userBadge->share_token]),
        ]);
    }

    /**
     * Public view of badge (no auth required)
     */
    public function publicView($token)
    {
        $userBadge = UserBadge::with(['user', 'course'])
            ->where('share_token', $token)
            ->firstOrFail();

        return view('academy.badges.public', compact('userBadge'));
    }

    /**
     * Get badge progress for a course (AJAX)
     */
    public function courseProgress(Request $request, $courseId)
    {
        $user = Auth::user();
        $progress = $this->badgeService->getBadgeProgress($user->id, $courseId);

        return response()->json([
            'success' => true,
            'badges' => $progress,
        ]);
    }
}
