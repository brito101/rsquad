@extends('adminlte::page')

@section('title', '- Cadastro de Postagem')
@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.select2', true)
@section('plugins.BootstrapSelect', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-blog"></i> Nova Postagem</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
                        <li class="breadcrumb-item active">Nova Postagem</li>
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
                            <h3 class="card-title">Dados Cadastrais da Postagem</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="title"
                                            placeholder="Título da Postagem" name="title" value="{{ old('title') }}"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                        <label for="subtitle">Headline</label>
                                        <input type="text" class="form-control" id="subtitle" placeholder="Headline"
                                            name="subtitle" value="{{ old('subtitle') }}" required>
                                    </div>
                                </div>

                                <div class="col-12 form-group px-0">
                                    <x-adminlte-input-file name="cover"
                                        label="Imagem (preferencialmente 860 x 490 pixels)"
                                        placeholder="Selecione uma imagem..." legend="Selecionar"/>
                                </div>

                                <div class="col-12 col-md-12 form-group px-0 mb-0">
                                    @php
                                        $config = [
                                            'height' => '300',
                                            'toolbar' => [
                                                ['groupName', ['list of button']],
                                                ['style', ['bold', 'italic', 'underline', 'clear']],
                                                ['font', ['strikethrough', 'superscript', 'subscript']],
                                                ['fontsize', ['fontsize']],
                                                ['color', ['color']],
                                                ['para', ['ul', 'ol', 'paragraph']],
                                                ['height', ['height']],
                                                ['table', ['table']],
                                                ['insert', ['link', 'picture', 'video']],
                                                ['view', ['fullscreen', 'codeview', 'help']],
                                            ],
                                        ];
                                    @endphp
                                    <x-adminlte-text-editor name="content" label="Texto" igroup-size="sm"
                                        placeholder="Escreva o conteúdo do post aqui..." :config="$config">
                                        {{ old('content') }}
                                    </x-adminlte-text-editor>
                                </div>

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2 mb-0">
                                        <label for="status">Status</label>
                                        <x-adminlte-select2 name="status" required>
                                            <option {{ old('status') == 'Postado' ? 'selected' : '' }} value="Postado">Postado
                                            </option>
                                            <option {{ old('status') == 'Rascunho' ? 'selected' : '' }} value="Rascunho">Rascunho
                                            </option>
                                            <option {{ old('status') == 'Lixo' ? 'selected' : '' }} value="Lixo">Lixo
                                            </option>
                                        </x-adminlte-select2>
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

                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <x-adminlte-select-bs id="categories" name="categories[]" label="Categorias"
                                            label-class="text-dark" igroup-size="md" :config="$config" multiple
                                            class="border">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
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
