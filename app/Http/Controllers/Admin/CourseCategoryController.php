<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseCategoryRequest;
use App\Models\CategoryCourse;
use App\Models\CourseCategoryPivot;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Image;
use Yajra\DataTables\Facades\DataTables;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Categorias de Cursos');

        if ($request->ajax()) {
            $categories = CategoryCourse::all();

            $token = csrf_token();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('cover', function ($row) {
                    return '<div class="d-flex justify-content-center align-items-center"><img src="'.($row->cover ? url('storage/course-categories/min/'.$row->cover) : asset('img/defaults/min/course-categories.webp')).'" class="img-thumbnail d-block" width="360" height="207" alt="'.$row->name.'" title="'.$row->name.'"/></div>';
                })
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="course-categories/'.$row->id.'/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.'<form method="POST" action="course-categories/'.$row->id.'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta categoria?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                })
                ->addColumn('courses', function ($row) {
                    return $row->courses->count();
                })
                ->rawColumns(['cover', 'courses', 'action'])
                ->make(true);
        }

        return view('admin.course-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Categorias de Cursos');

        return view('admin.course-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseCategoryRequest $request)
    {
        CheckPermission::checkAuth('Criar Categorias de Cursos');

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)).time();
            $extension = $request->cover->extension();
            $nameFile = "{$name}.{$extension}";

            $data['cover'] = $nameFile;

            $destinationPath = storage_path().'/app/public/course-categories';
            $destinationPathMedium = storage_path().'/app/public/course-categories/medium';
            $destinationPathMin = storage_path().'/app/public/course-categories/min';

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
            })->crop(860, 490)->save($destinationPath.'/'.$nameFile);

            $imgMedium = Image::make($request->cover)->resize(null, 385, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(675, 385)->save($destinationPathMedium.'/'.$nameFile);

            $imgMin = Image::make($request->cover)->resize(null, 207, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(360, 207)->save($destinationPathMin.'/'.$nameFile);

            if (! $img && ! $imgMedium && ! $imgMin) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Falha ao fazer o upload da imagem');
            }
        }

        $data['user_id'] = auth()->user()->id;

        $category = CategoryCourse::create($data);

        if ($category->save()) {
            return redirect()
                ->route('admin.course-categories.index')
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
        CheckPermission::checkAuth(auth: 'Editar Categorias de Cursos');

        $category = CategoryCourse::find($id);
        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.course-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseCategoryRequest $request, string $id)
    {
        CheckPermission::checkAuth(auth: 'Editar Categorias de Cursos');

        $category = CategoryCourse::find($id);

        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)).time();
            $imagePath = storage_path().'/app/public/course-categories/'.$category->cover;
            $imagePathMedium = storage_path().'/app/public/course-categories/medium/'.$category->cover;
            $imagePathMin = storage_path().'/app/public/course-categories/min/'.$category->cover;

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

            $destinationPath = storage_path().'/app/public/course-categories';
            $destinationPathMedium = storage_path().'/app/public/course-categories/medium';
            $destinationPathMin = storage_path().'/app/public/course-categories/min';

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
            })->crop(860, 490)->save($destinationPath.'/'.$nameFile);

            $imgMedium = Image::make($request->cover)->resize(null, 385, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(675, 385)->save($destinationPathMedium.'/'.$nameFile);

            $imgMin = Image::make($request->cover)->resize(null, 207, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(360, 207)->save($destinationPathMin.'/'.$nameFile);

            if (! $img && ! $imgMedium && ! $imgMin) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Falha ao fazer o upload da imagem');
            }
        }

        $data['user_id'] = auth()->user()->id;

        if ($category->update($data)) {
            return redirect()
                ->route('admin.course-categories.index')
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
        CheckPermission::checkAuth(auth: 'Excluir Categorias de Cursos');

        $category = CategoryCourse::find($id);

        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        $imagePath = storage_path().'/app/public/course-categories/'.$category->cover;
        $imagePathMedium = storage_path().'/app/public/course-categories/medium/'.$category->cover;
        $imagePathMin = storage_path().'/app/public/course-categories/min/'.$category->cover;

        if ($category->delete()) {
            if (File::isFile($imagePath)) {
                unlink($imagePath);
            }

            if (File::isFile($imagePathMedium)) {
                unlink($imagePathMedium);
            }

            if (File::isFile($imagePathMin)) {
                unlink($imagePathMin);
            }

            CourseCategoryPivot::where('category_course_id', $category->id)->delete();
            $category->cover = null;
            $category->update();

            return redirect()
                ->route('admin.course-categories.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
