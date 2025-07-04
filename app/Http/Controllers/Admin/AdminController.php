<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CheatSheet;
use App\Models\Course;
use App\Models\User;
use App\Models\Views\User as ViewsUser;
use App\Models\Views\Visit;
use App\Models\Views\VisitYesterday;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->hasRole('Aluno')) {
            return redirect()->route('academy.home');
        }

        CheckPermission::checkAuth('Acessar Administração');

        $administrators = ViewsUser::where('type', 'Administrador')->count();
        $instructors = ViewsUser::where('type', 'Instrutor')->count();
        $monitors = ViewsUser::where('type', 'Monitor')->count();
        $students = ViewsUser::where('type', 'Aluno')->get();

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $courses = Course::where('active', true)->with('students')->get();
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $courses = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('instructors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->with(['students', 'modules', 'classes'])->get();
        } elseif (auth()->user()->hasRole('Monitor')) {
            $courses = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('monitors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->with(['students', 'modules', 'classes'])->get();
        } else {
            $courses = new Course;
        }

        $modules = new Collection;
        $classes = new Collection;

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                foreach ($course->modules as $module) {
                    $modules[] = $module;
                }
            }

            foreach ($courses as $course) {
                foreach ($course->classes as $classe) {
                    $classes[] = $classe;
                }
            }
        }

        $posts = Blog::select('id', 'status', 'title', 'views', 'created_at')->orderBy('created_at', 'desc')->get();
        $cheats = CheatSheet::select('id', 'status', 'title', 'views', 'created_at')->orderBy('created_at', 'desc')->get();

        $visits = Visit::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            ->where('url', 'NOT LIKE', '%admin%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->where('url', 'NOT LIKE', '%serviceworker.js%')
            ->where('url', 'NOT LIKE', '%installHook.js.map%')
            ->get(['id', 'created_at', 'url', 'ip', 'method', 'languages', 'useragent', 'platform', 'browser', 'name']);

        if ($request->ajax()) {
            return DataTables::of($visits)
                ->addColumn('time', function ($row) {
                    return date(('H:i:s'), strtotime($row->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['time'])
                ->make(true);
        }

        $postsChart = ['label' => [], 'data' => []];
        foreach ($posts->sortBy('views')->reverse()->take(10) as $p) {
            $postsChart['label'][] = Str::limit($p->title, 25);
            $postsChart['data'][] = (int) $p->views;
        }

        $cheatsChart = ['label' => [], 'data' => []];
        foreach ($cheats->sortBy('views')->reverse()->take(10) as $p) {
            $cheatsChart['label'][] = Str::limit($p->title, 25);
            $cheatsChart['data'][] = (int) $p->views;
        }

        $studentCourseChart = ['label' => [], 'data' => []];
        if ($courses->count() === 0) {
            $studentCourseChart['label'][] = 'Nenhum curso encontrado';
            $studentCourseChart['data'][] = 0;
        } else {
            foreach ($courses as $course) {
                $studentCourseChart['label'][] = Str::limit($course->name, 25);
                $studentCourseChart['data'][] = (int) $course->students->count();
            }
        }

        /** Statistics */
        $statistics = $this->accessStatistics();
        $onlineUsers = $statistics['onlineUsers'];
        $percent = $statistics['percent'];
        $access = $statistics['access'];
        $chart = $statistics['chart'];

        return view('admin.home.index', compact(
            'administrators',
            'instructors',
            'courses',
            'monitors',
            'students',
            'onlineUsers',
            'percent',
            'access',
            'chart',
            'posts',
            'postsChart',
            'cheats',
            'cheatsChart',
            'studentCourseChart',
            'modules',
            'classes',
        ));
    }

    public function chart(): JsonResponse
    {
        /** Statistics */
        $statistics = $this->accessStatistics();
        $onlineUsers = $statistics['onlineUsers'];
        $percent = $statistics['percent'];
        $access = $statistics['access'];
        $chart = $statistics['chart'];

        return response()->json([
            'onlineUsers' => $onlineUsers,
            'access' => $access,
            'percent' => $percent,
            'chart' => $chart,
        ]);
    }

    private function accessStatistics(): array
    {
        $onlineUsers = User::online()->count();

        $accessToday = Visit::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            ->where('url', 'NOT LIKE', '%admin%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->where('url', 'NOT LIKE', '%serviceworker.js%')
            ->where('url', 'NOT LIKE', '%installHook.js.map%')
            ->where('method', 'GET')
            ->get();
        $accessYesterday = VisitYesterday::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            ->where('url', 'NOT LIKE', '%admin%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->where('url', 'NOT LIKE', '%serviceworker.js%')
            ->where('url', 'NOT LIKE', '%installHook.js.map%')
            ->where('method', 'GET')
            ->count();

        $totalDaily = $accessToday->count();

        $percent = 0;
        if ($accessYesterday > 0 && $totalDaily > 0) {
            $percent = number_format((($totalDaily - $accessYesterday) / $totalDaily * 100), 2, ',', '.');
        }

        /** Visitor Chart */
        $data = $accessToday->groupBy(function ($reg) {
            return date('H', strtotime($reg->created_at));
        });

        $dataList = [];
        foreach ($data as $key => $value) {
            $dataList[$key.'H'] = count($value);
        }

        $chart = new stdClass;
        $chart->labels = (array_keys($dataList));
        $chart->dataset = (array_values($dataList));

        return [
            'onlineUsers' => $onlineUsers,
            'access' => $totalDaily,
            'percent' => $percent,
            'chart' => $chart,
        ];
    }
}
