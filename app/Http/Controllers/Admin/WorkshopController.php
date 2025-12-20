<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class WorkshopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Workshops');

        if ($request->ajax()) {
            $workshops = Workshop::with(['user'])->select('workshops.*');

            $token = csrf_token();

            try {
                return DataTables::eloquent($workshops)
                    ->addIndexColumn()
                    ->addColumn('author', function ($row) {
                        return $row->user->name ?? '-';
                    })
                    ->addColumn('visibility', function ($row) {
                        if ($row->is_public) {
                            return 'Público';
                        }
                        return 'Alunos';
                    })
                    ->addColumn('status_badge', function ($row) {
                        switch ($row->status) {
                            case 'published':
                                return 'Publicado';
                            case 'scheduled':
                                return 'Agendado';
                            case 'archived':
                                return 'Arquivado';
                            default:
                                return 'Rascunho';
                        }
                    })
                    ->addColumn('featured_badge', function ($row) {
                        return $row->featured ? 'Destaque' : 'Normal';
                    })
                    ->addColumn('video_type_badge', function ($row) {
                        switch ($row->video_type) {
                            case 'youtube':
                                return 'YouTube';
                            case 'vimeo':
                                return 'Vimeo';
                            default:
                                return 'Sem vídeo';
                        }
                    })
                    ->addColumn('scheduled_at_formatted', function ($row) {
                        return $row->scheduled_at ? $row->scheduled_at->format('d/m/Y H:i') : '-';
                    })
                    ->addColumn('scheduled_at_sort', function ($row) {
                        return $row->scheduled_at ? $row->scheduled_at->timestamp : 0;
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        $actions = '<div class="d-flex justify-content-center align-items-center">';

                        if (Auth::user()->hasPermissionTo('Editar Workshops')) {
                            $actions .= '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.workshops.edit', ['workshop' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                        }

                        if (Auth::user()->hasPermissionTo('Excluir Workshops')) {
                            $actions .= '<form method="POST" action="'.route('admin.workshops.destroy', ['workshop' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste workshop?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                        }

                        $actions .= '</div>';

                        return $actions;
                    })
                    ->rawColumns(['action'])
                    ->orderColumn('scheduled_at_formatted', function ($query, $order) {
                        $query->orderBy('scheduled_at', $order);
                    })
                    ->order(function ($query) {
                        $query->orderBy('created_at', 'desc');
                    })
                    ->make(true);
            } catch (Exception $e) {
                return response([
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return view('admin.workshops.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Workshops');

        return view('admin.workshops.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        CheckPermission::checkAuth('Criar Workshops');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_type' => 'required|in:youtube,vimeo,none',
            'video_url' => 'nullable|string|url',
            'is_public' => 'required|boolean',
            'status' => 'required|in:draft,scheduled,published,archived',
            'scheduled_at' => 'nullable|date',
            'duration' => 'nullable|integer|min:1',
        ]);

        // Generate slug
        $validated['slug'] = Workshop::generateSlug($validated['title']);
        $validated['user_id'] = Auth::id();
        $validated['featured'] = $request->has('featured');

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/workshops', $imageName);
            $validated['cover'] = 'storage/workshops/' . $imageName;
        }

        Workshop::create($validated);

        return redirect()
            ->route('admin.workshops.index')
            ->with('success', 'Workshop criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        CheckPermission::checkAuth('Editar Workshops');

        $workshop = Workshop::with(['user'])->findOrFail($id);

        return view('admin.workshops.edit', compact('workshop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        CheckPermission::checkAuth('Editar Workshops');

        $workshop = Workshop::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_type' => 'required|in:youtube,vimeo,none',
            'video_url' => 'nullable|string|url',
            'is_public' => 'required|boolean',
            'status' => 'required|in:draft,scheduled,published,archived',
            'scheduled_at' => 'nullable|date',
            'duration' => 'nullable|integer|min:1',
        ]);

        // Update slug if title changed
        if ($validated['title'] !== $workshop->title) {
            $validated['slug'] = Workshop::generateSlug($validated['title']);
        }

        $validated['featured'] = $request->has('featured');

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            // Delete old cover
            if ($workshop->cover && Storage::exists(str_replace('storage/', 'public/', $workshop->cover))) {
                Storage::delete(str_replace('storage/', 'public/', $workshop->cover));
            }

            $image = $request->file('cover');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/workshops', $imageName);
            $validated['cover'] = 'storage/workshops/' . $imageName;
        }

        $workshop->update($validated);

        return redirect()
            ->route('admin.workshops.index')
            ->with('success', 'Workshop atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CheckPermission::checkAuth('Excluir Workshops');

        $workshop = Workshop::findOrFail($id);

        // Delete cover image
        if ($workshop->cover && Storage::exists(str_replace('storage/', 'public/', $workshop->cover))) {
            Storage::delete(str_replace('storage/', 'public/', $workshop->cover));
        }

        $workshop->delete();

        return redirect()
            ->route('admin.workshops.index')
            ->with('success', 'Workshop excluído com sucesso!');
    }
}
