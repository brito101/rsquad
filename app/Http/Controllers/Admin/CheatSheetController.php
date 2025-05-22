<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Helpers\TextProcessor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CheatSheetRequest;
use App\Models\CheatSheet;
use App\Models\CheatSheetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CheatSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Cheat Sheet');

        $cheats = CheatSheet::all();

        if ($request->ajax()) {
            $token = csrf_token();

            return DataTables::of($cheats)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category->title;
                })
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-success mx-1 shadow" title="Visualizar" target="_blank" href="'.route('site.cheat-sheets.post', ['uri' => $row->uri]).'"><i class="fa fa-lg fa-fw fa-eye"></i></a>'.
                        ((auth()->user()->hasRole(['Programador', 'Administrador']) || auth()->user()->id == $row->user_id) ? '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="cheat-sheets/'.$row->id.'/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.
                            '<form method="POST" action="cheat-sheets/'.$row->id.'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste cheat sheet?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>' : '');

                    return $btn;
                })
                ->rawColumns(['action', 'category'])
                ->make(true);
        }

        return view('admin.cheat-sheets.index');
    }

    public function create()
    {
        CheckPermission::checkAuth('Criar Cheat Sheet');
        $categories = CheatSheetCategory::orderBy('title')->get();

        return view('admin.cheat-sheets.create', \compact('categories'));
    }

    public function store(CheatSheetRequest $request)
    {
        CheckPermission::checkAuth('Criar Cheat Sheet');

        $data = $request->all();

        if ($request->content) {
            $data['content'] = TextProcessor::store($request->title, 'cheat-sheets/content', $request->content);
        }

        $data['uri'] = Str::slug($request->title);
        $data['user_id'] = Auth::user()->id;

        $post = CheatSheet::create($data);

        if ($post->save()) {
            return redirect()
                ->route('admin.cheat-sheets.index')
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
        CheckPermission::checkAuth('Editar Cheat Sheet');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $cheat = CheatSheet::find($id);
        } else {
            $cheat = CheatSheet::where('user_id', Auth::user()->id)->where('id', '=', $id)->first();
        }

        if (! $cheat) {
            abort(403, 'Acesso não autorizado');
        }

        $categories = CheatSheetCategory::orderBy('title')->get();

        return view('admin.cheat-sheets.edit', compact('cheat', 'categories'));
    }

    public function update(CheatSheetRequest $request, $id)
    {
        CheckPermission::checkAuth('Editar Cheat Sheet');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $post = CheatSheet::find($id);
        } else {
            $post = CheatSheet::where('user_id', Auth::user()->id)->where('id', '=', $id)->first();
        }

        if (! $post) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->content) {
            $data['content'] = TextProcessor::store($request->title, 'cheat-sheets/content', $request->content);
        }

        $post['uri'] = Str::slug($request->title);

        if ($post->update($data)) {
            return redirect()
                ->route('admin.cheat-sheets.index')
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
        CheckPermission::checkAuth('Excluir Cheat Sheet');

        if (auth()->user()->hasRole(['Programador', 'Administrador'])) {
            $post = CheatSheet::find($id);
        } else {
            $post = CheatSheet::where('user_id', Auth::user()->id)->where('id', '=', $id)->first();
        }

        if (! $post) {
            abort(403, 'Acesso não autorizado');
        }

        if ($post->delete()) {
            return redirect()
                ->route('admin.cheat-sheets.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
