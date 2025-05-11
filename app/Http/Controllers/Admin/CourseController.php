<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Helpers\TextProcessor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\CategoryCourse;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\CourseAuthor;
use App\Models\CourseCategoryPivot;
use App\Models\CourseModule;
use App\Models\CourseStudent;
use App\Models\Views\CategoryCourse as ViewsCategoryCourse;
use App\Models\Views\Student;
use App\Models\Views\User as ViewsUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Cursos');

        if ($request->ajax()) {
            if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
                $courses = Course::with(['classes', 'authors', 'modules'])->get();
            } elseif (auth()->user()->hasRole('Instrutor')) {
                $courses = Course::where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('authors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                })->with(['classes', 'authors', 'modules'])->get();
            } else {
                $courses = new Course;
            }

            $token = csrf_token();

            try {
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
                    ->addColumn('students', function ($row) {
                        return $row->students->count();
                    })
                    ->addColumn('active', function ($row) {
                        if ($row->active == 0) {
                            return '<span class="text-danger"><i class="fa fa-lg fa-fw fa-thumbs-down"></i></span>';
                        } else {
                            return '<span class="text-success"><i class="fa fa-lg fa-fw fa-thumbs-up"></i></span>';
                        }
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        if ($row->sales_link) {
                            $sales_link = '<a class="btn btn-xs btn-success mx-1 shadow" title="Link de vendas" href="'.$row->sales_link.'" target="_blank"><i class="fa fa-lg fa-fw fa-dollar-sign"></i></a>';
                        } else {
                            $sales_link = '';
                        }
                        if ($row->modules->count() > 0) {
                            $modules_link = '<a class="btn btn-xs btn-info mx-1 shadow" title="Módulos" href="courses/'.$row->id.'/modules"><i class="fa fa-lg fa-fw fa-layer-group"></i></a>';
                        } else {
                            $modules_link = '';
                        }
                        if ($row->classes->count() > 0) {
                            $classes_link = '<a class="btn btn-xs btn-warning mx-1 shadow" title="Aulas" href="courses/'.$row->id.'/classes"><i class="fa fa-lg fa-fw fa-chalkboard-teacher"></i></a>';
                        } else {
                            $classes_link = '';
                        }
                        if ($row->students->count() > 0) {
                            $students = '<a class="btn btn-xs brn-light mx-1 shadow" title="Alunos" href="'.route('admin.courses.students', ['course' => $row->id]).'"><i class="fa fa-lg fa-fw fa-graduation-cap"></i></a>';
                        } else {
                            $students = '';
                        }
                        $edit = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.courses.edit', ['course' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                        $delete = '<form method="POST" action="'.route('admin.courses.destroy', ['course' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste curso?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';

                        return '<div class="d-flex justify-content-center align-items-center">'.$sales_link.$students.$modules_link.$classes_link.$edit.$delete.'</div>';
                    })
                    ->rawColumns(['cover', 'categories', 'modules', 'authors', 'students', 'active', 'action'])
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

        return view('admin.courses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Cursos');

        $categories = ViewsCategoryCourse::orderBy('name')->get();

        $authors = ViewsUser::whereNotIn('type', ['Programador', 'Aluno'])->orderBy('name')->get();

        return view('admin.courses.create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        CheckPermission::checkAuth('Criar Cursos');

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)).time();
            $extension = $request->cover->extension();
            $nameFile = "{$name}.{$extension}";

            $data['cover'] = $nameFile;

            $destinationPath = storage_path().'/app/public/courses';
            $destinationPathMedium = storage_path().'/app/public/courses/medium';
            $destinationPathMin = storage_path().'/app/public/courses/min';
            $destinationPathIcon = storage_path().'/app/public/courses/icon/';

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 755, true);
            }

            if (! file_exists($destinationPathMedium)) {
                mkdir($destinationPathMedium, 755, true);
            }

            if (! file_exists($destinationPathMin)) {
                mkdir($destinationPathMin, 755, true);
            }

            if (! file_exists($destinationPathIcon)) {
                mkdir($destinationPathIcon, 755, true);
            }

            $img = Image::make($request->cover)->resize(null, 490, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(860, 490)->save($destinationPath.'/'.$nameFile);

            $imgMedium = Image::make($request->cover)->resize(null, 385, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(675, 385)->save($destinationPathMedium.'/'.$nameFile);

            $imgMin = Image::make($request->cover)->resize(null, 207, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(360, 207)->save($destinationPathMin.'/'.$nameFile);

            $icon = Image::make($request->cover)->resize(null, 65, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(65, 65)->save($destinationPathIcon.'/'.$nameFile);

            if (! $img && ! $imgMedium && ! $imgMin && ! $icon) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Falha ao fazer o upload da imagem');
            }
        }

        if ($request->description) {
            $data['description'] = TextProcessor::store($request->name, 'courses/description', $request->description);
        }

        $data['user_id'] = auth()->user()->id;

        $course = Course::create($data);

        if ($course->save()) {
            $categories = $request->categories;
            if ($categories && count($categories) > 0) {
                $categories = CategoryCourse::whereIn('id', $categories)->pluck('id');
                foreach ($categories as $category) {
                    $pivot = new CourseCategoryPivot;
                    $pivot->create([
                        'course_id' => $course->id,
                        'category_course_id' => $category,
                    ]);
                }
            }

            $authors = $request->authors;
            if ($authors && count($authors) > 0) {
                $users = ViewsUser::whereIn('id', $authors)->whereNotIn('type', ['Programador', 'Aluno'])->pluck('id');
                foreach ($users as $user) {
                    $pivot = new CourseAuthor;
                    $pivot->create([
                        'course_id' => $course->id,
                        'user_id' => $user,
                    ]);
                }
            }

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Cadastro realizado!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        CheckPermission::checkAuth(auth: 'Editar Cursos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $course = Course::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($id);
        } else {
            $course = null;
        }

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        $categories = ViewsCategoryCourse::orderBy('name')->get();

        $authors = ViewsUser::whereNotIn('type', ['Programador', 'Aluno'])->orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'categories', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, string $id)
    {
        CheckPermission::checkAuth(auth: 'Editar Cursos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $course = Course::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($id);
        } else {
            $course = null;
        }

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)).time();
            $imagePath = storage_path().'/app/public/courses/'.$course->cover;
            $imagePathMedium = storage_path().'/app/public/courses/medium/'.$course->cover;
            $imagePathMin = storage_path().'/app/public/courses/min/'.$course->cover;

            if (File::isFile($imagePath)) {
                unlink($imagePath);
            }

            if (File::isFile($imagePathMedium)) {
                unlink($imagePathMedium);
            }

            if (File::isFile($imagePathMin)) {
                unlink($imagePathMin);
            }

            $extension = $request->cover->extension();
            $nameFile = "{$name}.{$extension}";

            $data['cover'] = $nameFile;

            $destinationPath = storage_path().'/app/public/courses';
            $destinationPathMedium = storage_path().'/app/public/courses/medium';
            $destinationPathMin = storage_path().'/app/public/courses/min';
            $destinationPathIcon = storage_path().'/app/public/courses/icon/';

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 755, true);
            }

            if (! file_exists($destinationPathMedium)) {
                mkdir($destinationPathMedium, 755, true);
            }

            if (! file_exists($destinationPathMin)) {
                mkdir($destinationPathMin, 755, true);
            }

             if (! file_exists($destinationPathIcon)) {
                mkdir($destinationPathIcon, 755, true);
            }


            $img = Image::make($request->cover)->resize(null, 490, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(860, 490)->save($destinationPath.'/'.$nameFile);

            $imgMedium = Image::make($request->cover)->resize(null, 385, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(675, 385)->save($destinationPathMedium.'/'.$nameFile);

            $imgMin = Image::make($request->cover)->resize(null, 207, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(360, 207)->save($destinationPathMin.'/'.$nameFile);

            $icon = Image::make($request->cover)->resize(null, 65, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(65, 65)->save($destinationPathIcon.'/'.$nameFile);

            if (! $img && ! $imgMedium && ! $imgMin && ! $icon) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Falha ao fazer o upload da imagem');
            }
        }

        if ($request->description) {
            $data['description'] = TextProcessor::store($request->name, 'courses/description', $request->description);
        }

        $data['user_id'] = auth()->user()->id;

        if ($course->update($data)) {
            CourseCategoryPivot::where('course_id', $course->id)->delete();

            $categories = $request->categories;
            if ($categories && count($categories) > 0) {
                $categories = CategoryCourse::whereIn('id', $categories)->pluck('id');
                foreach ($categories as $category) {
                    $pivot = new CourseCategoryPivot;
                    $pivot->firstOrCreate([
                        'course_id' => $course->id,
                        'category_course_id' => $category,
                    ]);
                }
            }

            CourseAuthor::where('course_id', $course->id)->delete();

            $authors = $request->authors;
            if ($authors && count($authors) > 0) {
                $users = ViewsUser::whereIn('id', $authors)->whereNotIn('type', ['Programador', 'Aluno'])->pluck('id');
                foreach ($users as $user) {
                    $pivot = new CourseAuthor;
                    $pivot->firstOrCreate([
                        'course_id' => $course->id,
                        'user_id' => $user,
                    ]);
                }
            }

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CheckPermission::checkAuth(auth: 'Excluir Cursos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $course = Course::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($id);
        } else {
            $course = null;
        }

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        if ($course->delete()) {
            $imagePath = storage_path().'/app/public/courses/'.$course->cover;
            $imagePathMedium = storage_path().'/app/public/courses/medium/'.$course->cover;
            $imagePathMin = storage_path().'/app/public/courses/min/'.$course->cover;
            $imagePathIcon= storage_path().'/app/public/courses/icon/'.$course->cover;

            if (File::isFile($imagePath)) {
                unlink($imagePath);
            }

            if (File::isFile($imagePathMedium)) {
                unlink($imagePathMedium);
            }

            if (File::isFile($imagePathMin)) {
                unlink($imagePathMin);
            }

            if (File::isFile($imagePathIcon)) {
                unlink($imagePathIcon);
            }

            $course->cover = null;
            $course->update();

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }

    public function modules(Request $request, $id)
    {
        CheckPermission::checkAuth('Listar Aulas');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $course = Course::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($id);
        } else {
            $course = null;
        }

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        if ($request->ajax()) {
            if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
                $modules = CourseModule::where('course_id', $course->id)->get();
            } elseif (auth()->user()->hasRole('Instrutor')) {
                $modules = CourseModule::where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('course', function ($q) {
                            $q->whereHas('authors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        });
                })->where('course_id', $course->id)->get();
            } else {
                $modules = new CourseModule;
            }

            $token = csrf_token();

            try {
                return DataTables::of($modules)
                    ->addIndexColumn()
                    ->addColumn('course', function ($row) {
                        return $row->course->name;
                    })
                    ->addColumn('course', function ($row) {
                        return $row->course->name;
                    })
                    ->addColumn('classes', function ($row) {
                        return $row->classes->count();
                    })
                    ->addColumn('active', function ($row) {
                        if ($row->active == 0) {
                            return '<span class="text-danger"><i class="fa fa-lg fa-fw fa-thumbs-down"></i></span>';
                        } else {
                            return '<span class="text-success"><i class="fa fa-lg fa-fw fa-thumbs-up"></i></span>';
                        }
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        if ($row->link) {
                            $link = '<a class="btn btn-xs btn-success mx-1 shadow" title="Link da aula" href="'.$row->link.'" target="_blank"><i class="fa fa-lg fa-fw fa-link"></i></a>';
                        } else {
                            $link = '';
                        }
                        if ($row->classes->count() > 0) {
                            $classes_link = '<a class="btn btn-xs btn-warning mx-1 shadow" title="Aulas" href="courses/'.$row->id.'/classes"><i class="fa fa-lg fa-fw fa-chalkboard-teacher"></i></a>';
                        } else {
                            $classes_link = '';
                        }
                        $edit = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.classes.edit', ['class' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                        $delete = '<form method="POST" action="'.route('admin.classes.destroy', ['class' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta aula?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';

                        return '<div class="d-flex justify-content-center align-items-center">'.$link.$classes_link.$edit.$delete.'</div>';
                    })
                    ->rawColumns(['course', 'classes', 'active', 'action'])
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

        return view('admin.courses.modules', compact('course'));
    }

    public function classes(Request $request, $id)
    {
        CheckPermission::checkAuth('Listar Aulas');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $course = Course::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($id);
        } else {
            $course = null;
        }

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        if ($request->ajax()) {
            if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
                $classes = Classroom::where('course_id', $course->id)->get();
            } elseif (auth()->user()->hasRole('Instrutor')) {
                $classes = Classroom::where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('course', function ($q) {
                            $q->whereHas('authors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        });
                })->where('course_id', $course->id)->get();
            } else {
                $classes = new Classroom;
            }

            $token = csrf_token();

            try {
                return DataTables::of($classes)
                    ->addIndexColumn()
                    ->addColumn('course', function ($row) {
                        return $row->course->name;
                    })
                    ->addColumn('active', function ($row) {
                        if ($row->active == 0) {
                            return '<span class="text-danger"><i class="fa fa-lg fa-fw fa-thumbs-down"></i></span>';
                        } else {
                            return '<span class="text-success"><i class="fa fa-lg fa-fw fa-thumbs-up"></i></span>';
                        }
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        if ($row->link) {
                            $link = '<a class="btn btn-xs btn-success mx-1 shadow" title="Link da aula" href="'.$row->link.'" target="_blank"><i class="fa fa-lg fa-fw fa-link"></i></a>';
                        } else {
                            $link = '';
                        }
                        $edit = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.classes.edit', ['class' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                        $delete = '<form method="POST" action="'.route('admin.classes.destroy', ['class' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta aula?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';

                        return '<div class="d-flex justify-content-center align-items-center">'.$link.$edit.$delete.'</div>';
                    })
                    ->rawColumns(['course', 'active', 'action'])
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

        return view('admin.courses.classes', compact('course'));
    }

    public function students(Request $request, $id)
    {

        CheckPermission::checkAuth('Listar Alunos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $course = Course::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($id);
        } else {
            $course = null;
        }

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        $pivot = CourseStudent::where('course_id', $course->id)->get();

        if ($request->ajax()) {

            $students = Student::whereIn('id', $pivot->pluck('user_id'))->get(['id', 'name', 'email', 'type', 'photo']);

            $token = csrf_token();

            return DataTables::of($students)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-success mx-1 shadow" title="Visualizar" href="'.route('admin.students.show', ['student' => $row->id]).'"><i class="fa fa-lg fa-fw fa-eye"></i></a>'.
                        (Auth::user()->hasPermissionTo('Editar Alunos') ? '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.students.edit', ['student' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>' : '').
                        (Auth::user()->hasPermissionTo('Excluir Alunos') ? '<form method="POST" action="'.route('admin.students.destroy', ['student' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste usuário?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>' : '');
                })
                ->addColumn('photo', function ($row) {
                    return '<img src="'.($row->photo ? url('storage/users/'.$row->photo) : asset('vendor/adminlte/dist/img/avatar.png')).'"
                    alt="'.$row->name.'" class="img-circle img-size-32 mr-2 border" style="object-fit: cover; width:75px; height: 75px; aspect-ratio: 1;">';
                })
                ->rawColumns(['action', 'photo'])
                ->make(true);
        }

        return view('admin.courses.students', compact('course'));
    }
}
