@extends('adminlte::page')

@section('title', '- Academy')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa fa-fw fa-digital-tachograph"></i> Academy</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if ($courses->count() > 0)
        <section class="content">
            <div class="container-fluid">
                <div class="row px-0">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-fw fa-newspaper mr-2"></i> Meus Cursos
                                </h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-wrap justify-content-center">

                                @foreach ($courses as $course)
                                    <div class="col-md-4">
                                        <!-- Widget: user widget style 1 -->
                                        <div class="card card-widget widget-user shadow-lg">
                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                            <div class="widget-user-header text-white"
                                                style="background: linear-gradient(rgba(0,0,0,.85), rgba(0,0,0,.85)), url('{{ $course->cover ? url('storage/courses/min/' . $course->cover) : asset('img/defaults/min/courses.webp') }}') center center;">
                                                <h3 class="widget-user-username text-right font-weight-bold">
                                                    {{ $course->name }}
                                                </h3>
                                            </div>
                                            <div class="widget-user-image">
                                                <img class="img-circle"
                                                    src="{{ $course->user->photo ? url('storage/users/' . $course->user->photo) : asset('vendor/adminlte/dist/img/avatar.png') }}"
                                                    alt="{{ $course->user->name }}">
                                            </div>
                                            <div class="card-footer">
                                                <div class="row">
                                                    <div class="col-sm-6 border-right">
                                                        <div class="description-block">
                                                            <h5 class="description-header">
                                                                {{ $course->modules->where('active', true)->count() }}</h5>
                                                            <span class="description-text">Módulos</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 border-left">
                                                        <div class="description-block">
                                                            <h5 class="description-header">
                                                                {{ $course->classes->where('active', true)->count() }}</h5>
                                                            <span class="description-text">Aulas</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="p-0 col-12">
                                                    <span class="nav-link text-center">
                                                        <a href="#"><i
                                                                class="fas fa-fw fa-2x fa-chalkboard-teacher text-dark mr-2"></i>
                                                            Aulas</a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="content">
            <div class="container-fluid">
                <div class="row px-0">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-center">Não há cursos adquiridos</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($courses_avaliable->count() > 0)
        <section class="content">
            <div class="container-fluid">
                <div class="row px-0">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-fw fa-plus mr-2"></i> Cursos Disponíveis
                                </h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-wrap justify-content-start">

                                @foreach ($courses_avaliable as $course)
                                    <div class="col-md-4">
                                        <!-- Widget: user widget style 1 -->
                                        <div class="card card-widget widget-user shadow-lg">
                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                            <div class="widget-user-header text-white"
                                                style="background: linear-gradient(rgba(0,0,0,.85), rgba(0,0,0,.85)), url('{{ $course->cover ? url('storage/courses/min/' . $course->cover) : asset('img/defaults/min/courses.webp') }}') center center;">
                                                <h3 class="widget-user-username text-right font-weight-bold">
                                                    {{ $course->name }}
                                                </h3>
                                            </div>
                                            <div class="widget-user-image">
                                                <img class="img-circle"
                                                    src="{{ $course->user->photo ? url('storage/users/' . $course->user->photo) : asset('vendor/adminlte/dist/img/avatar.png') }}"
                                                    alt="{{ $course->user->name }}">
                                            </div>
                                            <div class="card-footer">
                                                <div class="row">
                                                    <div class="col-sm-6 border-right">
                                                        <div class="description-block">
                                                            <h5 class="description-header">
                                                                {{ $course->modules->where('active', true)->count() }}</h5>
                                                            <span class="description-text">Módulos</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 border-left">
                                                        <div class="description-block">
                                                            <h5 class="description-header">
                                                                {{ $course->classes->where('active', true)->count() }}</h5>
                                                            <span class="description-text">Aulas</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($course->sales_link)
                                                <div class="row">
                                                    <div class="p-0 col-12">
                                                        <span class="nav-link text-center">
                                                            <a href="{{ $course->sales_link }}" target="_blank"><i
                                                                    class="fas fa-fw fa-2x fa-link text-dark mr-2"></i>
                                                                Adquira por Aqui</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
