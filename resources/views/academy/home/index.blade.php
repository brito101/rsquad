@extends('adminlte::page')

@section('title', '- Academy')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-graduation-cap text-primary"></i> Academy</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            {{-- Info Boxes --}}
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <x-adminlte-info-box title="Meus Cursos" text="{{ $courses->count() }}" icon="fas fa-book text-white" 
                        icon-theme="primary" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-adminlte-info-box title="Cursos Disponíveis" text="{{ $courses_avaliable->count() }}" icon="fas fa-shopping-cart text-white" 
                        icon-theme="success" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-adminlte-info-box title="Workshops" text="{{ $workshops->count() }}" icon="fas fa-chalkboard-teacher text-white" 
                        icon-theme="info" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-adminlte-info-box title="Certificados" text="{{ $certificates->count() }}" icon="fas fa-certificate text-white" 
                        icon-theme="warning" />
                </div>
            </div>

            <div class="row">
                {{-- Conteúdo Principal --}}
                <div class="col-lg-8">
                    @if ($courses->count() > 0)
                        {{-- Meus Cursos --}}
                        <x-adminlte-card title="Meus Cursos" icon="fas fa-book" theme="primary" collapsible>
                            <div class="row">
                                @foreach ($courses as $course)
                                    <div class="col-12 col-md-6">
                                        <div class="card card-widget widget-user shadow-sm">
                                            <div class="widget-user-header text-white"
                                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 125px;">
                                                <h5 class="widget-user-username text-right font-weight-bold">
                                                    {{ $course->name }}
                                                </h5>
                                                <h6 class="widget-user-desc text-right">
                                                    @foreach ($course->instructorsInfo as $author)
                                                        {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                </h6>
                                            </div>
                                            <div class="widget-user-image">
                                                <img class="img-circle elevation-2"
                                                    src="{{ $course->cover ? url('storage/courses/icon/' . $course->cover) : asset('img/logo-bg-white.png') }}"
                                                    alt="{{ $course->name }}">
                                            </div>
                                            <div class="card-footer">
                                                <div class="row">
                                                    <div class="col-4 border-right">
                                                        <div class="description-block">
                                                            <h5 class="description-header text-primary">
                                                                {{ $course->modules->where('active', true)->count() }}
                                                            </h5>
                                                            <span class="description-text">Módulos</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 border-right">
                                                        <div class="description-block">
                                                            <h5 class="description-header text-success">
                                                                {{ $course->classes->where('active', true)->count() }}
                                                            </h5>
                                                            <span class="description-text">Aulas</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="description-block">
                                                            <h5 class="description-header text-info">
                                                                {{ $course->students->count() }}
                                                            </h5>
                                                            <span class="description-text">Alunos</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer p-2 d-flex justify-content-center">
                                                <a href="{{ route('academy.courses.show', ['course' => $course->id]) }}" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-play mr-1"></i> Acessar Curso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </x-adminlte-card>
                    @else
                        <x-adminlte-callout theme="info" title="Nenhum curso matriculado">
                            Você ainda não está matriculado em nenhum curso. Confira os cursos disponíveis abaixo!
                        </x-adminlte-callout>
                    @endif

                    @if ($courses_avaliable->count() > 0)
                        {{-- Cursos Disponíveis --}}
                        <x-adminlte-card title="Cursos Disponíveis" icon="fas fa-plus-circle" theme="primary" collapsible>
                            <div class="row">
                                @foreach ($courses_avaliable as $course)
                                    <div class="col-12 col-md-6">
                                        <x-adminlte-profile-widget name="{{ $course->name }}"
                                            desc="{{ $course->instructors->count() > 0 ? 'Instrutores: ' . $course->instructorsInfo()->pluck('name')->join(', ') : '' }}"
                                            theme="primary"
                                            img="{{ $course->cover ? url('storage/courses/icon/' . $course->cover) : asset('/img/logo-bg-white.png') }}"
                                            layout-type="classic">
                                            <x-adminlte-profile-row-item icon="fas fa-fw fa-layer-group" title="Módulos"
                                                text="{{ $course->modules->where('active', true)->count() }}" url="#"
                                                badge="teal" />
                                            <x-adminlte-profile-row-item
                                                icon="fas fa-fw fa-chalkboard-teacher fa-flip-horizontal" title="Aulas"
                                                text="{{ $course->classes->where('active', true)->count() }}"
                                                url="#" badge="lightblue" />
                                            <x-adminlte-profile-row-item icon="fas fa-fw fa-graduation-cap" title="Alunos"
                                                text="{{ $course->students->count() }}" url="#" badge="navy" />
                                            @if ($course->sales_link)
                                                <x-adminlte-profile-row-item class="text-center border-top"
                                                    title="Adquira agora!" url="{{ $course->sales_link }}" size=12 />
                                            @endif
                                        </x-adminlte-profile-widget>
                                    </div>
                                @endforeach
                            </div>
                        </x-adminlte-card>
                    @endif
                </div>

                {{-- Sidebar - Workshops --}}
                <div class="col-lg-4">
                    @if ($workshops->count() > 0)
                        <x-adminlte-card title="Últimos Workshops" icon="fas fa-chalkboard-teacher" theme="secondary" collapsible>
                            <ul class="list-group list-group-flush">
                                @foreach ($workshops as $workshop)
                                    <li class="list-group-item px-0">
                                        <div class="d-flex align-items-start">
                                            @if ($workshop->cover)
                                                <img src="{{ asset($workshop->cover) }}" 
                                                     class="img-thumbnail mr-3" 
                                                     alt="{{ $workshop->title }}"
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center mr-3 rounded" 
                                                     style="width: 80px; height: 80px; min-width: 80px;">
                                                    <i class="fas fa-chalkboard-teacher fa-2x text-white"></i>
                                                </div>
                                            @endif
                                            <div class="flex-fill">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('academy.workshops.show', ['slug' => $workshop->slug]) }}" 
                                                       class="text-dark">
                                                        {{ $workshop->title }}
                                                    </a>
                                                </h6>
                                                <p class="text-muted small mb-2">
                                                    {{ Str::limit($workshop->description, 60) }}
                                                </p>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    @if ($workshop->duration)
                                                        <small class="text-muted mr-3">
                                                            <i class="far fa-clock"></i> {{ $workshop->duration }} min
                                                        </small>
                                                    @endif
                                                    @if (!$workshop->is_public)
                                                        <span class="badge badge-warning badge-sm">
                                                            <i class="fas fa-lock"></i> Exclusivo
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="text-center mt-3">
                                <a href="{{ route('academy.workshops.index') }}" class="btn btn-secondary btn-sm btn-block">
                                    <i class="fas fa-list mr-1"></i> Ver Todos os Workshops
                                </a>
                            </div>
                        </x-adminlte-card>
                    @endif
                </div>
            </div>

        </div>
    </section>
@endsection
