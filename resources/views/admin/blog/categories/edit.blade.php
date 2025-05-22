@extends('adminlte::page')

@section('title', '- Edição de Categoria do Blog')
@section('plugins.BsCustomFileInput', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-tags"></i> Editar Categoria</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog-categories.index') }}">Categorias do
                                Blog</a></li>
                        <li class="breadcrumb-item active">Editar Categoria</li>
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
                            <h3 class="card-title">Dados Cadastrais da Categoria</h3>
                        </div>

                        <form method="POST"
                            action="{{ route('admin.blog-categories.update', ['blog_category' => $category->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $category->id }}">
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="title"
                                            placeholder="Título da Categoria" name="title"
                                            value="{{ old('title') ?? $category->title }}" required>
                                    </div>
                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                        <label for="description">Descrição</label>
                                        <input type="text" class="form-control" id="description"
                                            placeholder="Descrição da Categoria" name="description"
                                            value="{{ old('description') ?? $category->description }}" required>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0">
                                        <x-adminlte-input-file name="cover"
                                            label="Imagem (preferencialmente 860 x 490 pixels)"
                                            placeholder="Selecione uma imagem..." legend="Selecionar" />
                                    </div>
                                </div>

                                @if ($category->cover)
                                    <div class='col-12 align-self-center mt-3 d-flex justify-content-center px-0'>
                                        <img src="{{ url('storage/blog-categories/' . $category->cover) }}"
                                            alt="{{ $category->title }}" title="{{ $category->title }}"
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
