@extends('adminlte::page')

@section('title', '- Cadastro de Aula')
@section('plugins.BootstrapSwitch', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.select2', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-chalkboard-teacher"></i> Nova Aula</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">Aulas</a></li>
                        <li class="breadcrumb-item active">Nova Aula</li>
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
                            <h3 class="card-title">Dados Cadastrais da Aula</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.classes.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Título da Aula" name="name" value="{{ old('name') }}"
                                            required>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Módulo</label>
                                        <x-adminlte-select2 name="course_module_id" required>
                                            @foreach ($modules as $module)
                                                <option {{ old('course_module_id') == $module->id ? 'selected' : '' }}
                                                    value="{{ $module->id }}">
                                                    {{ $module->name }} ({{ $module->course->name }})
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

                                    <div class="col-12 col-md-2 form-group px-0 pr-md-2">
                                        <label for="order">Ordem</label>
                                        <input type="number" class="form-control" id="order" step="1"
                                            min="0" max="9999" placeholder="Ordem do Módulo" name="order"
                                            value="{{ old('order') }}">
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 px-md-2">
                                        <label for="link">Link <small class="text-muted">(opcional se enviar vídeo)</small></label>
                                        <input type="text" class="form-control" id="sales_link"
                                            placeholder="Link do Módulo" name="link" value="{{ old(key: 'link') }}">
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="release_date">Data de Liberação</label>
                                        <input type="date" class="form-control" id="release_date"
                                            placeholder="Data de liberação da Aula" name="release_date"
                                            value="{{ old(key: 'release_date') }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label class="align-self-center mr-2">Ativo?</label>
                                        <x-adminlte-input-switch name="active" data-on-color="success"
                                            data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                            enable-old-support />
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="video">Vídeo da Aula</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="video" name="video" accept="video/*">
                                            <label class="custom-file-label" for="video">Escolher arquivo de vídeo...</label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Formatos aceitos: MP4, MOV, AVI. O vídeo será enviado para o Vimeo.
                                        </small>
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                        <label for="thumbnail">Thumbnail Personalizada <small class="text-muted">(opcional)</small></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail" accept="image/*">
                                            <label class="custom-file-label" for="thumbnail">Escolher imagem...</label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Formatos aceitos: JPG, PNG, GIF. Se não enviar, usará a thumbnail automática do Vimeo.
                                        </small>
                                        <div class="mt-2" id="thumbnail-preview-container" style="display: none;">
                                            <img src="" id="thumbnail-preview" class="img-thumbnail" style="max-width: 200px;" alt="Preview">
                                        </div>
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

@section('js')
    <script>
        // Update file input label with selected filename
        $('#video').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Escolher arquivo de vídeo...');
        });

        $('#thumbnail').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Escolher imagem...');
            
            // Preview thumbnail
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#thumbnail-preview').attr('src', e.target.result);
                    $('#thumbnail-preview-container').show();
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
