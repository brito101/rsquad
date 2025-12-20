@extends('adminlte::page')

@section('title', '- Novo Workshop')
@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.select2', true)
@section('plugins.BootstrapSwitch', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-chalkboard-teacher"></i> Novo Workshop</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.workshops.index') }}">Workshops</a></li>
                        <li class="breadcrumb-item active">Novo Workshop</li>
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
                            <h3 class="card-title">Dados do Workshop</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.workshops.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label for="title">Título <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title') }}" 
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
                                                  placeholder="Breve descrição do workshop">{{ old('description') }}</textarea>
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
                                            {{ old('content') }}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group">
                                        <x-adminlte-input-file name="cover"
                                            label="Imagem de Capa (recomendado 1200 x 600 pixels)"
                                            placeholder="Selecione uma imagem..." legend="Selecionar"/>
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
                                            <option value="none" {{ old('video_type', 'none') == 'none' ? 'selected' : '' }}>Sem Vídeo</option>
                                            <option value="youtube" {{ old('video_type') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                            <option value="vimeo" {{ old('video_type') == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="video_url">URL do Vídeo</label>
                                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                               id="video_url" name="video_url" value="{{ old('video_url') }}" 
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
                                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Rascunho</option>
                                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Agendado</option>
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publicado</option>
                                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Arquivado</option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="is_public">Visibilidade <span class="text-danger">*</span></label>
                                        <x-adminlte-select2 name="is_public" id="is_public" enable-old-support>
                                            <option value="1" {{ old('is_public', '0') == '1' ? 'selected' : '' }}>Público</option>
                                            <option value="0" {{ old('is_public', '0') == '0' ? 'selected' : '' }}>Somente Alunos</option>
                                        </x-adminlte-select2>
                                        <small class="form-text text-muted">Público: visível para todos | Somente Alunos: apenas na área do aluno</small>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="duration">Duração (minutos)</label>
                                        <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                               id="duration" name="duration" value="{{ old('duration') }}" 
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
                                               id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                                        <small class="form-text text-muted">Deixe vazio se não for agendar</small>
                                        @error('scheduled_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="featured">Destacar Workshop</label>
                                        <div>
                                            <x-adminlte-input-switch name="featured" id="featured"
                                                data-on-text="Sim" data-off-text="Não" data-on-color="success"
                                                enable-old-support />
                                            <small class="form-text text-muted">Workshops destacados aparecem com prioridade</small>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Salvar
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
