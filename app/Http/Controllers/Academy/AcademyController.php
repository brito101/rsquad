<?php

namespace App\Http\Controllers\Academy;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Acessar Academia');

        return view('academy.home.index');
    }
}
