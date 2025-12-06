<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\ClassroomProgress;
use App\Models\CourseStudent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ClassroomProgressController extends Controller
{
    /**
     * Show individual classroom video player page
     */
    public function show($classroomId): View
    {
        $classroom = Classroom::with(['course.modules', 'course.classes'])->findOrFail($classroomId);
        $user = Auth::user();

        // Verify if user is enrolled in the course
        $isEnrolled = CourseStudent::where('user_id', $user->id)
            ->where('course_id', $classroom->course_id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Você não está matriculado neste curso.');
        }

        // Get user progress for this classroom
        $progress = ClassroomProgress::where('user_id', $user->id)
            ->where('classroom_id', $classroom->id)
            ->first();

        $isWatched = $progress ? $progress->watched : false;

        // Get all classrooms from this course ordered by module and order
        $allClassrooms = $classroom->course->classes()
            ->where('active', true)
            ->orderBy('course_module_id')
            ->orderBy('order')
            ->get();

        // Find previous and next classrooms
        $currentIndex = $allClassrooms->search(function ($item) use ($classroom) {
            return $item->id === $classroom->id;
        });

        $previousClassroom = $currentIndex > 0 ? $allClassrooms[$currentIndex - 1] : null;
        $nextClassroom = $currentIndex < $allClassrooms->count() - 1 ? $allClassrooms[$currentIndex + 1] : null;

        return view('academy.classroom.show', compact(
            'classroom',
            'isWatched',
            'previousClassroom',
            'nextClassroom'
        ));
    }

    /**
     * Register a video view event
     */
    public function registerView(Request $request, $classroomId): JsonResponse
    {
        try {
            $validator = Validator::make(['classroom_id' => $classroomId], [
                'classroom_id' => 'required|exists:classrooms,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aula não encontrada.',
                    'errors' => $validator->errors(),
                ], 404);
            }

            $user = Auth::user();
            $classroom = Classroom::findOrFail($classroomId);

            // Verify if user is enrolled in the course
            $isEnrolled = CourseStudent::where('user_id', $user->id)
                ->where('course_id', $classroom->course_id)
                ->exists();

            if (! $isEnrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não está matriculado neste curso.',
                ], 403);
            }

            // Get or create progress record
            $progress = ClassroomProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'classroom_id' => $classroomId,
                ],
                [
                    'watched' => false,
                    'view_count' => 0,
                    'watch_time_seconds' => 0,
                ]
            );

            // Increment view count
            $progress->incrementViewCount();

            return response()->json([
                'success' => true,
                'message' => 'Visualização registrada com sucesso.',
                'data' => [
                    'view_count' => $progress->view_count,
                    'watched' => $progress->watched,
                    'first_viewed_at' => $progress->first_viewed_at?->format('Y-m-d H:i:s'),
                    'last_viewed_at' => $progress->last_viewed_at?->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar visualização.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle watched status
     */
    public function toggleWatched(Request $request, $classroomId): JsonResponse
    {
        try {
            $validator = Validator::make(['classroom_id' => $classroomId], [
                'classroom_id' => 'required|exists:classrooms,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aula não encontrada.',
                    'errors' => $validator->errors(),
                ], 404);
            }

            $user = Auth::user();
            $classroom = Classroom::findOrFail($classroomId);

            // Verify if user is enrolled in the course
            $isEnrolled = CourseStudent::where('user_id', $user->id)
                ->where('course_id', $classroom->course_id)
                ->exists();

            if (! $isEnrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não está matriculado neste curso.',
                ], 403);
            }

            // Get or create progress record
            $progress = ClassroomProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'classroom_id' => $classroomId,
                ],
                [
                    'watched' => false,
                    'view_count' => 0,
                    'watch_time_seconds' => 0,
                ]
            );

            // Toggle watched status
            if ($progress->watched) {
                $progress->markAsUnwatched();
                $message = 'Aula marcada como não assistida.';
            } else {
                $progress->markAsWatched();
                $message = 'Aula marcada como assistida.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'watched' => $progress->watched,
                    'view_count' => $progress->view_count,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update watch time
     */
    public function updateWatchTime(Request $request, $classroomId): JsonResponse
    {
        try {
            $validator = Validator::make(
                array_merge($request->all(), ['classroom_id' => $classroomId]),
                [
                    'classroom_id' => 'required|exists:classrooms,id',
                    'seconds' => 'required|integer|min:1',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = Auth::user();
            $classroom = Classroom::findOrFail($classroomId);

            // Verify if user is enrolled in the course
            $isEnrolled = CourseStudent::where('user_id', $user->id)
                ->where('course_id', $classroom->course_id)
                ->exists();

            if (! $isEnrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não está matriculado neste curso.',
                ], 403);
            }

            // Get or create progress record
            $progress = ClassroomProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'classroom_id' => $classroomId,
                ],
                [
                    'watched' => false,
                    'view_count' => 0,
                    'watch_time_seconds' => 0,
                ]
            );

            $progress->addWatchTime($request->seconds);

            return response()->json([
                'success' => true,
                'message' => 'Tempo de visualização atualizado.',
                'data' => [
                    'watch_time_seconds' => $progress->watch_time_seconds,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar tempo de visualização.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get progress for a specific classroom
     */
    public function getProgress($classroomId): JsonResponse
    {
        try {
            $validator = Validator::make(['classroom_id' => $classroomId], [
                'classroom_id' => 'required|exists:classrooms,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aula não encontrada.',
                    'errors' => $validator->errors(),
                ], 404);
            }

            $user = Auth::user();

            $progress = ClassroomProgress::where('user_id', $user->id)
                ->where('classroom_id', $classroomId)
                ->first();

            if (! $progress) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'watched' => false,
                        'view_count' => 0,
                        'watch_time_seconds' => 0,
                        'first_viewed_at' => null,
                        'last_viewed_at' => null,
                    ],
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'watched' => $progress->watched,
                    'view_count' => $progress->view_count,
                    'watch_time_seconds' => $progress->watch_time_seconds,
                    'first_viewed_at' => $progress->first_viewed_at?->format('Y-m-d H:i:s'),
                    'last_viewed_at' => $progress->last_viewed_at?->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar progresso.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get course progress summary for the authenticated user
     */
    public function getCourseProgress($courseId): JsonResponse
    {
        try {
            $user = Auth::user();

            // Verify if user is enrolled in the course
            $isEnrolled = CourseStudent::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->exists();

            if (! $isEnrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não está matriculado neste curso.',
                ], 403);
            }

            // Get all classrooms for the course
            $classrooms = Classroom::where('course_id', $courseId)
                ->where('active', true)
                ->pluck('id');

            $totalClassrooms = $classrooms->count();

            // Get watched classrooms
            $watchedCount = ClassroomProgress::where('user_id', $user->id)
                ->whereIn('classroom_id', $classrooms)
                ->where('watched', true)
                ->count();

            $progressPercentage = $totalClassrooms > 0
                ? round(($watchedCount / $totalClassrooms) * 100, 2)
                : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'total_classrooms' => $totalClassrooms,
                    'watched_count' => $watchedCount,
                    'progress_percentage' => $progressPercentage,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar progresso do curso.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
