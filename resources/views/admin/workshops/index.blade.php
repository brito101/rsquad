@extends('adminlte::page')

@section('title', '- Workshops')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-chalkboard-teacher"></i> Workshops</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Workshops</li>
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
                                <h3 class="card-title align-self-center">Workshops Cadastrados</h3>
                                @can('Criar Workshops')
                                    <a href="{{ route('admin.workshops.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus mr-1"></i> Novo Workshop
                                    </a>
                                @endcan
                            </div>
                        </div>

                        @php
                            $heads = [
                                ['label' => 'ID', 'width' => 5],
                                'Título',
                                'Autor',
                                ['label' => 'Visibilidade', 'width' => 10],
                                ['label' => 'Status', 'width' => 10],
                                ['label' => 'Vídeo', 'width' => 10],
                                ['label' => 'Destaque', 'width' => 10],
                                ['label' => 'Agendamento', 'width' => 12],
                                ['label' => 'Ações', 'no-export' => true, 'width' => 10],
                            ];
                            $config = [
                                'ajax' => route('admin.workshops.index'),
                                'columns' => [
                                    ['data' => 'id', 'name' => 'id'],
                                    ['data' => 'title', 'name' => 'title'],
                                    ['data' => 'author', 'name' => 'author'],
                                    ['data' => 'visibility', 'name' => 'is_public'],
                                    ['data' => 'status_badge', 'name' => 'status'],
                                    ['data' => 'video_type_badge', 'name' => 'video_type'],
                                    ['data' => 'featured_badge', 'name' => 'featured', 'orderable' => true, 'searchable' => false],
                                    ['data' => 'scheduled_at_formatted', 'name' => 'scheduled_at', 'orderData' => 9],
                                    [
                                        'data' => 'action',
                                        'name' => 'action',
                                        'orderable' => false,
                                        'searchable' => false,
                                    ],
                                    ['data' => 'scheduled_at_sort', 'name' => 'scheduled_at_sort', 'visible' => false, 'searchable' => false],
                                ],
                                'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                                'order' => [[9, 'desc']],
                            ];
                        @endphp

                        <div class="card-body">
                            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped
                                hoverable beautify with-buttons />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
