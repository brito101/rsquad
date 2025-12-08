<?php

namespace App\Http\Controllers\Academy;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\ClassroomProgress;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Testimonial;
use App\Services\CertificateService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Acessar Academia');

        if ($request->ajax()) {
            $pivot = CourseStudent::where('user_id', auth()->user()->id)->pluck('course_id');

            $courses = Course::whereIn('id', $pivot)
                ->where('active', true)
                ->get();

            try {
                $certificateService = new CertificateService();
                $userId = auth()->user()->id;

                return DataTables::of($courses)
                    ->addIndexColumn()
                    ->addColumn('cover', function ($row) {
                        return '<div class="d-flex justify-content-center align-items-center"><img src="'.($row->cover ? url('storage/courses/min/'.$row->cover) : asset('img/defaults/min/courses.webp')).'" class="img-thumbnail d-block" width="360" height="207" alt="'.$row->name.'" title="'.$row->name.'"/></div>';
                    })
                    ->addColumn('categories', function ($row) {
                        return $row->categories->map(function ($pivot) {
                            return $pivot->category->name;
                        })->implode(' - ');
                    })
                    ->addColumn('modules', function ($row) {
                        return $row->modules->where('active', true)->count();
                    })
                    ->addColumn('classes', function ($row) {
                        return $row->classes->where('active', true)->count();
                    })
                    ->addColumn('instructors', function ($row) {
                        return $row->instructors->map(function ($pivot) {
                            return $pivot->user->name;
                        })->implode(' - ');
                    })
                    ->addColumn('status', function ($row) use ($certificateService, $userId) {
                        $percentage = $certificateService->getCompletionPercentage($userId, $row->id);
                        
                        // Verificar se tem certificado
                        $certificate = Certificate::where('user_id', $userId)
                            ->where('course_id', $row->id)
                            ->first();
                        
                        if ($percentage == 100 && $certificate) {
                            return '<div class="text-center">
                                        <div class="badge badge-success mb-1" style="font-size: 14px;">
                                            <i class="fas fa-check-circle"></i> Concluído (100%)
                                        </div><br>
                                        <a href="'.route('academy.certificates.show', $certificate->id).'" 
                                           class="btn btn-sm btn-primary" 
                                           title="Ver Certificado">
                                            <i class="fas fa-certificate"></i> Certificado
                                        </a>
                                    </div>';
                        } else if ($percentage == 100) {
                            return '<div class="text-center">
                                        <span class="badge badge-success" style="font-size: 14px;">
                                            <i class="fas fa-check-circle"></i> Concluído (100%)
                                        </span>
                                    </div>';
                        } else if ($percentage > 0) {
                            return '<div class="text-center">
                                        <div class="progress" style="height: 25px; min-width: 120px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: '.$percentage.'%;" 
                                                 aria-valuenow="'.$percentage.'" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                '.$percentage.'%
                                            </div>
                                        </div>
                                    </div>';
                        } else {
                            return '<div class="text-center">
                                        <span class="badge badge-secondary" style="font-size: 14px;">
                                            <i class="fas fa-hourglass-start"></i> Não iniciado
                                        </span>
                                    </div>';
                        }
                    })
                    ->addColumn('action', function ($row) {
                        return '<a class="btn btn-xs btn-success mx-1 shadow" title="Editar" href="'.route('academy.courses.show', ['course' => $row->id]).'"><i class="fa fa-lg fa-fw fa-eye"></i></a>';
                    })
                    ->rawColumns(['cover', 'categories', 'modules', 'instructors', 'status', 'action'])
                    ->make(true);
            } catch (Exception $e) {
                return response([
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return view('academy.courses.index');
    }

    public function show(Course $course)
    {
        CheckPermission::checkAuth('Acessar Academia');

        $pivot = CourseStudent::where('user_id', auth()->user()->id)->pluck('course_id');

        $course = Course::whereIn('id', $pivot)
            ->where('active', true)
            ->where('id', $course->id)
            ->with([
                'categories',
                'modules',
                'classes',
                'instructors',
            ])
            ->first();

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        $modules = $course->modules()->where('active', true)->orderBy('order')->get();
        $classes = $course->classes()->where('active', true)->orderBy('order')->get();

        // Get user's progress for all classes in this course
        $classIds = $classes->pluck('id')->toArray();
        $userProgress = ClassroomProgress::where('user_id', auth()->user()->id)
            ->whereIn('classroom_id', $classIds)
            ->get()
            ->keyBy('classroom_id');

        // Calculate course progress
        $totalClasses = $classes->count();
        $watchedClasses = $userProgress->where('watched', true)->count();
        $progressPercentage = $totalClasses > 0 ? round(($watchedClasses / $totalClasses) * 100, 2) : 0;

        // Check if user already has a testimonial for this course
        $userTestimonial = Testimonial::where('user_id', auth()->user()->id)
            ->where('course_id', $course->id)
            ->first();

        return view('academy.courses.show', compact(
            'course',
            'modules',
            'classes',
            'userProgress',
            'progressPercentage',
            'totalClasses',
            'watchedClasses',
            'userTestimonial',
        ));
    }

    /**
     * Store a testimonial for a course
     */
    public function storeTestimonial(Request $request, Course $course)
    {
        CheckPermission::checkAuth('Criar Depoimentos');

        // Verify user is enrolled in the course
        $isEnrolled = CourseStudent::where('user_id', auth()->user()->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()
                ->back()
                ->with('error', 'Você precisa estar matriculado no curso para enviar um depoimento.');
        }

        // Calculate course completion percentage
        $classes = $course->classes()->where('active', true)->get();
        $totalClasses = $classes->count();
        
        if ($totalClasses > 0) {
            $classIds = $classes->pluck('id')->toArray();
            $watchedClasses = ClassroomProgress::where('user_id', auth()->user()->id)
                ->whereIn('classroom_id', $classIds)
                ->where('watched', true)
                ->count();
            
            $progressPercentage = round(($watchedClasses / $totalClasses) * 100, 2);
            
            if ($progressPercentage < 100) {
                return redirect()
                    ->back()
                    ->with('error', 'Você precisa completar 100% do curso para enviar um depoimento. Seu progresso atual: ' . $progressPercentage . '%');
            }
        }

        // Check if user already has a testimonial for this course
        $existingTestimonial = Testimonial::where('user_id', auth()->user()->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingTestimonial) {
            return redirect()
                ->back()
                ->with('error', 'Você já enviou um depoimento para este curso.');
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'testimonial' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Por favor, selecione uma avaliação.',
            'rating.min' => 'A avaliação deve ser de no mínimo 1 estrela.',
            'rating.max' => 'A avaliação deve ser de no máximo 5 estrelas.',
            'testimonial.required' => 'Por favor, escreva seu depoimento.',
            'testimonial.min' => 'O depoimento deve ter no mínimo 10 caracteres.',
            'testimonial.max' => 'O depoimento deve ter no máximo 1000 caracteres.',
        ]);

        $data['user_id'] = auth()->user()->id;
        $data['course_id'] = $course->id;
        $data['status'] = 'pending';

        Testimonial::create($data);

        return redirect()
            ->route('academy.courses.show', $course->id)
            ->with('success', 'Depoimento enviado com sucesso! Ele será analisado pela equipe.');
    }
}
