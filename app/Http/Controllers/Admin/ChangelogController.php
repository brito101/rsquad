<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ChangelogController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        CheckPermission::checkAuth('Acessar Administração');

        return view('admin.changelog.index');
    }
}
