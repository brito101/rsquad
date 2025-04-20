<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Helpers\TextProcessor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\CategoryCourse;
use App\Models\Course;
use App\Models\CourseAuthor;
use App\Models\CourseCategoryPivot;
use App\Models\User;
use App\Models\Views\CategoryCourse as ViewsCategoryCourse;
use App\Models\Views\User as ViewsUser;
use Dom\Text;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Image;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Cursos');

        if ($request->ajax()) {
            $courses = Course::all();

            $token = csrf_token();

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
                ->addColumn('authors', function ($row) {
                    return $row->authors->map(function ($pivot) {
                        return $pivot->user->name;
                    })->implode(' - ');
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
                        $sales_link = '<a class="btn btn-xs btn-success mx-1 shadow" title="Link de vendas" href="' . $row->sales_link . '" target="_blank"><i class="fa fa-lg fa-fw fa-dollar-sign"></i></a>';
                    } else {
                        $sales_link = '';
                    }
                    $edit = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="courses/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                    $delete = '<form method="POST" action="courses/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste curso?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    return '<div class="d-flex justify-content-center align-items-center">' . $sales_link . $edit . $delete . '</div>';
                })
                ->rawColumns(['cover', 'categories', 'authors', 'active', 'action'])
                ->make(true);
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
            $name = Str::slug(mb_substr($data['name'], 0, 100)) . time();
            $extension = $request->cover->extension();
            $nameFile = "{$name}.{$extension}";

            $data['cover'] = $nameFile;

            $destinationPath = storage_path() . '/app/public/courses';
            $destinationPathMedium = storage_path() . '/app/public/courses/medium';
            $destinationPathMin = storage_path() . '/app/public/courses/min';
            $descriptionPath = storage_path() . '/app/public/courses/description';

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 755, true);
            }

            if (! file_exists($destinationPathMedium)) {
                mkdir($destinationPathMedium, 755, true);
            }

            if (! file_exists($destinationPathMin)) {
                mkdir($destinationPathMin, 755, true);
            }

            $img = Image::make($request->cover)->resize(null, 490, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(860, 490)->save($destinationPath . '/' . $nameFile);

            $imgMedium = Image::make($request->cover)->resize(null, 385, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(675, 385)->save($destinationPathMedium . '/' . $nameFile);

            $imgMin = Image::make($request->cover)->resize(null, 207, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(360, 207)->save($destinationPathMin . '/' . $nameFile);

            if (! $img && ! $imgMedium && ! $imgMin) {
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

        $course = Course::find($id);
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

        $course = Course::find($id);

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)) . time();
            $imagePath = storage_path() . '/app/public/courses/' . $course->cover;
            $imagePathMedium = storage_path() . '/app/public/courses/medium/' . $course->cover;
            $imagePathMin = storage_path() . '/app/public/courses/min/' . $course->cover;

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

            $destinationPath = storage_path() . '/app/public/courses';
            $destinationPathMedium = storage_path() . '/app/public/courses/medium';
            $destinationPathMin = storage_path() . '/app/public/courses/min';

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 755, true);
            }

            if (! file_exists($destinationPathMedium)) {
                mkdir($destinationPathMedium, 755, true);
            }

            if (! file_exists($destinationPathMin)) {
                mkdir($destinationPathMin, 755, true);
            }

            $img = Image::make($request->cover)->resize(null, 490, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(860, 490)->save($destinationPath . '/' . $nameFile);

            $imgMedium = Image::make($request->cover)->resize(null, 385, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(675, 385)->save($destinationPathMedium . '/' . $nameFile);

            $imgMin = Image::make($request->cover)->resize(null, 207, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(360, 207)->save($destinationPathMin . '/' . $nameFile);

            if (! $img && ! $imgMedium && ! $imgMin) {
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

        $course = Course::find($id);

        if (! $course) {
            abort(403, 'Acesso não autorizado');
        }

        $imagePath = storage_path() . '/app/public/courses/' . $course->cover;
        $imagePathMedium = storage_path() . '/app/public/courses/medium/' . $course->cover;
        $imagePathMin = storage_path() . '/app/public/courses/min/' . $course->cover;

        if ($course->delete()) {
            if (File::isFile($imagePath)) {
                unlink($imagePath);
            }

            if (File::isFile($imagePathMedium)) {
                unlink($imagePathMedium);
            }

            if (File::isFile($imagePathMin)) {
                unlink($imagePathMin);
            }

            CourseCategoryPivot::where('course_id', $course->id)->delete();
            CourseAuthor::where('course_id', $course->id)->delete();
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
}
