<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\BlogCategoriesPivot;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Categorias do Blog');

        $categories = BlogCategory::all();

        if ($request->ajax()) {
            $token = csrf_token();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="blog-categories/'.$row->id.'/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.'<form method="POST" action="blog-categories/'.$row->id.'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta categoria?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';

                    return $btn;
                })
                ->addColumn('cover', function ($row) {
                    return '<div class="d-flex justify-content-center align-items-center"><img src='.url('storage/blog-categories/min/'.$row->cover).' class="img-thumbnail d-block" width="360" height="207" alt="'.$row->title.'" title="'.$row->title.'"/></div>';
                })
                ->addColumn('posts', function ($row) {
                    return $row->posts->count();
                })
                ->rawColumns(['action', 'cover', 'posts'])
                ->make(true);
        }

        return view('admin.blog.categories.index');
    }

    public function create()
    {
        CheckPermission::checkAuth('Criar Categorias do Blog');

        return view('admin.blog.categories.create');
    }

    public function store(BlogCategoryRequest $request)
    {
        CheckPermission::checkAuth('Criar Categorias do Blog');

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['title'], 0, 100)).time();
            $extension = $request->cover->extension();
            $nameFile = "{$name}.{$extension}";

            $data['cover'] = $nameFile;

            $destinationPath = storage_path().'/app/public/blog-categories';
            $destinationPathMedium = storage_path().'/app/public/blog-categories/medium';
            $destinationPathMin = storage_path().'/app/public/blog-categories/min';

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

        $data['uri'] = Str::slug($request->title);
        $category = BlogCategory::create($data);

        if ($category->save()) {
            return redirect()
                ->route('admin.blog-categories.index')
                ->with('success', 'Cadastro realizado!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Categorias do Blog');

        $category = BlogCategory::find($id);
        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.blog.categories.edit', compact('category'));
    }

    public function update(BlogCategoryRequest $request, $id)
    {
        CheckPermission::checkAuth('Editar Categorias do Blog');

        $category = BlogCategory::find($id);

        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $name = Str::slug(mb_substr($data['title'], 0, 100)).time();
            $imagePath = storage_path().'/app/public/blog-categories/'.$category->cover;
            $imagePathMedium = storage_path().'/app/public/blog-categories/medium/'.$category->cover;
            $imagePathMin = storage_path().'/app/public/blog-categories/min/'.$category->cover;

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

            $destinationPath = storage_path().'/app/public/blog-categories';
            $destinationPathMedium = storage_path().'/app/public/blog-categories/medium';
            $destinationPathMin = storage_path().'/app/public/blog-categories/min';

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

        $data['uri'] = Str::slug($request->title);

        if ($category->update($data)) {
            return redirect()
                ->route('admin.blog-categories.index')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    public function destroy($id)
    {
        CheckPermission::checkAuth('Excluir Categorias do Blog');

        $category = BlogCategory::find($id);

        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        $imagePath = storage_path().'/app/public/blog-categories/'.$category->cover;
        $imagePathMedium = storage_path().'/app/public/blog-categories/medium/'.$category->cover;
        $imagePathMin = storage_path().'/app/public/blog-categories/min/'.$category->cover;

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

            BlogCategoriesPivot::where('blog_category_id', $category->id)->delete();
            $category->cover = null;
            $category->update();

            return redirect()
                ->route('admin.blog-categories.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
