<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassroomRequest;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\CourseModule;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Aulas');

        if ($request->ajax()) {
            if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
                $classes = Classroom::with(['course', 'module'])->get();
            } elseif (auth()->user()->hasRole('Instrutor')) {
                $classes = Classroom::where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('course', function ($q) {
                            $q->whereHas('authors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        });
                })->with(['course', 'module'])->get();
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
                    ->addColumn('module', function ($row) {
                        return $row->module->name;
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
                    ->rawColumns(['course', 'module', 'active', 'action'])
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

        return view('admin.classroom.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Aulas');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $courses = Course::orderBy('name')->get();
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $courses = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->orderBy('name')->get();
        } else {
            $courses = null;
        }

        if (! $courses) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $courses->count()) {
            return redirect()
                ->back()
                ->with('error', 'Nenhum curso encontrado!');
        }

        $modules = CourseModule::whereIn('course_id', $courses->pluck('id'))->orderBy('course_id')->orderBy('order')->get();

        if (! $modules->count()) {
            return redirect()
                ->back()
                ->with('error', 'Nenhum módulo encontrado!');
        }

        return view('admin.classroom.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassroomRequest $request)
    {
        CheckPermission::checkAuth('Criar Aulas');

        $module = CourseModule::where('id', $request->course_module_id)->first();

        if (! $module) {
            abort(403, 'Acesso não autorizado');
        }

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $course = Course::find($module->course_id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($module->course_id);
        } else {
            $course = null;
        }

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        $data['course_id'] = $course->id;
        $data['course_module_id'] = $module->id;
        $data['user_id'] = auth()->user()->id;

        $course = Classroom::create($data);

        if ($course->save()) {
            return redirect()
                ->route('admin.classes.index')
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
        CheckPermission::checkAuth(auth: 'Editar Aulas');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $classroom = Classroom::find($id);
            $courses = Course::orderBy('name')->get();
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $courses = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->orderBy('name')->get();
            $classroom = Classroom::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('course', function ($q) {
                        $q->whereHas('authors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                    });
            })->find($id);
        } else {
            $courses = null;
        }

        if (! $courses || ! $classroom) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $courses->count()) {
            return redirect()
                ->back()
                ->with('error', 'Nenhum curso encontrado!');
        }

        $modules = CourseModule::whereIn('course_id', $courses->pluck('id'))->orderBy('course_id')->orderBy('order')->get();

        if (! $modules->count()) {
            return redirect()
                ->back()
                ->with('error', 'Nenhum módulo encontrado!');
        }

        return view('admin.classroom.edit', compact('classroom', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassroomRequest $request, string $id)
    {
        CheckPermission::checkAuth(auth: 'Editar Aulas');

        $module = CourseModule::where('id', $request->course_module_id)->first();

        if (! $module) {
            abort(403, 'Acesso não autorizado');
        }

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $classroom = Classroom::find($id);
            $course = Course::find($module->course_id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('authors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($module->course_id);
            $classroom = Classroom::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('course', function ($q) {
                        $q->whereHas('authors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                    });
            })->find($id);
        } else {
            $classroom = null;
            $course = null;
        }

        if (! $course || ! $classroom) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();
        $data['course_id'] = $course->id;
        $data['course_module_id'] = $module->id;
        $data['user_id'] = auth()->user()->id;

        if ($classroom->update($data)) {

            return redirect()
                ->route('admin.classes.index')
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
        CheckPermission::checkAuth(auth: 'Excluir Aulas');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $classroom = Classroom::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $classroom = Classroom::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('course', function ($q) {
                        $q->whereHas('authors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                    });
            })->find($id);
        } else {
            $classroom = null;
        }

        if (! $classroom) {
            abort(403, 'Acesso não autorizado');
        }

        if ($classroom->delete()) {
            return redirect()
                ->route('admin.classes.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
