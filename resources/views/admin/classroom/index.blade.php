@extends('adminlte::page')

@section('title', '- Aulas')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-chalkboard-teacher"></i> Aulas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item active">Aulas</li>
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
                            <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                                <h3 class="card-title align-self-center">Aulas Cadastradas</h3>
                                @can('Criar Aulas')
                                    <a href="{{ route('admin.classes.create') }}" title="Nova Aula" class="btn btn-success"><i
                                            class="fas fa-fw fa-plus"></i>Nova Aula</a>
                                @endcan
                            </div>
                        </div>

                        @php
                            $heads = [
                                ['label' => 'ID', 'width' => 10],
                                'Nome',
                                'Ordem',
                                'Curso',
                                'Módulo',
                                'Status',
                                'Ativo',
                                'Data liberação',
                                ['label' => 'Data_BR', 'no-export' => true],
                                ['label' => 'Ações', 'no-export' => true, 'width' => 10],
                            ];
                            $config = [
                                'ajax' => route('admin.classes.index'),
                                'columns' => [
                                    ['data' => 'id', 'name' => 'id'],
                                    ['data' => 'name', 'name' => 'name'],
                                    ['data' => 'order', 'name' => 'order'],
                                    ['data' => 'course', 'name' => 'course'],
                                    ['data' => 'module', 'name' => 'module'],
                                    ['data' => 'status', 'name' => 'status'],
                                    ['data' => 'active', 'name' => 'active'],
                                    [
                                        'data' => 'release_date_br',
                                        'name' => 'release_date',
                                    ],
                                    ['data' => 'release_date_br', 'name' => 'release_date_br', 'visible' => false],
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
                            <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads" :config="$config"
                                striped hoverable beautify />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
