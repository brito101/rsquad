@extends('adminlte::page')

@section('title', '- Dashboard')

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa fa-fw fa-digital-tachograph"></i> Dashboard</h1>
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
            <div class="row">
                <div class="col-12">
                    @include('components.alert')
                </div>
                @if (Auth::user()->hasRole('Programador|Administrador'))
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-shield"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Administradores</span>
                                <span class="info-box-number">{{ $administrators }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1"><i
                                    class="fas fa-chalkboard-teacher"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Instrutores</span>
                                <span class="info-box-number">{{ $instructors }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-secondary elevation-1"><i
                                    class="fas fa-user-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Monitores</span>
                                <span class="info-box-number">{{ $monitors }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Alunos</span>
                                <span class="info-box-number">{{ $students->count() }}</span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            @can('Listar Contatos')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                            <h3 class="card-title align-self-center"><i class="fa fa-envelope mr-2"></i> Contatos
                            </h3>
                        </div>
                    </div>

                    @php
                        $headsContacts = [
                            ['label' => 'ID', 'width' => 10],
                            'E-mail',
                            'Nome',
                            'Telefone',
                            'Mensagem',
                            ['label' => 'Ações', 'no-export' => true, 'width' => 10],
                        ];
                        $configContacts = [
                            'ajax' => route('admin.contacts.index'),
                            'columns' => [
                                ['data' => 'id', 'name' => 'id'],
                                ['data' => 'email', 'name' => 'email', 'orderable' => false],
                                ['data' => 'name', 'name' => 'name'],
                                ['data' => 'phone', 'name' => 'phone'],
                                ['data' => 'message', 'name' => 'message'],
                                [
                                    'data' => 'action',
                                    'name' => 'action',
                                    'orderable' => false,
                                    'searchable' => false,
                                ],
                            ],
                            'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                            'autoFill' => true,
                            'processing' => true,
                            'serverSide' => true,
                            'responsive' => true,
                            'dom' => '<"d-flex flex-wrap col-12 justify-content-between"Bf>rtip',
                            'buttons' => [
                                ['extend' => 'pageLength', 'className' => 'btn-default'],
                                [
                                    'extend' => 'copy',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-copy text-secondary"></i>',
                                    'titleAttr' => 'Copiar',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'print',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                    'titleAttr' => 'Imprimir',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'csv',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-file-csv text-primary"></i>',
                                    'titleAttr' => 'Exportar para CSV',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'excel',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-file-excel text-success"></i>',
                                    'titleAttr' => 'Exportar para Excel',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'pdf',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>',
                                    'titleAttr' => 'Exportar para PDF',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                            ],
                        ];
                    @endphp

                    <div class="card-body">
                        <x-adminlte-datatable id="table1" :heads="$headsContacts" :heads="$headsContacts" :config="$configContacts" striped
                            hoverable beautify />
                    </div>
                </div>
            @endcan

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                        <h3 class="card-title align-self-center"><i class="fa fa-newspaper mr-2"></i> Cursos Cadastrados
                        </h3>
                        @can('Criar Cursos')
                            <a href="{{ route('admin.courses.create') }}" title="Novo Curso" class="btn btn-success"><i
                                    class="fas fa-fw fa-plus"></i>Novo Curso</a>
                        @endcan
                    </div>
                </div>

                @php
                    $headsCourses = [
                        ['label' => 'ID', 'width' => 10],
                        ['label' => 'Foto', 'no-export' => true],
                        'Nome',
                        'Categorias',
                        'Módulos',
                        'Aulas',
                        'Instrutores',
                        'Monitores',
                        'Alunos',
                        'Status',
                        'Ativo',
                        ['label' => 'Ações', 'no-export' => true, 'width' => 10],
                    ];
                    $configCourses = [
                        'ajax' => route('admin.courses.index'),
                        'columns' => [
                            ['data' => 'id', 'name' => 'id'],
                            ['data' => 'cover', 'name' => 'cover', 'orderable' => false],
                            ['data' => 'name', 'name' => 'name'],
                            ['data' => 'categories', 'name' => 'categories'],
                            ['data' => 'modules', 'name' => 'modules'],
                            ['data' => 'classes', 'name' => 'classes'],
                            ['data' => 'instructors', 'name' => 'instructors'],
                            ['data' => 'monitors', 'name' => 'monitors'],
                            ['data' => 'students', 'name' => 'students'],
                            ['data' => 'status', 'name' => 'status'],
                            ['data' => 'active', 'name' => 'active'],
                            [
                                'data' => 'action',
                                'name' => 'action',
                                'orderable' => false,
                                'searchable' => false,
                            ],
                        ],
                        'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                        'autoFill' => true,
                        'processing' => true,
                        'serverSide' => true,
                        'responsive' => true,
                        'dom' => '<"d-flex flex-wrap col-12 justify-content-between"Bf>rtip',
                        'buttons' => [
                            ['extend' => 'pageLength', 'className' => 'btn-default'],
                            [
                                'extend' => 'copy',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-copy text-secondary"></i>',
                                'titleAttr' => 'Copiar',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                            ],
                            [
                                'extend' => 'print',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                'titleAttr' => 'Imprimir',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                            ],
                            [
                                'extend' => 'csv',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-file-csv text-primary"></i>',
                                'titleAttr' => 'Exportar para CSV',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                            ],
                            [
                                'extend' => 'excel',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-file-excel text-success"></i>',
                                'titleAttr' => 'Exportar para Excel',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                            ],
                            [
                                'extend' => 'pdf',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>',
                                'titleAttr' => 'Exportar para PDF',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                            ],
                        ],
                    ];
                @endphp

                <div class="card-body">
                    <x-adminlte-datatable id="table2" :heads="$headsCourses" :heads="$headsCourses" :config="$configCourses" striped
                        hoverable beautify />
                </div>
            </div>

            @if (Auth::user()->hasRole('Instrutor') && (count($modules) > 0 || count($classes) > 0))
                <div class="row px-0">
                    @if (count($modules) > 0)
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-fw fa-layer-group mr-2"></i> Últimos Módulos
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

                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                        @foreach ($modules->sortBy('created_at')->reverse()->take(3) as $module)
                                            <li class="item d-flex flex-wrap">
                                                <div class="mb-1">
                                                    <img src="{{ $module->cover ? url('storage/course-modules/min/' . $module->cover) : asset('img/defaults/min/courses.webp') }}"
                                                        alt="{{ $module->name }}" class="img-thumbnail d-block"
                                                        width="180" height="103">
                                                </div>
                                                <div class="product-info">
                                                    <a href="{{ route('admin.course-modules.edit', ['course_module' => $module->id]) }}"
                                                        class="product-title">{{ $module->name }}
                                                    </a>
                                                    <span class="product-description mt-3">
                                                        Aulas: {{ $module->classes->count() }}
                                                    </span>
                                                    <span class="product-description">
                                                        Situação:
                                                        {{ $module->status }}{{ $module->active ? ' e ativado' : ' mas não ativado' }}
                                                    </span>
                                                    @if ($module->release_date)
                                                        <span class="badge badge-warning mt-2">Liberada a partir
                                                            de:
                                                            {{ date('d/m/Y', strtotime($module->release_date)) }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="card-footer text-center">
                                    <a href="{{ route('admin.course-modules.index') }}" class="uppercase">Ver todos</a>
                                </div>

                            </div>
                        </div>
                    @endif
                    @if (count($classes) > 0)
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-fw fa-chalkboard-teacher mr-2"></i> Últimas
                                        Aulas
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

                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                        @foreach ($classes->sortBy('created_at')->reverse()->take(6) as $classroom)
                                            <li class="item d-flex flex-wrap">

                                                <div class="product-info">
                                                    <a href="{{ route('admin.classes.edit', ['class' => $classroom->id]) }}"
                                                        class="product-title">{{ $classroom->name }}
                                                        @if ($classroom->release_date)
                                                            <span class="badge badge-warning float-right ml-2">Liberada a
                                                                partir de:
                                                                {{ date('d/m/Y', strtotime($classroom->release_date)) }}</span>
                                                        @endif
                                                    </a>
                                                    <span class="product-description">
                                                        Situação:
                                                        {{ $classroom->status }}{{ $classroom->active ? ' e ativado' : ' mas não ativado' }}
                                                    </span>

                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="card-footer text-center">
                                    <a href="{{ route('admin.classes.index') }}" class="uppercase">Ver todos</a>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="row px-0">
                {{-- Students by Courses --}}
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title"><i class="fa fa-bars mr-2"></i> Alunos por Curso</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <div class="chartjs-size-monitor" z>
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="student-by-course-chart" style="display: block; width: 489px; height: 200px;"
                                    class="chartjs-render-monitor" width="489" height="270"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Last Students --}}
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-user-graduate mr-2"></i> Últimos Alunos</h3>
                            <div class="card-tools">
                                <span class="badge badge-danger">
                                    {{ $total = $students->where('created_at', '>=', date('Y-m-d'))->count() }}
                                    @if ($total == 1)
                                        Aluno novo
                                    @else
                                        Aluno novos
                                    @endif
                                </span>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <ul class="users-list clearfix">
                                @foreach ($students->take(8)->reverse() as $student)
                                    <li>
                                        @if ($student->photo)
                                            <img src="{{ url('storage/users/' . $student->photo) }}"
                                                alt="{{ $student->name }}" class="img-circle img-fluid"
                                                style="object-fit: cover; width: 100%; aspect-ratio: 1;">
                                        @else
                                            <img src="{{ asset('vendor/adminlte/dist/img/avatar.png') }}"
                                                alt="{{ $student->name }}">
                                        @endif

                                        @if (Auth::user()->hasPermissionTo('Listar Alunos'))
                                            <a class="users-list-name"
                                                href="{{ route('admin.students.show', ['student' => $student->id]) }}">{{ $student->name }}</a>
                                        @else
                                            <p class="users-list-name mb-n1" href="#">{{ $student->name }}</p>
                                        @endif

                                        <span
                                            class="users-list-date">{{ date('d/m/Y', strtotime($student->created_at)) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if (Auth::user()->hasRole('Franquiado|Administrador|Programador'))
                            <div class="card-footer text-center">
                                <a href="{{ route('admin.students.index') }}">Visualizar Todos</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($posts->count() > 0 || $cheats->count() > 0)
                <div class="row px-2">
                    <div class="card col-12">
                        <div class="card-header">
                            <i class="fa fa-chart-bar mr-2"></i> Visualizações
                        </div>
                        <div class="card-body px-0 pb-0 d-flex flex-wrap justify-content-center">
                            @if ($posts->count() > 0)
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <p class="mb-0"><i class="fa fa-blog mr-2"></i> Posts</p>
                                        </div>
                                        <div class="cardy-body py-2">
                                            <div class="chart-responsive">
                                                <div class="chartjs-size-monitor">
                                                    <div class="chartjs-size-monitor-expand">
                                                        <div class=""></div>
                                                    </div>
                                                    <div class="chartjs-size-monitor-shrink">
                                                        <div class=""></div>
                                                    </div>
                                                </div>
                                                <canvas id="posts-chart"
                                                    style="display: block; width: 203px; height: 100px;"
                                                    class="chartjs-render-monitor" width="203"
                                                    height="100"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($cheats->count() > 0)
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <p class="mb-0"><i class="fa fa-th-list mr-2"></i> Cheats</p>
                                        </div>
                                        <div class="cardy-body py-2">
                                            <div class="chart-responsive">
                                                <div class="chartjs-size-monitor">
                                                    <div class="chartjs-size-monitor-expand">
                                                        <div class=""></div>
                                                    </div>
                                                    <div class="chartjs-size-monitor-shrink">
                                                        <div class=""></div>
                                                    </div>
                                                </div>
                                                <canvas id="cheats-chart"
                                                    style="display: block; width: 203px; height: 100px;"
                                                    class="chartjs-render-monitor" width="203"
                                                    height="100"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->hasRole('Programador|Administrador'))
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                            <h3 class="card-title align-self-center"><i class="fa fa-eye mr-2"></i> Acessos Diário</h3>
                        </div>
                    </div>

                    @php
                        $heads = [
                            ['label' => 'Hora', 'width' => 10],
                            'Usuário',
                            'Página',
                            'IP',
                            'User-Agent',
                            'Plataforma',
                            'Navegador',
                        ];
                        $config = [
                            'ajax' => url('/admin'),
                            'columns' => [
                                ['data' => 'time', 'name' => 'time'],
                                ['data' => 'name', 'name' => 'name'],
                                ['data' => 'url', 'name' => 'url'],
                                ['data' => 'ip', 'name' => 'ip'],
                                ['data' => 'useragent', 'name' => 'useragent'],
                                ['data' => 'platform', 'name' => 'platform'],
                                ['data' => 'browser', 'name' => 'browser'],
                            ],
                            'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                            'order' => [0, 'desc'],
                            'destroy' => true,
                            'autoFill' => true,
                            'processing' => true,
                            'serverSide' => true,
                            'responsive' => true,
                            'lengthMenu' => [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, 'Tudo']],
                            'dom' => '<"d-flex flex-wrap col-12 justify-content-between"Bf>rtip',
                            'buttons' => [
                                ['extend' => 'pageLength', 'className' => 'btn-default'],
                                [
                                    'extend' => 'copy',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-copy text-secondary"></i>',
                                    'titleAttr' => 'Copiar',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'print',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                    'titleAttr' => 'Imprimir',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'csv',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-file-csv text-primary"></i>',
                                    'titleAttr' => 'Exportar para CSV',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'excel',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-file-excel text-success"></i>',
                                    'titleAttr' => 'Exportar para Excel',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'pdf',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>',
                                    'titleAttr' => 'Exportar para PDF',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                            ],
                        ];
                    @endphp

                    <div class="card-body">
                        <x-adminlte-datatable id="tableAccess" :heads="$heads" :heads="$heads" :config="$config"
                            striped hoverable beautify />
                    </div>
                </div>

                <div class="row px-0">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title"><i class="fa fa-signal mr-2"></i> Usuários Online: <span
                                            id="onlineusers">{{ $onlineUsers }}</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    <p class="d-flex flex-column">
                                        <span class="text-bold text-lg" id="accessdaily">{{ $access }}</span>
                                        <span>Acessos Diários</span>
                                    </p>
                                    <p class="ml-auto d-flex flex-column text-right">
                                        <span id="percentclass"
                                            class="{{ $percent > 0 ? 'text-success' : 'text-danger' }}">
                                            <i id="percenticon"
                                                class="fas {{ $percent > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}  mr-1"></i><span
                                                id="percentvalue">{{ $percent }}</span>%
                                        </span>
                                        <span class="text-muted">em relação ao dia anterior</span>
                                    </p>
                                </div>

                                <div class="position-relative mb-4">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas id="visitors-chart" style="display: block; width: 489px; height: 200px;"
                                        class="chartjs-render-monitor" width="489" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </section>
@endsection

@section('custom_js')
    @include('admin.home.components.posts')
    @include('admin.home.components.cheats')
    @include('admin.home.components.student-course')
    @if (Auth::user()->hasRole('Programador|Administrador'))
        @include('admin.home.components.access')
    @endif
@endsection
