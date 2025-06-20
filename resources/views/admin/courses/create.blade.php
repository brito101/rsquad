@extends('adminlte::page')

@section('title', '- Cadastro de Cursos')
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSwitch', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.select2', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-newspaper"></i> Novo Curso</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item active">Novo Curso</li>
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

                        <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Título da Categoria" name="name" value="{{ old('name') }}"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Status</label>
                                        @php $coursesStatus = ['Planejamento','Previsto','Em Andamento','Concluído','Disponível','Sob Demanda','Suspenso','Cancelado','Arquivado'] @endphp
                                        <x-adminlte-select2 name="status" required>
                                            @foreach ($coursesStatus as $item)
                                                <option {{ old('status') == $item ? 'selected' : '' }}
                                                    value="{{ $item }}">
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label class="align-self-center mr-2">Ativo?</label>
                                        <x-adminlte-input-switch name="active" data-on-color="success" id="active"
                                            data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                            enable-old-support />
                                    </div>

                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-textarea name="description" label="Descrição" igroup-size="md"
                                            placeholder="Descrição do curso..." rows=5>
                                            {{ old('description') }}
                                        </x-adminlte-textarea>
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

                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2 mb-0">
                                        <x-adminlte-select-bs id="categories" name="categories[]" label="Categorias"
                                            label-class="text-dark" igroup-size="md" :config="$config" multiple
                                            class="border">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <x-adminlte-select-bs id="instructors" name="instructors[]" label="Instrutores"
                                            label-class="text-dark" igroup-size="md" :config="$config" multiple
                                            class="border">
                                            @foreach ($instructors as $instructor)
                                                <option value="{{ $instructor->id }}">
                                                    {{ $instructor->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 pl-md-2 mb-0">
                                        <x-adminlte-select-bs id="monitors" name="monitors[]" label="Monitores"
                                            label-class="text-dark" igroup-size="md" :config="$config" multiple
                                            class="border">
                                            @foreach ($monitors as $monitor)
                                                <option value="{{ $monitor->id }}">
                                                    {{ $monitor->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 pr-md-2">
                                        <label for="price">Preço</label>
                                        <input type="text" class="form-control money_format_2" id="price"
                                            placeholder="Preço do Curso" name="price" value="{{ old('price') }}"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="promotional_price">Preço Promocional</label>
                                        <input type="text" class="form-control money_format_2" id="promotional_price"
                                            placeholder="Preço Promocional do Curso" name="promotional_price"
                                            value="{{ old('promotional_price') }}">
                                    </div>
                                    
                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label class="align-self-center mr-2">Promoção Ativa?</label>
                                        <x-adminlte-input-switch name="is_promotional" id="is_promotional"
                                            data-on-color="success" data-off-color="danger" data-on-text="Sim"
                                            data-off-text="Não" enable-old-support />
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 px-md-2">
                                        <label for="sales_link">Link de Venda</label>
                                        <input type="text" class="form-control" id="sales_link"
                                            placeholder="Link do site utilizado para a aquisição do curso" name="sales_link"
                                            value="{{ old('sales_link') }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label for="coupon_code">Cupom de Desconto</label>
                                        <input type="text" class="form-control" id="coupon_code" placeholder="Código"
                                            name="coupon_code" value="{{ old('coupon_code') }}">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0">
                                        <x-adminlte-input-file name="cover"
                                            label="Imagem (preferencialmente 860 x 490 pixels)"
                                            placeholder="Selecione uma imagem..." legend="Selecionar" />
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

@section('custom_js')
    <script src="{{ asset('vendor/jquery/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('js/money.js') }}"></script>
@endsection
