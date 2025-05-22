@extends('adminlte::page')

@section('title', '- Aluno')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-graduation-cap"></i> Aluno {{ $user->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Alunos')
                            <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Alunos</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Aluno</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ $user->photo != null ? url('storage/users/' . $user->photo) : asset('/vendor/adminlte/dist/img/avatar.png') }}"
                                    alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ $user->name }}</h3>
                            <p class="text-muted text-center">Criado em {{ date('d/m/Y', strtotime($user->created_at)) }}
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Dados:</h3>
                        </div>
                        <div class="card-body">
                            <strong><i class="fas fa-mail-bulk mr-1"></i> E-mail</strong>
                            <p class="text-muted">
                                {{ $user->email }}
                            </p>
                            @if ($user->telephone)
                                <hr>
                                <strong><i class="fas fa-phone-alt mr-1"></i> Telefone</strong>
                                <p class="text-muted">{{ $user->telephone }}</p>
                            @endif
                            @if ($user->cell)
                                <hr>
                                <strong><i class="fa fa-mobile mr-1"></i> Celular</strong>
                                <p class="text-muted">{{ $user->cell }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#bio" data-toggle="tab">Bio</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#courses" data-toggle="tab">Cursos</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="bio">
                                    <div>{!! '<p>' . implode('</p><p>', array_filter(explode("\n", $user->bio))) . '</p>' !!}</div>
                                    <div class="mt-4 product-share">
                                        @if ($user->linkedin)
                                            <a href="{{ $user->linkedin }}" class="text-gray" target="_blank">
                                                <i class="fab fa-linkedin fa-2x"></i>
                                            </a>
                                        @endif
                                        @if ($user->instagram)
                                            <a href="{{ $user->instagram }}" class="text-gray" target="_blank">
                                                <i class="fab fa-instagram fa-2x"></i>
                                            </a>
                                        @endif
                                        @if ($user->youtube)
                                            <a href="{{ $user->youtube }}" class="text-gray" target="_blank">
                                                <i class="fab fa-youtube fa-2x"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane" id="courses">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                        @forelse ($courses as $course)
                                            <li class="item d-flex flex-wrap justify-content-start align-content-center">
                                                <div>
                                                    <img class="img-thumbnail d-block" width="360" height="207" src="{{ $course->cover ? url('storage/courses/min/' . $course->cover) : asset('img/defaults/min/courses.webp') }}"
                                                        alt="{{ $course->name }}">
                                                </div>
                                                <div class="product-info d-flex flex-wrap justify-content-start align-content-center">
                                                    <p class="product-title">{{ $course->name }}</p>
                                                </div>
                                            </li>
                                        @empty
                                            <p class="text-center">Nenhum curso cadastrado</p>
                                        @endforelse
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
