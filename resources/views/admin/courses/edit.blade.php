@extends('adminlte::page')

@section('title', '- Edição de Curso')
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSwitch', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.select2', true)

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

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
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Título da Categoria" name="name"
                                            value="{{ old('name') ?? $course->name }}" required>
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Status</label>
                                        @php $coursesStatus = ['Planejamento','Previsto','Em Andamento','Concluído','Disponível','Sob Demanda','Suspenso','Cancelado','Arquivado'] @endphp
                                        <x-adminlte-select2 name="status" required>
                                            @foreach ($coursesStatus as $item)
                                                <option
                                                    {{ old('status') == $item ? 'selected' : ($course->status == $item ? 'selected' : '') }}
                                                    value="{{ $item }}">
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
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

                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-textarea name="description" label="Descrição" igroup-size="md"
                                            placeholder="Descrição do curso..." rows=5>
                                            {{ old('description') ?? $course->description }}
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
                                                <option value="{{ $category->id }}"
                                                    {{ in_array($category->id, $course->categories->pluck('category_course_id')->toArray()) ? 'selected' : '' }}>
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
                                                <option value="{{ $instructor->id }}"
                                                    {{ in_array($instructor->id, $course->instructors->pluck('user_id')->toArray()) ? 'selected' : '' }}>
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
                                                <option value="{{ $monitor->id }}"
                                                    {{ in_array($monitor->id, $course->monitors->pluck('user_id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $monitor->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                    
                                    <div class="col-12 col-md-2 form-group px-0 pr-md-2">
                                        <label for="total_hours">Carga horária (horas)</label>
                                        <input type="number" class="form-control" id="total_hours" name="total_hours"
                                            min="1" step="1" value="{{ old('total_hours') ?? $course->total_hours }}" placeholder="e.g. 40" required>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="price">Preço</label>
                                        <input type="text" class="form-control money_format_2" id="price"
                                            placeholder="Preço do Curso" name="price"
                                            value="{{ old('price') ?? number_format($course->price, 2, ',', '.') }}"
                                            required>
                                    </div>
                                    
                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="promotional_price">Preço Promocional</label>
                                        <input type="text" class="form-control money_format_2" id="promotional_price"
                                            placeholder="Preço Promocional do Curso" name="promotional_price"
                                            value="{{ old('promotional_price') ?? number_format($course->promotional_price, 2, ',', '.') }}">
                                    </div>
                                    
                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label class="align-self-center mr-2">Promoção Ativa?</label>
                                        @if ($course->is_promotional == 1)
                                            <x-adminlte-input-switch name="is_promotional" id="is_promotional"
                                                data-on-color="success" data-off-color="danger" data-on-text="Sim"
                                                data-off-text="Não" enable-old-support checked />
                                        @else
                                            <x-adminlte-input-switch name="is_promotional" id="is_promotional"
                                                data-on-color="success" data-off-color="danger" data-on-text="Sim"
                                                data-off-text="Não" enable-old-support />
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 px-md-2">
                                        <label for="sales_link">Link de Venda</label>
                                        <input type="text" class="form-control" id="sales_link"
                                            placeholder="Link do site utilizado para a aquisição do curso"
                                            name="sales_link" value="{{ old('sales_link') ?? $course->sales_link }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 pr-md-2">
                                        <label for="coupon_code">Cupom de Desconto</label>
                                        <input type="text" class="form-control" id="coupon_code" placeholder="Código"
                                            name="coupon_code" value="{{ old('coupon_code') ?? $course->coupon_code }}">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0">
                                        <x-adminlte-input-file id="cover" name="cover"
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

                                <!-- Badge Section -->
                                <div class="d-flex flex-wrap justify-content-start mt-3">
                                    <div class="col-12 px-0 mb-2">
                                        <h5 class="text-muted"><i class="fas fa-medal mr-2"></i>Badge de Conclusão</h5>
                                        <small class="text-muted">Badge será conquistada pelo aluno ao completar 100% do curso.</small>
                                    </div>
                                    
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="badge_name">Nome da Badge</label>
                                        <input type="text" class="form-control" id="badge_name" 
                                            placeholder="Ex: Expert em Laravel" name="badge_name" 
                                            value="{{ old('badge_name') ?? $course->badge_name }}">
                                        <small class="form-text text-muted">Nome que aparecerá no LinkedIn ao compartilhar.</small>
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                        <x-adminlte-input-file id="badge_image" name="badge_image"
                                            label="Imagem da Badge (preferencialmente 400 x 400 pixels)"
                                            placeholder="Selecione uma imagem..." legend="Selecionar" />
                                    </div>

                                    @if ($course->badge_image)
                                        <div class='col-12 col-md-6 align-self-center mt-2 d-flex justify-content-center px-0 pr-md-2'>
                                            <img src="{{ url('storage/badges/' . $course->badge_image) }}"
                                                alt="Badge: {{ $course->badge_name }}" title="{{ $course->badge_name }}"
                                                class="img-thumbnail d-block" style="max-width: 200px;">
                                        </div>
                                    @endif
                                </div>

                                <!-- PDF Section -->
                                <div class="d-flex flex-wrap justify-content-start mt-3">
                                    <div class="col-12 px-0 mb-2">
                                        <h5 class="text-muted"><i class="fas fa-file-pdf mr-2"></i>Material em PDF</h5>
                                        <small class="text-muted">Alunos matriculados poderão baixar com marca d'água personalizada.</small>
                                    </div>

                                    <div class="col-12 form-group px-0">
                                        <x-adminlte-input-file id="pdf_file" name="pdf_file"
                                            label="Material em PDF (máximo 50MB)"
                                            placeholder="Selecione um arquivo PDF..." legend="Selecionar" />
                                    </div>

                                    @if ($course->pdf_file)
                                        <div class='col-12 px-0'>
                                            <div class="alert alert-light">
                                                <i class="fas fa-file-pdf mr-2"></i>
                                                <strong>PDF atual:</strong> {{ $course->pdf_file }}
                                            </div>
                                            <div class="card-body icheck-primary">
                                                <input type="checkbox" style="cursor: pointer" id="remove_pdf"
                                                    name="remove_pdf" value="1">
                                                <label for="remove_pdf" class="my-0 ml-2"><i
                                                        class="fas fa-trash text-danger"></i> Remover arquivo PDF
                                                    atual</label>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
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
