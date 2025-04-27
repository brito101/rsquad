<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CheatSheetCategoryRequest;
use App\Models\CheatSheetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CheatSheetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Categorias do Cheat Sheet');

        $categories = CheatSheetCategory::all();

        if ($request->ajax()) {
            $token = csrf_token();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn =
                        '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.cheat-sheets-categories.edit', ['cheat_sheets_category' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.
                        '<form method="POST" action="'.route('admin.cheat-sheets-categories.destroy', ['cheat_sheets_category' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta categoria?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';

                    return $btn;
                })
                ->addColumn('cheats', function ($row) {
                    return $row->cheats->count();
                })
                ->rawColumns(['action', 'cover', 'cheats'])
                ->make(true);
        }

        return view('admin.cheat-sheets.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Categorias do Cheat Sheet');

        return view('admin.cheat-sheets.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CheatSheetCategoryRequest $request)
    {
        CheckPermission::checkAuth('Criar Categorias do Cheat Sheet');

        $data = $request->all();

        $data['uri'] = Str::slug($request->title);
        $category = CheatSheetCategory::create($data);

        if ($category->save()) {
            return redirect()
                ->route('admin.cheat-sheets-categories.index')
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
        CheckPermission::checkAuth('Editar Categorias do Cheat Sheet');

        $category = CheatSheetCategory::find($id);
        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.cheat-sheets.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CheatSheetCategoryRequest $request, string $id)
    {
        CheckPermission::checkAuth('Editar Categorias do Cheat Sheet');

        $category = CheatSheetCategory::find($id);

        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        $data['uri'] = Str::slug($request->title);

        if ($category->update($data)) {
            return redirect()
                ->route('admin.cheat-sheets-categories.index')
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
        CheckPermission::checkAuth('Excluir Categorias do Cheat Sheet');

        $category = CheatSheetCategory::find($id);

        if (! $category) {
            abort(403, 'Acesso não autorizado');
        }

        if ($category->delete()) {
            return redirect()
                ->route('admin.cheat-sheets-categories.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
