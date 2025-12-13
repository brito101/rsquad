<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Depoimentos');

        if ($request->ajax()) {
            $testimonials = Testimonial::with(['user', 'course'])->select('testimonials.*');

            $token = csrf_token();

            try {
                return DataTables::eloquent($testimonials)
                    ->addIndexColumn()
                    ->addColumn('student', function ($row) {
                        return $row->user->name ?? '-';
                    })
                    ->addColumn('course_name', function ($row) {
                        return $row->course->name ?? '-';
                    })
                    ->addColumn('rating', function ($row) {
                        return $row->rating;
                    })
                    ->addColumn('rating_stars', function ($row) {
                        $stars = '';
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $row->rating) {
                                $stars .= '<i class="fas fa-star text-warning"></i>';
                            } else {
                                $stars .= '<i class="far fa-star text-warning"></i>';
                            }
                        }

                        return $stars;
                    })
                    ->addColumn('status_badge', function ($row) {
                        switch ($row->status) {
                            case 'approved':
                                return '<span class="badge badge-success">Aprovado</span>';
                            case 'rejected':
                                return '<span class="badge badge-danger">Rejeitado</span>';
                            default:
                                return '<span class="badge badge-warning">Pendente</span>';
                        }
                    })
                    ->addColumn('featured_badge', function ($row) {
                        if ($row->featured) {
                            return '<span class="badge badge-primary"><i class="fas fa-star"></i> Destaque</span>';
                        }

                        return '-';
                    })
                    ->addColumn('created_at_formatted', function ($row) {
                        return $row->created_at->format('d/m/Y H:i');
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        $actions = '<div class="d-flex justify-content-center align-items-center">';

                        if (Auth::user()->hasPermissionTo('Editar Depoimentos')) {
                            $actions .= '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.testimonials.edit', ['testimonial' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                        }

                        if (Auth::user()->hasPermissionTo('Excluir Depoimentos')) {
                            $actions .= '<form method="POST" action="'.route('admin.testimonials.destroy', ['testimonial' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste depoimento?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                        }

                        $actions .= '</div>';

                        return $actions;
                    })
                    ->rawColumns(['rating_stars', 'status_badge', 'featured_badge', 'action'])
                    ->orderColumn('rating', function ($query, $order) {
                        $query->orderBy('rating', $order);
                    })
                    ->orderColumn('created_at_formatted', function ($query, $order) {
                        $query->orderBy('created_at', $order);
                    })
                    ->orderColumn('featured', function ($query, $order) {
                        $query->orderBy('featured', $order);
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

        return view('admin.testimonials.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        CheckPermission::checkAuth('Editar Depoimentos');

        $testimonial = Testimonial::with(['user', 'course'])->findOrFail($id);

        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TestimonialRequest $request, string $id)
    {
        CheckPermission::checkAuth('Editar Depoimentos');

        $testimonial = Testimonial::findOrFail($id);

        $data = $request->validated();

        $testimonial->update($data);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Depoimento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CheckPermission::checkAuth('Excluir Depoimentos');

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Depoimento excluído com sucesso!');
    }

    /**
     * Approve testimonial
     */
    public function approve(string $id)
    {
        CheckPermission::checkAuth('Editar Depoimentos');

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->approve();

        return redirect()
            ->back()
            ->with('success', 'Depoimento aprovado com sucesso!');
    }

    /**
     * Reject testimonial
     */
    public function reject(string $id)
    {
        CheckPermission::checkAuth('Editar Depoimentos');

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->reject();

        return redirect()
            ->back()
            ->with('success', 'Depoimento rejeitado com sucesso!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(string $id)
    {
        CheckPermission::checkAuth('Editar Depoimentos');

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->toggleFeatured();

        $message = $testimonial->featured ? 'Depoimento marcado como destaque!' : 'Depoimento removido dos destaques!';

        return redirect()
            ->back()
            ->with('success', $message);
    }
}
