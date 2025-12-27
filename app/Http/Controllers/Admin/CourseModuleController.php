<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Helpers\TextProcessor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseModuleRequest;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\CourseModule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CourseModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Módulos de Cursos');

        if ($request->ajax()) {
            if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
                $modules = CourseModule::with(['course', 'classes'])->orderBy('course_id')->orderBy('order')->get();
            } elseif (auth()->user()->hasRole('Instrutor')) {
                $modules = CourseModule::where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('course', function ($q) {
                            $q->whereHas('instructors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        });
                })->with(['course', 'classes'])->orderBy('course_id')->orderBy('order')->get();
            } elseif (auth()->user()->hasRole('Monitor')) {
                $modules = CourseModule::where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('course', function ($q) {
                            $q->whereHas('monitors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        });
                })->with(['course', 'classes'])->orderBy('course_id')->orderBy('order')->get();
            } else {
                $modules = new CourseModule;
            }

            $token = csrf_token();

            try {
                return DataTables::of($modules)
                    ->addIndexColumn()
                    ->addColumn('cover', function ($row) {
                        return '<div class="d-flex justify-content-center align-items-center"><img src="'.($row->cover ? url('storage/course-modules/min/'.$row->cover) : asset('img/defaults/min/courses.webp')).'" class="img-thumbnail d-block" width="360" height="207" alt="'.$row->name.'" title="'.$row->name.'"/></div>';
                    })
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
                            $classes_link = '<a class="btn btn-xs btn-warning mx-1 shadow" title="Aulas" href="'.route('admin.course-modules.classes', ['module' => $row->id]).'"><i class="fa fa-lg fa-fw fa-chalkboard-teacher"></i></a>';
                        } else {
                            $classes_link = '';
                        }
                        if (Auth::user()->hasPermissionTo('Editar Módulos de Cursos') && Auth::user()->hasPermissionTo('Excluir Módulos de Cursos')) {
                            $edit = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.course-modules.edit', ['course_module' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                            $delete = '<form method="POST" action="'.route('admin.course-modules.destroy', ['course_module' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta aula?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                        } else {
                            $edit = '';
                            $delete = '';
                        }

                        return '<div class="d-flex justify-content-center align-items-center">'.$link.$classes_link.$edit.$delete.'</div>';
                    })
                    ->rawColumns(['cover', 'course', 'classes', 'active', 'action'])
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

        return view('admin.course-modules.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Módulos de Cursos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $courses = Course::orderBy('name')->get();
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $courses = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('instructors', function ($q) {
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

        return view('admin.course-modules.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseModuleRequest $request)
    {
        CheckPermission::checkAuth('Criar Módulos de Cursos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $course = Course::find($request->course_id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('instructors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($request->course_id);
        } else {
            $course = null;
        }

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $course) {
            return redirect()
                ->back()
                ->with('error', 'Nenhum curso encontrado!');
        }

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)).time();
            $extension = $request->cover->extension();
            $nameFile = "{$name}.{$extension}";

            $data['cover'] = $nameFile;

            $destinationPath = storage_path().'/app/public/course-modules';
            $destinationPathMedium = storage_path().'/app/public/course-modules/medium';
            $destinationPathMin = storage_path().'/app/public/course-modules/min';
            $destinationPathIcon = storage_path().'/app/public/course-modules/icon';

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

            $imgIcon = Image::make($request->cover)->resize(null, 65, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(65, 65)->save($destinationPathIcon.'/'.$nameFile);

            if (! $img && ! $imgMedium && ! $imgMin && ! $imgIcon) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Falha ao fazer o upload da imagem');
            }
        }

        // Upload do PDF
        if ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {
            $pdfName = Str::slug(mb_substr($data['name'], 0, 100)).'-'.time();
            $extension = $request->pdf_file->extension();
            $pdfFileName = "{$pdfName}.{$extension}";

            $data['pdf_file'] = $pdfFileName;

            $destinationPath = storage_path('app/private/pdfs/modules');

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $request->pdf_file->move($destinationPath, $pdfFileName);
        }

        if ($request->description) {
            $data['description'] = TextProcessor::store($request->name, 'course-modules/description', $request->description);
        }

        $data['course_id'] = $course->id;
        $data['user_id'] = auth()->user()->id;

        $module = CourseModule::create($data);

        if ($module->save()) {
            return redirect()
                ->route('admin.course-modules.index')
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
        CheckPermission::checkAuth(auth: 'Editar Módulos de Cursos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $module = CourseModule::find($id);
            $courses = Course::orderBy('name')->get();
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $courses = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('instructors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->orderBy('name')->get();
            $module = CourseModule::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('course', function ($q) {
                        $q->whereHas('instructors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                    });
            })->find($id);
        } else {
            $module = null;
        }

        if (! $courses || ! $module) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $courses->count()) {
            return redirect()
                ->back()
                ->with('error', 'Nenhum curso encontrado!');
        }

        return view('admin.course-modules.edit', compact('module', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseModuleRequest $request, string $id)
    {
        CheckPermission::checkAuth(auth: 'Editar Módulos de Cursos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $module = CourseModule::find($id);
            $course = Course::find($request->course_id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $course = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('instructors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->find($request->course_id);
            $module = CourseModule::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('course', function ($q) {
                        $q->whereHas('instructors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                    });
            })->find($id);
        } else {
            $module = null;
            $course = null;
        }

        if (! $course || ! $module) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)).time();
            $extension = $request->cover->extension();
            $nameFile = "{$name}.{$extension}";

            $data['cover'] = $nameFile;

            $destinationPath = storage_path().'/app/public/course-modules';
            $destinationPathMedium = storage_path().'/app/public/course-modules/medium';
            $destinationPathMin = storage_path().'/app/public/course-modules/min';
            $destinationPathIcon = storage_path().'/app/public/course-modules/icon';

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

            $imgIcon = Image::make($request->cover)->resize(null, 65, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(65, 65)->save($destinationPathIcon.'/'.$nameFile);

            if (! $img && ! $imgMedium && ! $imgMin && ! $imgIcon) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Falha ao fazer o upload da imagem');
            }
        }

        // Verifica se deve remover o PDF
        if ($request->input('remove_pdf') == '1') {
            if ($module->pdf_file) {
                $oldPdfPath = storage_path('app/private/pdfs/modules/'.$module->pdf_file);
                if (File::isFile($oldPdfPath)) {
                    unlink($oldPdfPath);
                }
            }
            $data['pdf_file'] = null;
        }
        // Upload do PDF se fornecido
        elseif ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {
            // Remove PDF antigo se existir
            if ($module->pdf_file) {
                $oldPdfPath = storage_path('app/private/pdfs/modules/'.$module->pdf_file);
                if (File::isFile($oldPdfPath)) {
                    unlink($oldPdfPath);
                }
            }

            $pdfName = Str::slug(mb_substr($data['name'], 0, 100)).'-'.time();
            $extension = $request->pdf_file->extension();
            $pdfFileName = "{$pdfName}.{$extension}";

            $data['pdf_file'] = $pdfFileName;

            $destinationPath = storage_path('app/private/pdfs/modules');

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $request->pdf_file->move($destinationPath, $pdfFileName);
        }

        if ($request->description) {
            $data['description'] = TextProcessor::store($request->name, 'course-modules/description', $request->description);
        }

        $data['course_id'] = $course->id;
        $data['user_id'] = auth()->user()->id;

        if ($module->update($data)) {

            return redirect()
                ->route('admin.course-modules.index')
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
        CheckPermission::checkAuth(auth: 'Excluir Módulos de Cursos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $module = CourseModule::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $module = Classroom::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('course', function ($q) {
                        $q->whereHas('instructors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                    });
            })->find($id);
        } else {
            $module = null;
        }

        if (! $module) {
            abort(403, 'Acesso não autorizado');
        }

        if ($module->delete()) {
            $imagePath = storage_path().'/app/public/course-modules/'.$module->cover;
            $imagePathMedium = storage_path().'/app/public/course-modules/medium/'.$module->cover;
            $imagePathMin = storage_path().'/app/public/course-modules/min/'.$module->cover;
            $imagePathIcon = storage_path().'/app/public/course-modules/icon/'.$module->cover;

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

            $module->cover = null;
            $module->update();

            return redirect()
                ->route('admin.course-modules.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }

    public function classes(Request $request, $id)
    {
        CheckPermission::checkAuth('Listar Aulas');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $module = CourseModule::find($id);
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $module = Classroom::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('course', function ($q) {
                        $q->whereHas('instructors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                    });
            })->find($id);
        } elseif (auth()->user()->hasRole('Monitor')) {
            $module = Classroom::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('course', function ($q) {
                        $q->whereHas('monitors', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                    });
            })->find($id);
        } else {
            $module = null;
        }

        if (! $module) {
            abort(403, 'Acesso não autorizado');
        }

        if ($request->ajax()) {
            if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
                $classes = Classroom::where('course_module_id', $module->id)->get();
            } elseif (auth()->user()->hasRole('Instrutor')) {
                $classes = Classroom::where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('course', function ($q) {
                            $q->whereHas('instructors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        });
                })->where('course_module_id', $module->id)->get();
            } elseif (auth()->user()->hasRole('Monitor')) {
                $classes = Classroom::where(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('course', function ($q) {
                            $q->whereHas('monitors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                        });
                })->where('course_module_id', $module->id)->get();
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
                        if (Auth::user()->hasPermissionTo('Editar Aulas') && Auth::user()->hasPermissionTo('Excluir Aulas')) {
                            $edit = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.classes.edit', ['class' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                            $delete = '<form method="POST" action="'.route('admin.classes.destroy', ['class' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta aula?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                        } else {
                            $edit = '';
                            $delete = '';
                        }

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

        return view('admin.course-modules.classes', compact('module'));
    }
}
