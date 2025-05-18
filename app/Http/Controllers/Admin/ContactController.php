<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Contatos');

        $contacts = Contact::all();

        if ($request->ajax()) {
            $token = csrf_token();

            return DataTables::of($contacts)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn =
                        '<form method="POST" action="'.route('admin.contacts.destroy', ['contact' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste contato?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CheckPermission::checkAuth('Excluir Contatos');

        $contact = Contact::find($id);

        if (! $contact) {
            abort(403, 'Acesso não autorizado');
        }

        if ($contact->delete()) {
            return redirect()
                ->route('admin.home')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
