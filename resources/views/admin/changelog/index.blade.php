@extends('adminlte::page')

@section('title', '- Changelog')

@section('adminlte_css')
    <style>
        .callout-primary {
            border-color: #024BA9;
        }
    </style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa fa-fw fa-code"></i> Changelog</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Changelog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histórico do Sistema</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-6 order-2 order-md-1 overflow-auto" style="max-height: 65vh;">
                        <div class="row">
                            <div class="col-12">
                                <h4>Versionamento:</h4>

                                <x-adminlte-callout theme="primary" class="elevation-2" title-class="text-uppercase"
                                    title="1.0.0">
                                    <p>Sistema com os seguintes módulos:</p>
                                    <ul>
                                        <li>ACL com perfis e permissões</li>
                                        <li>Usuários com duplo fator de autenticação (2FA)</li>
                                        <li>Changelog</li>
                                        <li>Categorias de Cursos</li>
                                        <li>Cursos com categorias, módulos e aulas</li>
                                        <li>Alunos</li>
                                        <li>Blog com múltiplas categorias</li>
                                        <li>Cheat Sheets</li>
                                    </ul>
                                </x-adminlte-callout>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-6 order-1 order-md-2 overflow-auto" style="max-height: 65vh;">

                        <x-adminlte-profile-widget name="{{ env('APP_NAME') }}" desc="{{ env('APP_DESC') }}"
                            theme="bg-gradient-dark" img="{{ asset('img/logo-100.webp') }}" layout-type="classic">
                            <x-adminlte-profile-col-item title="PHP" icon="fab fa-2x fa-php text-primary" ssize=4 />
                            <x-adminlte-profile-col-item title="Laravel" icon="fab fa-2x fa-laravel text-primary" size=4 />
                            <x-adminlte-profile-col-item title="Javascript" icon="fab fa-2x fa-js text-primary" size=4 />
                            <x-adminlte-profile-col-item title="HTML5" icon="fab fa-2x fa-html5 text-primary" size=4 />
                            <x-adminlte-profile-col-item title="CSS3" icon="fab fa-2x fa-css3 text-primary" size=4 />
                            <x-adminlte-profile-col-item title="Bootstrap" icon="fab fa-2x fa-bootstrap text-primary"
                                size=4 />
                        </x-adminlte-profile-widget>
                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection
