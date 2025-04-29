@extends('adminlte::page')

@section('title', '- Edição de Módulo')
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
                    <h1><i class="fas fa-fw fa-layer-group"></i> Editar Módulo</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.course-modules.index') }}">Módulos</a></li>
                        <li class="breadcrumb-item active">Editar Módulo</li>
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

                        <form method="POST"
                            action="{{ route('admin.course-modules.update', ['course_module' => $module->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $module->id }}">

                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Título da Aula" name="name"
                                            value="{{ old('name') ?? $module->name }}" required>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Curso</label>
                                        <x-adminlte-select2 name="course_id" required>
                                            @foreach ($courses as $course)
                                                <option
                                                    {{ old('course_id') == $course->id ? 'selected' : ($module->course_id == $course->id ? 'selected' : '') }}
                                                    value="{{ $course->id }}">
                                                    {{ $course->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 pl-md-2 mb-0">
                                        <label for="status">Status</label>
                                        <x-adminlte-select2 name="status" required>
                                            <option
                                                {{ old('status') == 'Publicado' ? 'selected' : ($module->status == 'Publicado' ? 'selected' : '') }}
                                                value="Publicado">
                                                Publicado
                                            </option>
                                            <option
                                                {{ old('status') == 'Rascunho' ? 'selected' : ($module->status == 'Rascunho' ? 'selected' : '') }}
                                                value="Rascunho">
                                                Rascunho
                                            </option>
                                            <option
                                                {{ old('status') == 'Suspenso' ? 'selected' : ($module->status == 'Suspenso' ? 'selected' : '') }}
                                                value="Suspenso">
                                                Suspenso
                                            </option>
                                            <option
                                                {{ old('status') == 'Cancelado' ? 'selected' : ($module->status == 'Cancelado' ? 'selected' : '') }}
                                                value="Cancelado">
                                                Cancelado
                                            </option>
                                            <option
                                                {{ old('status') == 'Arquivado' ? 'selected' : ($module->status == 'Arquivado' ? 'selected' : '') }}
                                                value="Arquivado">
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
                                            {!! old('description') ?? $module->description !!}
                                        </x-adminlte-text-editor>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pr-md-2">
                                        <label for="order">Ordem</label>
                                        <input type="number" class="form-control" id="order" step="1"
                                            min="0" max="9999" placeholder="Ordem do Módulo" name="order"
                                            value="{{ old('order') ?? $module->order }}">
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 px-md-2">
                                        <label for="link">Link</label>
                                        <input type="text" class="form-control" id="sales_link"
                                            placeholder="Link do Módulo" name="link"
                                            value="{{ old(key: 'link') ?? $module->link }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="release_date">Data de Liberação</label>
                                        <input type="date" class="form-control" id="release_date"
                                            placeholder="Data de liberação do Módulo" name="release_date"
                                            value="{{ old(key: 'release_date') ?? $module->release_date }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label class="align-self-center mr-2">Ativo?</label>
                                        @if ($module->active == 1)
                                            <x-adminlte-input-switch name="active" id="active" data-on-color="success"
                                                data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                                enable-old-support checked />
                                        @else
                                            <x-adminlte-input-switch name="active" id="active" data-on-color="success"
                                                data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                                enable-old-support />
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0">
                                        <x-adminlte-input-file name="cover"
                                            label="Imagem (preferencialmente 860 x 490 pixels)"
                                            placeholder="Selecione uma imagem..." legend="Selecionar" />
                                    </div>
                                </div>

                                @if ($module->cover)
                                    <div class='col-12 align-self-center mt-3 d-flex justify-content-center px-0'>
                                        <img src="{{ url('storage/course-modules/' . $module->cover) }}"
                                            alt="{{ $module->name }}" title="{{ $module->name }}"
                                            class="img-thumbnail d-block">
                                    </div>
                                @endif
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
