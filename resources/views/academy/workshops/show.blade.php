@extends('adminlte::page')

@section('title', '- ' . $workshop->title)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-chalkboard-teacher"></i> {{ $workshop->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('academy.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('academy.workshops.index') }}">Workshops</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($workshop->title, 30) }}</li>
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
                        <div class="card-body">
                            @if ($workshop->cover)
                                <div class="text-center mb-4">
                                    <img src="{{ asset($workshop->cover) }}" alt="{{ $workshop->title }}" class="img-fluid"
                                        style="max-height: 400px; width: auto;">
                                </div>
                            @endif

                            <div class="mb-3">
                                <p>
                                    @if ($workshop->scheduled_at)
                                        <strong>Data:</strong> {{ $workshop->scheduled_at->format('d/m/Y \à\s H:i') }}
                                    @endif

                                    @if ($workshop->duration)
                                        <strong>Duração:</strong> {{ $workshop->duration }} minutos
                                    @endif

                                    @if (!$workshop->is_public)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-lock"></i> Exclusivo para Alunos
                                        </span>
                                    @endif
                                </p>
                            </div>                            

                            @if ($workshop->video_type !== 'none' && $workshop->getVideoEmbedUrl())
                                <div class="mb-4">
                                    <h5><i class="fas fa-video"></i> Vídeo do Workshop</h5>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="{{ $workshop->getVideoEmbedUrl() }}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            @endif

                            @if ($workshop->description)
                                <div class="alert alert-light">
                                    <h5><i class="fas fa-info-circle"></i> Sobre este Workshop</h5>
                                    <p class="mb-0">{{ $workshop->description }}</p>
                                </div>
                            @endif

                            @if ($workshop->content)
                                <div class="mt-4">
                                    <h5><i class="fas fa-file-alt"></i> Conteúdo Completo</h5>
                                    <div class="workshop-content">
                                        {!! $workshop->content !!}
                                    </div>
                                </div>
                            @endif

                            <hr>

                            <div class="text-muted">
                                <small>
                                    <strong>Publicado por:</strong> {{ $workshop->user->name }} |
                                    <strong>Em:</strong> {{ $workshop->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    @if ($relatedWorkshops->count() > 0)
                        <div class="card mt-4">
                            <div class="card-header bg-gradient-secondary">
                                <h3 class="card-title"><i class="fas fa-chalkboard-teacher"></i> Outros Workshops</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($relatedWorkshops as $related)
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100 shadow-sm position-relative">
                                                @if (!$related->is_public)
                                                    <span class="badge badge-warning position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                                        <i class="fas fa-lock"></i> Exclusivo
                                                    </span>
                                                @endif
                                                
                                                @if ($related->cover)
                                                    <img src="{{ asset($related->cover) }}" class="card-img-top"
                                                        alt="{{ $related->title }}" style="height: 180px; object-fit: cover;">
                                                @else
                                                    <div class="card-img-top bg-gradient-secondary d-flex align-items-center justify-content-center" 
                                                         style="height: 180px;">
                                                        <i class="fas fa-chalkboard-teacher fa-4x text-white"></i>
                                                    </div>
                                                @endif
                                                
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title font-weight-bold">{{ $related->title }}</h5>
                                                    @if ($related->description)
                                                        <p class="card-text text-muted small">
                                                            {{ Str::limit($related->description, 80) }}
                                                        </p>
                                                    @endif
                                                    
                                                    <div class="mt-auto">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            @if ($related->duration)
                                                                <small class="text-muted">
                                                                    <i class="far fa-clock"></i> {{ $related->duration }} min
                                                                </small>
                                                            @endif
                                                            @if ($related->video_type !== 'none')
                                                                <span class="badge badge-sm badge-danger">
                                                                    <i class="fab fa-{{ $related->video_type === 'youtube' ? 'youtube' : 'vimeo' }}"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        <a href="{{ route('academy.workshops.show', ['slug' => $related->slug]) }}"
                                                            class="btn btn-primary btn-block">
                                                            <i class="fas fa-eye mr-1"></i> Ver Workshop
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection

@section('css')
    <style>
        .workshop-content {
            line-height: 1.8;
        }

        .workshop-content img {
            max-width: 100%;
            height: auto;
        }

        .workshop-content table {
            width: 100%;
            margin: 20px 0;
        }

        .workshop-content table th,
        .workshop-content table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
@endsection
