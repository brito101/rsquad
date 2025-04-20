@extends('adminlte::page')

@section('title', '- Edição de Curso')
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSwitch', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.select2', true)
@section('plugins.Summernote', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-newspaper"></i> Editar Curso</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item active">Editar Curso</li>
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
                            <h3 class="card-title">Dados Cadastrais do Curso</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.courses.update', ['course' => $course->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $course->id }}">
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-8 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Título da Categoria" name="name"
                                            value="{{ old('name') ?? $course->name }}" required>
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 pl-md-2 mb-0">
                                        <label for="status">Status</label>
                                        <x-adminlte-select2 name="status" required>
                                            <option
                                                {{ old('status') == 'Publicado' ? 'selected' : ($course->status == 'Publicado' ? 'selected' : '') }}
                                                value="Publicado">
                                                Publicado
                                            </option>
                                            <option
                                                {{ old('status') == 'Rascunho' ? 'selected' : ($course->status == 'Rascunho' ? 'selected' : '') }}
                                                value="Rascunho">
                                                Rascunho
                                            </option>
                                            <option
                                                {{ old('status') == 'Suspenso' ? 'selected' : ($course->status == 'Suspenso' ? 'selected' : '') }}
                                                value="Suspenso">
                                                Suspenso
                                            </option>
                                            <option
                                                {{ old('status') == 'Cancelado' ? 'selected' : ($course->status == 'Cancelado' ? 'selected' : '') }}
                                                value="Cancelado">
                                                Cancelado
                                            </option>
                                            <option
                                                {{ old('status') == 'Arquivado' ? 'selected' : ($course->status == 'Arquivado' ? 'selected' : '') }}
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
                                            placeholder="Descrição do curso..." :config="$configText">
                                            {!! old('description') ?? $course->description !!}
                                        </x-adminlte-text-editor>
                                    </div>

                                    @php
                                        $config = [
                                            'title' => 'Selecione múltiplos...',
                                            'liveSearch' => true,
                                            'liveSearchPlaceholder' => 'Pesquisar...',
                                            'showTick' => true,
                                            'actionsBox' => true,
                                        ];
                                    @endphp

                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2 mb-0">
                                        <x-adminlte-select-bs id="categories" name="categories[]" label="Categorias"
                                            label-class="text-dark" igroup-size="md" :config="$config" multiple
                                            class="border">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ in_array($category->id, $course->categories->pluck('category_course_id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2 mb-0">
                                        <x-adminlte-select-bs id="authors" name="authors[]" label="Autores"
                                            label-class="text-dark" igroup-size="md" :config="$config" multiple
                                            class="border">
                                            @foreach ($authors as $author)
                                                <option value="{{ $author->id }}"
                                                    {{ in_array($author->id, $course->authors->pluck('user_id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $author->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                    <div class="col-12 col-md-8 form-group px-0 pr-md-2">
                                        <label for="sales_link">Link de Venda</label>
                                        <input type="text" class="form-control" id="sales_link"
                                            placeholder="Link do site utilizado para a aquisição do curso" name="sales_link"
                                            value="{{ old('sales_link') ?? $course->sales_link }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label class="align-self-center mr-2">Ativo?</label>
                                        @if ($course->active == 1)
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

                                @if ($course->cover)
                                    <div class='col-12 align-self-center mt-3 d-flex justify-content-center px-0'>
                                        <img src="{{ url('storage/courses/' . $course->cover) }}"
                                            alt="{{ $course->name }}" title="{{ $course->name }}"
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
