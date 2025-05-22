@extends('adminlte::page')

@section('title', '- Curso ' . $course->name)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-newspaper"></i> Curso {{ $course->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('academy.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('academy.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item active">Curso {{ $course->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    @include('components.alert')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalhes do Curso</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-muted">Módulos</span>
                                                    <span
                                                        class="info-box-number text-center text-muted mb-0">{{ $modules->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-muted">Aulas</span>
                                                    <span
                                                        class="info-box-number text-center text-muted mb-0">{{ $classes->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            @foreach ($course->modules as $module)
                                                <div class="card" style="min-height: 50px;">
                                                    <div class="card-header">
                                                        @if ($module->cover)
                                                            <img class="img-circle img-bordered-sm"
                                                                src="{{ url('storage/course-modules/icon/' . $module->cover) }}"
                                                                alt="{{ $module->name }}">
                                                        @endif
                                                        <span class="username">
                                                            @if ($module->link)
                                                                @if ($module->release_date)
                                                                    @if ($module->release_date && $module->release_date <= now())
                                                                        <a href="{{ $module->link }}" target="_blank"
                                                                            class="h3 text-primary">{{ $module->name }}</a>
                                                                    @else
                                                                        <a href="#"
                                                                            class="h3 text-primary">{{ $module->name }}
                                                                            <span
                                                                                class="badge badge-primary text-sm float-right">Liberação
                                                                                em
                                                                                {{ date('d/m/Y', strtotime($module->release_date)) }}</span></a>
                                                                    @endif
                                                                @else
                                                                    <a href="{{ $module->link }}" target="_blank"
                                                                        class="h3 text-primary">{{ $module->name }}</a>
                                                                @endif
                                                            @else
                                                                <a href="#"
                                                                    class="h3 text-primary">{{ $module->name }}</a>
                                                            @endif
                                                        </span>
                                                        {!! $module->description !!}
                                                    </div>

                                                    <ul class="todo-list">
                                                        @foreach ($classes as $classroom)
                                                            @if ($classroom->course_module_id == $module->id)
                                                                <li>

                                                                    <span>
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </span>

                                                                    <span class="text">{{ $classroom->name }}</span>
                                                                    @if ($classroom->link)
                                                                        @if ($classroom->release_date)
                                                                            @if ($classroom->release_date && $classroom->release_date <= now())
                                                                                <a class="btn btn-sm btn-success float-right"
                                                                                    target="_blank"
                                                                                    href="{{ $classroom->link }}"><i
                                                                                        class="fas fa-link"></i> Link da
                                                                                    aula</a>
                                                                            @else
                                                                                <span
                                                                                    class="badge badge-warning text-sm float-sm-right mr-2">Liberação
                                                                                    em
                                                                                    {{ date('d/m/Y', strtotime($classroom->release_date)) }}</span></a>
                                                                            @endif
                                                                        @else
                                                                            <a class="btn btn-sm btn-success float-right"
                                                                                target="_blank"
                                                                                href="{{ $classroom->link }}"><i
                                                                                    class="fas fa-link"></i> Link da
                                                                                aula</a>
                                                                        @endif
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>

                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                    @if ($course->cover)
                                        <img class="img-fluid mb-4"
                                            src="{{ url('storage/courses/medium/' . $course->cover) }}" alt="Course Cover">
                                    @endif

                                    <h3 class="text-primary">{{ $course->name }}</h3>

                                    {!! $course->description !!}

                                    <br>
                                    <div class="text-muted">
                                        @if ($course->instructors->count() > 0)
                                            <p class="text-sm">Instrutores:
                                                @foreach ($course->instructorsInfo as $instructor)
                                                    <b class="d-block">{{ $instructor->name }}</b>
                                                @endforeach
                                            </p>
                                        @endif
                                        @if ($course->categories->count() > 0)
                                            <p class="text-sm">Categorias:
                                                @foreach ($course->categoriesInfo as $category)
                                                    <b class="d-block">{{ $category->name }}</b>
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
