@extends('adminlte::page')

@section('title', '- Depoimentos')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-comment-dots"></i> Depoimentos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Depoimentos</li>
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
                                <h3 class="card-title align-self-center">Depoimentos dos Alunos</h3>
                            </div>
                        </div>

                        @php
                            $heads = [
                                ['label' => 'ID', 'width' => 5],
                                'Aluno',
                                'Curso',
                                ['label' => 'Avaliação', 'width' => 10],
                                ['label' => 'Rating', 'width' => 0, 'classes' => 'd-none'],
                                ['label' => 'Status', 'width' => 10],
                                ['label' => 'Destaque', 'width' => 10],
                                ['label' => 'Data', 'width' => 12],
                                ['label' => 'Ações', 'no-export' => true, 'width' => 10],
                            ];
                            $config = [
                                'ajax' => route('admin.testimonials.index'),
                                'columns' => [
                                    ['data' => 'id', 'name' => 'id'],
                                    ['data' => 'student', 'name' => 'student'],
                                    ['data' => 'course_name', 'name' => 'course_name'],
                                    ['data' => 'rating_stars', 'name' => 'rating_stars', 'orderable' => true, 'searchable' => false, 'orderData' => [4]],
                                    ['data' => 'rating', 'name' => 'rating', 'visible' => false, 'searchable' => false],
                                    ['data' => 'status_badge', 'name' => 'status'],
                                    ['data' => 'featured_badge', 'name' => 'featured', 'orderable' => true, 'searchable' => false],
                                    ['data' => 'created_at_formatted', 'name' => 'created_at_formatted'],
                                    [
                                        'data' => 'action',
                                        'name' => 'action',
                                        'orderable' => false,
                                        'searchable' => false,
                                    ],
                                ],
                                'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                                'order' => [[7, 'desc']],
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
