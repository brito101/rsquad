<?php

namespace App\Http\Controllers\Academy;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseStudent;
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
                return DataTables::of($courses)
                    ->addIndexColumn()
                    ->addColumn('cover', function ($row) {
                        return '<div class="d-flex justify-content-center align-items-center"><img src="' . ($row->cover ? url('storage/courses/min/' . $row->cover) : asset('img/defaults/min/courses.webp')) . '" class="img-thumbnail d-block" width="360" height="207" alt="' . $row->name . '" title="' . $row->name . '"/></div>';
                    })
                    ->addColumn('categories', function ($row) {
                        return $row->categories->map(function ($pivot) {
                            return $pivot->category->name;
                        })->implode(' - ');
                    })
                    ->addColumn('modules', function ($row) {
                        return $row->modules->count();
                    })
                    ->addColumn('classes', function ($row) {
                        return $row->classes->count();
                    })
                    ->addColumn('authors', function ($row) {
                        return $row->authors->map(function ($pivot) {
                            return $pivot->user->name;
                        })->implode(' - ');
                    })
                    ->addColumn('action', function ($row) {
                        return '<a class="btn btn-xs btn-success mx-1 shadow" title="Editar" href="' . route('academy.courses.show', ['course' => $row->id]) . '"><i class="fa fa-lg fa-fw fa-eye"></i></a>';
                    })
                    ->rawColumns(['cover', 'categories', 'modules', 'authors', 'action'])
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
                'authors',
            ])
            ->first();

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        $modules = $course->modules()->where('active', true)->orderBy('order')->get();
        $classes = $course->classes()->where('active', true)->orderBy('order')->get();
        
        return view('academy.courses.show', compact(
            'course',
            'modules',
            'classes',
        ));
    }
}
