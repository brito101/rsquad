@extends('adminlte::page')

@section('title', '- Cadastro de Categoria do Cheat Sheet')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-tags"></i> Novo Categoria</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cheat-sheets.index') }}">Cheat Sheets</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cheat-sheets-categories.index') }}">Categorias
                                do
                                Cheat Sheet</a></li>
                        <li class="breadcrumb-item active">Nova Categoria</li>
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

                        <form method="POST" action="{{ route('admin.cheat-sheets-categories.store') }}">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="title"
                                            placeholder="Título da Categoria" name="title" value="{{ old('title') }}"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                        <label for="description">Descrição</label>
                                        <input type="text" class="form-control" id="description"
                                            placeholder="Descrição da Categoria" name="description"
                                            value="{{ old('description') }}" required>
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
