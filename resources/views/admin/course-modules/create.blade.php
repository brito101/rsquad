@extends('adminlte::page')

@section('title', '- Cadastro de Módulo')
@section('plugins.BootstrapSwitch', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.select2', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-layer-group"></i> Novo Módulo</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.course-modules.index') }}">Módulos</a></li>
                        <li class="breadcrumb-item active">Novo Módulo</li>
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
                            <h3 class="card-title">Dados Cadastrais do Módulo</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.course-modules.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Título do Módulo" name="name" value="{{ old('name') }}"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Curso</label>
                                        <x-adminlte-select2 name="course_id" required>
                                            @foreach ($courses as $course)
                                                <option {{ old('course_id') == $course->id ? 'selected' : '' }}
                                                    value="{{ $course->id }}">
                                                    {{ $course->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>                                    

                                    <div class="col-12 col-md-4 form-group px-0 pl-md-2 mb-0">
                                        <label for="status">Status</label>
                                        <x-adminlte-select2 name="status" required>
                                            <option {{ old('status') == 'Publicado' ? 'selected' : '' }} value="Publicado">
                                                Publicado
                                            </option>
                                            <option {{ old('status') == 'Rascunho' ? 'selected' : '' }} value="Rascunho">
                                                Rascunho
                                            </option>
                                            <option {{ old('status') == 'Suspenso' ? 'selected' : '' }} value="Suspenso">
                                                Suspenso
                                            </option>
                                            <option {{ old('status') == 'Cancelado' ? 'selected' : '' }} value="Cancelado">
                                                Cancelado
                                            </option>
                                            <option {{ old('status') == 'Arquivado' ? 'selected' : '' }} value="Arquivado">
                                                Arquivado
                                            </option>
                                        </x-adminlte-select2>
                                    </div>

                                    @php
                                        $configText = [
                                            'height' => '100',
                                            'toolbar' => [
                                                // [groupName, [list of button]]
                                                ['style', ['style']],
                                                ['font', ['bold', 'underline', 'clear']],
                                                ['fontsize', ['fontsize']],
                                                ['fontname', ['fontname']],
                                                ['color', ['color']],
                                                ['para', ['ul', 'ol', 'paragraph']],
                                                ['height', ['height']],
                                                ['table', ['table']],
                                                ['insert', ['link', 'picture', 'video']],
                                                ['view', ['fullscreen', 'codeview', 'help']],
                                            ],
                                            'inheritPlaceholder' => true,
                                        ];
                                    @endphp

                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="description" label="Descrição" igroup-size="md"
                                            placeholder="Descrição do módulo..." :config="$configText">
                                            {!! old('description') !!}
                                        </x-adminlte-text-editor>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pr-md-2">
                                        <label for="order">Ordem</label>
                                        <input type="number" class="form-control" id="order" step="1"
                                            min="0" max="9999" placeholder="Ordem do Módulo" name="order"
                                            value="{{ old('order') }}">
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 px-md-2">
                                        <label for="link">Link</label>
                                        <input type="text" class="form-control" id="sales_link"
                                            placeholder="Link do Módulo" name="link" value="{{ old(key: 'link') }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="release_date">Data de Liberação</label>
                                        <input type="date" class="form-control" id="release_date"
                                            placeholder="Data de liberação do Módulo" name="release_date"
                                            value="{{ old(key: 'release_date') }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label class="align-self-center mr-2">Ativo?</label>
                                        <x-adminlte-input-switch name="active" data-on-color="success"
                                            data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                            enable-old-support />
                                    </div>

                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0">
                                        <x-adminlte-input-file name="cover"
                                            label="Imagem (preferencialmente 860 x 490 pixels)"
                                            placeholder="Selecione uma imagem..." legend="Selecionar" />
                                    </div>

                                    <div class="col-12 form-group px-0">
                                        <x-adminlte-input-file id="pdf_file" name="pdf_file"
                                            label="Material em PDF (máximo 50MB)"
                                            placeholder="Selecione um arquivo PDF..." legend="Selecionar" />
                                        <small class="form-text text-muted">Os alunos poderão baixar este PDF com marca d'água personalizada.</small>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
