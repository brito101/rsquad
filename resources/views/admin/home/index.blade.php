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
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-newspaper"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Cursos</span>
                                <span class="info-box-number">{{ $courses }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Alunos</span>
                                <span class="info-box-number">{{ $students }}</span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="row px-2">
                <div class="card col-12">
                    <div class="card-header">
                        <i class="fa fa-chart-bar mr-2"></i> Gráficos
                    </div>
                    <div class="card-body px-0 pb-0 d-flex flex-wrap justify-content-center">
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <p class="mb-0">Posts</p>
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
                                        <canvas id="posts-chart" style="display: block; width: 203px; height: 100px;"
                                            class="chartjs-render-monitor" width="203" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <p class="mb-0">Cheats</p>
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
                                        <canvas id="cheats-chart" style="display: block; width: 203px; height: 100px;"
                                            class="chartjs-render-monitor" width="203" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                        <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads" :config="$config" striped
                            hoverable beautify />
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
                                        <span id="percentclass" class="{{ $percent > 0 ? 'text-success' : 'text-danger' }}">
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
    @if (Auth::user()->hasRole('Programador|Administrador'))
        @include('admin.home.components.access')
    @endif
@endsection
