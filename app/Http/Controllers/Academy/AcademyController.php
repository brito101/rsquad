<?php

namespace App\Http\Controllers\Academy;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseStudent;
use App\Models\Views\Classroom;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Acessar Academia');

        $pivot = CourseStudent::where('user_id', auth()->user()->id)->pluck('course_id');

        $courses = Course::whereIn('id', $pivot)
            ->where('active', true)
            ->get();

        $modules = CourseModule::whereIn('course_id', $courses->pluck('id'))
            ->where('active', true)
            ->get();
        $classes = Classroom::whereIn('course_module_id', $modules->pluck('id'))
            ->where('status', true)
            ->get();

        $courses_avaliable = Course::whereNotIn('id', $courses->pluck('id'))
            ->where('active', true)
            ->get();

        return view('academy.home.index', compact('courses', 'modules', 'classes', 'courses_avaliable'));
    }
}
