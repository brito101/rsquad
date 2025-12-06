@extends('adminlte::page')

@section('title', '- Edição de Aula')
@section('plugins.BootstrapSwitch', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.select2', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-chalkboard-teacher"></i> Editar Aula</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">Aulas</a></li>
                        <li class="breadcrumb-item active">Editar Aula</li>
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

                        <form method="POST" action="{{ route('admin.classes.update', ['class' => $classroom->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $classroom->id }}">

                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                        <label for="name">Título</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Título da Aula" name="name"
                                            value="{{ old('name') ?? $classroom->name }}" required>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Módulo</label>
                                        <x-adminlte-select2 name="course_module_id" required>
                                            @foreach ($modules as $module)
                                                <option
                                                    {{ old('course_module_id') == $module->id ? 'selected' : ($classroom->course_module_id == $module->id ? 'selected' : '') }}
                                                    value="{{ $module->id }}">
                                                    {{ $module->name }} ({{ $module->course->name }})
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 pl-md-2 mb-0">
                                        <label for="status">Status</label>
                                        <x-adminlte-select2 name="status" required>
                                            <option
                                                {{ old('status') == 'Publicado' ? 'selected' : ($classroom->status == 'Publicado' ? 'selected' : '') }}
                                                value="Publicado">
                                                Publicado
                                            </option>
                                            <option
                                                {{ old('status') == 'Rascunho' ? 'selected' : ($classroom->status == 'Rascunho' ? 'selected' : '') }}
                                                value="Rascunho">
                                                Rascunho
                                            </option>
                                            <option
                                                {{ old('status') == 'Suspenso' ? 'selected' : ($classroom->status == 'Suspenso' ? 'selected' : '') }}
                                                value="Suspenso">
                                                Suspenso
                                            </option>
                                            <option
                                                {{ old('status') == 'Cancelado' ? 'selected' : ($classroom->status == 'Cancelado' ? 'selected' : '') }}
                                                value="Cancelado">
                                                Cancelado
                                            </option>
                                            <option
                                                {{ old('status') == 'Arquivado' ? 'selected' : ($classroom->status == 'Arquivado' ? 'selected' : '') }}
                                                value="Arquivado">
                                                Arquivado
                                            </option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pr-md-2">
                                        <label for="order">Ordem</label>
                                        <input type="number" class="form-control" id="order" step="1"
                                            min="0" max="9999" placeholder="Ordem da Aula" name="order"
                                            value="{{ old('order') ?? $classroom->order }}">
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 px-md-2">
                                        <label for="link">Link <small class="text-muted">(opcional se enviar vídeo)</small></label>
                                        <input type="text" class="form-control" id="sales_link"
                                            placeholder="Link da Aula" name="link"
                                            value="{{ old(key: 'link') ?? $classroom->link }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="release_date">Data de Liberação</label>
                                        <input type="date" class="form-control" id="release_date"
                                            placeholder="Data de liberação da Aula" name="release_date"
                                            value="{{ old(key: 'release_date') ?? $classroom->release_date }}">
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label class="align-self-center mr-2">Ativo?</label>
                                        @if ($classroom->active == 1)
                                            <x-adminlte-input-switch name="active" id="active" data-on-color="success"
                                                data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                                enable-old-support checked />
                                        @else
                                            <x-adminlte-input-switch name="active" id="active" data-on-color="success"
                                                data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                                enable-old-support />
                                        @endif
                                    </div>

                                    @if($classroom->vimeo_id)
                                        <div class="col-12 form-group px-0 mb-3" id="vimeo-video-container">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0 h6 text-bold"><i class="fas fa-video"></i> Vídeo Atual no Vimeo</h5>
                                                    <a href="javascript:void(0)" 
                                                       id="delete-video-btn" 
                                                       data-url="{{ route('admin.classes.delete-video', $classroom->id) }}"
                                                       class="btn btn-sm btn-warning" 
                                                       title="Remover apenas o vídeo (a aula será mantida)">
                                                        <i class="fas fa-video-slash"></i> Remover Vídeo
                                                    </a>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                @php
                                                                    $playerUrl = $classroom->vimeo_player_url ?? 'https://player.vimeo.com/video/' . $classroom->vimeo_id;
                                                                    $playerUrl .= (strpos($playerUrl, '?') !== false ? '&' : '?') . 't=' . time();
                                                                @endphp
                                                                <iframe src="{{ $playerUrl }}" 
                                                                        class="embed-responsive-item" 
                                                                        frameborder="0" 
                                                                        allow="autoplay; fullscreen; picture-in-picture" 
                                                                        allowfullscreen>
                                                                </iframe>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p><strong>Vimeo ID:</strong> {{ $classroom->vimeo_id }}</p>                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="video">{{ $classroom->vimeo_id ? 'Substituir Vídeo da Aula' : 'Vídeo da Aula' }}</label>
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
                                        @if($classroom->vimeo_thumbnail)
                                            <div class="mt-2" id="current-thumbnail-preview">
                                                <img src="{{ $classroom->vimeo_thumbnail }}" class="img-thumbnail" style="max-width: 200px;" alt="Preview">
                                            </div>
                                        @else
                                            <div class="mt-2" id="thumbnail-preview-container" style="display: none;">
                                                <img src="" id="thumbnail-preview" class="img-thumbnail" style="max-width: 200px;" alt="Preview">
                                            </div>
                                        @endif
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

        // Delete video via AJAX
        $('#delete-video-btn').on('click', function(e) {
            e.preventDefault();
            
            if (!confirm('⚠️ ATENÇÃO: Você está removendo APENAS o VÍDEO do Vimeo.\n\nA aula será mantida e você poderá adicionar um link do YouTube no campo "Link Externo".\n\nDeseja continuar?')) {
                return;
            }
            
            const url = $(this).data('url');
            const btn = $(this);
            
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Removendo...');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    // Remove o container do vídeo da tela
                    $('#vimeo-video-container').fadeOut(300, function() {
                        $(this).remove();
                    });
                    
                    // Remove a thumbnail preview atual se existir
                    $('#current-thumbnail-preview').fadeOut(300, function() {
                        $(this).remove();
                    });
                    
                    // Mostra mensagem de sucesso
                    const alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        '<i class="fas fa-check-circle"></i> Vídeo removido com sucesso! A aula foi mantida.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span></button></div>';
                    
                    $('.content-header').after(alertHtml);
                    
                    // Remove o alerta após 5 segundos
                    setTimeout(function() {
                        $('.alert-success').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }, 5000);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fas fa-video-slash"></i> Remover Vídeo');
                    
                    const errorMsg = xhr.responseJSON?.message || 'Erro ao remover vídeo. Tente novamente.';
                    
                    const alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<i class="fas fa-exclamation-circle"></i> ' + errorMsg +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span></button></div>';
                    
                    $('.content-header').after(alertHtml);
                }
            });
        });
    </script>
@endsection
