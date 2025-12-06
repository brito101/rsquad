<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\User;
use App\Models\Views\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Alunos');

        if ($request->ajax()) {
            $users = Student::get(['id', 'name', 'email', 'photo']);

            $token = csrf_token();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('courses', function ($row) {
                    $pivot = CourseStudent::where('user_id', $row->id)->get();

                    return $pivot->map(function ($pivot) {
                        return $pivot->course->name;
                    })->implode(' - ');
                })
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-success mx-1 shadow" title="Visualizar" href="'.route('admin.students.show', ['student' => $row->id]).'"><i class="fa fa-lg fa-fw fa-eye"></i></a>'.
                        (Auth::user()->hasPermissionTo('Editar Alunos') ? '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.students.edit', ['student' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>' : '').
                        (Auth::user()->hasPermissionTo('Excluir Alunos') ? '<form method="POST" action="'.route('admin.students.destroy', ['student' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste usuário?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>' : '');
                })
                ->addColumn('photo', function ($row) {
                    return '<img src="'.($row->photo ? url('storage/users/'.$row->photo) : asset('vendor/adminlte/dist/img/avatar.png')).'"
                    alt="'.$row->name.'" class="img-circle img-size-32 mr-2 border" style="object-fit: cover; width:75px; height: 75px; aspect-ratio: 1;">';
                })
                ->rawColumns(['courses', 'action', 'photo'])
                ->make(true);
        }

        return view('admin.students.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Alunos');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $courses = Course::where('active', true)->get();
        } elseif (auth()->user()->hasRole('Instrutor')) {
            $courses = Course::where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhereHas('instructors', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    });
            })->where('active', true)->get();
        } else {
            $courses = new Course;
        }

        return view('admin.students.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        CheckPermission::checkAuth('Criar Alunos');

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)).time();
            $data = $this->saveImage($request, $name, $data);
        }

        $user = User::create($data);

        if ($user->save()) {
            $user->syncRoles('Aluno');
            $user->save();

            $coursesRequest = $request->courses;
            if ($coursesRequest && count($coursesRequest) > 0) {
                if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
                    $courses = Course::whereIn('id', $coursesRequest)->where('active', true)->get();
                } elseif (auth()->user()->hasRole('Instrutor')) {
                    $courses = Course::where(function ($query) {
                        $query->where('user_id', auth()->user()->id)
                            ->orWhereHas('instructors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                    })->whereIn('id', $coursesRequest)->where('active', true)->get();
                } else {
                    $courses = [];
                }
                foreach ($courses as $course) {
                    $pivot = new CourseStudent;
                    $pivot->create([
                        'course_id' => $course->id,
                        'user_id' => $user->id,
                    ]);
                }
            }

            return redirect()
                ->route('admin.students.index')
                ->with('success', 'Cadastro realizado!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        CheckPermission::checkAuth('Listar Alunos');

        $user = Student::find($id);

        if (! $user) {
            abort(403, 'Acesso não autorizado');
        }

        $pivot = CourseStudent::with('course')->where('user_id', $id)->get();

        $courses = Course::whereIn('id', $pivot->pluck('course_id'))->where('active', true)->get();

        return view('admin.students.show', compact('user', 'courses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        CheckPermission::checkAuth('Editar Alunos');

        $user = Student::find($id);

        if (! $user) {
            abort(403, 'Acesso não autorizado');
        }

        $pivot = CourseStudent::with('course')->where('user_id', $id)->get();

        $courses = Course::where('active', true)->get();

        if (Auth::user()->hasRole('Programador')) {
            $roles = Role::all(['id', 'name']);
        } else {
            $roles = Role::whereNotIn('name', ['Programador'])->get(['id', 'name']);
        }

        return view('admin.students.edit', compact('user', 'pivot', 'courses', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $id): RedirectResponse
    {
        CheckPermission::checkAuth('Editar Alunos');

        $data = $request->all();

        $student = Student::find($id);

        $user = User::where('id', $student->id)->first();

        if (! $user) {
            abort(403, 'Acesso não autorizado');
        }

        if ($request->google2fa_secret_enabled && $user->google2fa_secret == null) {
            $data['google2fa_secret'] = $user->generateSecretKey();
        }

        if (! $request->google2fa_secret_enabled) {
            $data['google2fa_secret'] = null;
        }

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $user->password;
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 200)).'-'.time();
            $imagePath = storage_path().'/app/public/users/'.$user->photo;

            if (File::isFile($imagePath)) {
                unlink($imagePath);
            }

            $data = $this->saveImage($request, $name, $data);
        }

        if ($user->update($data)) {
            $coursesRequest = $request->courses;
            if ($coursesRequest && count($coursesRequest) > 0) {
                CourseStudent::where('user_id', $user->id)->delete();

                if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
                    $courses = Course::whereIn('id', $coursesRequest)->where('active', true)->get();
                } elseif (auth()->user()->hasRole('Instrutor')) {
                    $courses = Course::where(function ($query) {
                        $query->where('user_id', auth()->user()->id)
                            ->orWhereHas('instructors', function ($q) {
                                $q->where('user_id', auth()->user()->id);
                            });
                    })->whereIn('id', $coursesRequest)->where('active', true)->get();
                } else {
                    $courses = [];
                }
                foreach ($courses as $course) {
                    $pivot = new CourseStudent;
                    $pivot->create([
                        'course_id' => $course->id,
                        'user_id' => $user->id,
                    ]);
                }
            }

            if (! empty($request->role) && Auth::user()->hasPermissionTo('Atribuir Perfis')) {
                if (Auth::user()->hasRole('Programador')) {
                    $user->syncRoles($request->role);
                } elseif ($request->role != 'Programador') {
                    $user->syncRoles($request->role);
                }
            }

            return redirect()
                ->route('admin.students.index')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CheckPermission::checkAuth('Excluir Alunos');

        $student = Student::find($id);

        $user = User::where('id', $student->id)->first();

        if (! $user) {
            abort(403, 'Acesso não autorizado');
        }

        $imagePath = storage_path().'/app/public/users/'.$user->photo;
        if ($user->delete()) {
            if (File::isFile($imagePath)) {
                unlink($imagePath);
                $user->photo = null;
                $user->update();
            }

            CourseStudent::where('user_id', $user->id)->delete();

            return redirect()
                ->route('admin.students.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }

    private function saveImage(UserRequest $request, string $name, array $data): array
    {
        $extension = $request->photo->extension();
        $nameFile = "$name.$extension";

        $data['photo'] = $nameFile;

        $destinationPath = storage_path().'/app/public/users';

        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        $img = Image::make($request->photo);
        $img->resize(null, 100, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->crop(100, 100)->save($destinationPath.'/'.$nameFile);

        return $data;
    }
}
