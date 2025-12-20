<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Support\Facades\Auth;

class WorkshopController extends Controller
{
    /**
     * Display a listing of workshops for students.
     */
    public function index()
    {
        // Check if user is a student
        if (!Auth::user()->hasRole('Aluno')) {
            abort(403, 'Acesso não autorizado.');
        }

        $workshops = Workshop::where(function($query) {
                // Public workshops or private workshops for students
                $query->where('is_public', true)
                      ->orWhere('is_public', false);
            })
            ->published()
            ->orderBy('scheduled_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();

        return view('academy.workshops.index', compact('workshops'));
    }

    /**
     * Display the specified workshop.
     */
    public function show($slug)
    {
        // Check if user is a student
        if (!Auth::user()->hasRole('Aluno')) {
            abort(403, 'Acesso não autorizado.');
        }

        $workshop = Workshop::where('slug', $slug)
            ->where(function($query) {
                // Public workshops or private workshops for students
                $query->where('is_public', true)
                      ->orWhere('is_public', false);
            })
            ->published()
            ->with('user')
            ->firstOrFail();

        $relatedWorkshops = Workshop::where(function($query) {
                $query->where('is_public', true)
                      ->orWhere('is_public', false);
            })
            ->published()
            ->where('id', '!=', $workshop->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('academy.workshops.show', compact('workshop', 'relatedWorkshops'));
    }
}
