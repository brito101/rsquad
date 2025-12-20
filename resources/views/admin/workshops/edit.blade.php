@extends('adminlte::page')

@section('title', '- Editar Workshop')
@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.select2', true)
@section('plugins.BootstrapSwitch', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-chalkboard-teacher"></i> Editar Workshop</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.workshops.index') }}">Workshops</a></li>
                        <li class="breadcrumb-item active">Editar</li>
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
                            <h3 class="card-title">Editar Dados do Workshop</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.workshops.update', ['workshop' => $workshop->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label for="title">Título <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $workshop->title) }}" 
                                               placeholder="Título do Workshop" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label for="description">Descrição Curta</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3" 
                                                  placeholder="Breve descrição do workshop">{{ old('description', $workshop->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group">
                                        @php
                                            $config = [
                                                'height' => '300',
                                                'toolbar' => [
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
                                        <x-adminlte-text-editor name="content" label="Conteúdo Completo" 
                                            placeholder="Escreva o conteúdo detalhado do workshop aqui..." :config="$config">
                                            {{ old('content', $workshop->content) }}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group">
                                        @if($workshop->cover)
                                            <div class="mb-2">
                                                <label>Capa Atual:</label><br>
                                                <img src="{{ asset($workshop->cover) }}" alt="Capa" style="max-width: 300px; height: auto;" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <x-adminlte-input-file name="cover"
                                            label="Nova Imagem de Capa (recomendado 1200 x 600 pixels)"
                                            placeholder="Selecione uma nova imagem..." legend="Selecionar"/>
                                        <small class="form-text text-muted">Deixe vazio para manter a imagem atual</small>
                                        @error('cover')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr>
                                <h5>Configurações de Vídeo</h5>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="video_type">Tipo de Vídeo <span class="text-danger">*</span></label>
                                        <x-adminlte-select2 name="video_type" id="video_type" enable-old-support>
                                            <option value="none" {{ old('video_type', $workshop->video_type) == 'none' ? 'selected' : '' }}>Sem Vídeo</option>
                                            <option value="youtube" {{ old('video_type', $workshop->video_type) == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                            <option value="vimeo" {{ old('video_type', $workshop->video_type) == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="video_url">URL do Vídeo</label>
                                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                               id="video_url" name="video_url" value="{{ old('video_url', $workshop->video_url) }}" 
                                               placeholder="https://www.youtube.com/watch?v=...">
                                        <small class="form-text text-muted">Cole a URL completa do vídeo do YouTube ou Vimeo</small>
                                        @error('video_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr>
                                <h5>Configurações de Publicação</h5>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <x-adminlte-select2 name="status" id="status" enable-old-support>
                                            <option value="draft" {{ old('status', $workshop->status) == 'draft' ? 'selected' : '' }}>Rascunho</option>
                                            <option value="scheduled" {{ old('status', $workshop->status) == 'scheduled' ? 'selected' : '' }}>Agendado</option>
                                            <option value="published" {{ old('status', $workshop->status) == 'published' ? 'selected' : '' }}>Publicado</option>
                                            <option value="archived" {{ old('status', $workshop->status) == 'archived' ? 'selected' : '' }}>Arquivado</option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="is_public">Visibilidade <span class="text-danger">*</span></label>
                                        <x-adminlte-select2 name="is_public" id="is_public" enable-old-support>
                                            <option value="1" {{ old('is_public', $workshop->is_public) == '1' ? 'selected' : '' }}>Público</option>
                                            <option value="0" {{ old('is_public', $workshop->is_public) == '0' ? 'selected' : '' }}>Somente Alunos</option>
                                        </x-adminlte-select2>
                                        <small class="form-text text-muted">Público: visível para todos | Somente Alunos: apenas na área do aluno</small>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="duration">Duração (minutos)</label>
                                        <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                               id="duration" name="duration" value="{{ old('duration', $workshop->duration) }}" 
                                               placeholder="Ex: 60" min="1">
                                        @error('duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="scheduled_at">Data/Hora de Agendamento</label>
                                        <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                               id="scheduled_at" name="scheduled_at" 
                                               value="{{ old('scheduled_at', $workshop->scheduled_at ? $workshop->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                        <small class="form-text text-muted">Deixe vazio se não for agendar</small>
                                        @error('scheduled_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="featured">Destacar Workshop</label>
                                        <div>
                                            @if($workshop->featured)
                                                <x-adminlte-input-switch name="featured" id="featured"
                                                    data-on-text="Sim" data-off-text="Não" data-on-color="success"
                                                    checked enable-old-support />
                                            @else
                                                <x-adminlte-input-switch name="featured" id="featured"
                                                    data-on-text="Sim" data-off-text="Não" data-on-color="success"
                                                    enable-old-support />
                                            @endif
                                            <small class="form-text text-muted">Workshops destacados aparecem com prioridade</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <small class="text-muted">
                                            <strong>Criado por:</strong> {{ $workshop->user->name }} em {{ $workshop->created_at->format('d/m/Y H:i') }}
                                            @if($workshop->created_at != $workshop->updated_at)
                                                | <strong>Última atualização:</strong> {{ $workshop->updated_at->format('d/m/Y H:i') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Salvar Alterações
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
