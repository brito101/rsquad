@extends('adminlte::page')

@section('title', '- Workshops')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-chalkboard-teacher"></i> Workshops</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('academy.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Workshops</li>
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
                            <h3 class="card-title">Workshops Disponíveis</h3>
                        </div>
                        <div class="card-body">
                            @if ($workshops->count() > 0)
                                <div class="row">
                                    @foreach ($workshops as $workshop)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card h-100 position-relative">
                                                @if(!$workshop->is_public)
                                                    <span class="badge badge-warning position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                                        <i class="fas fa-lock"></i> Exclusivo para Alunos
                                                    </span>
                                                @endif
                                                
                                                @if($workshop->cover)
                                                    <img src="{{ asset($workshop->cover) }}" class="card-img-top" alt="{{ $workshop->title }}">
                                                @else
                                                    <img src="{{ asset('img/share.png') }}" class="card-img-top" alt="{{ $workshop->title }}">
                                                @endif
                                                
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title">{{ $workshop->title }}</h5>
                                                    
                                                    @if($workshop->description)
                                                        <p class="card-text">{{ Str::limit($workshop->description, 100) }}</p>
                                                    @endif
                                                    
                                                    <div class="mt-auto">
                                                        @if($workshop->duration)
                                                            <p class="mb-2">
                                                                <i class="fas fa-clock text-info"></i> 
                                                                <small>{{ $workshop->duration }} minutos</small>
                                                            </p>
                                                        @endif
                                                        
                                                        @if($workshop->scheduled_at)
                                                            <p class="mb-2">
                                                                <i class="fas fa-calendar-alt text-primary"></i> 
                                                                <small>{{ $workshop->scheduled_at->format('d/m/Y H:i') }}</small>
                                                            </p>
                                                        @endif

                                                        @if($workshop->video_type !== 'none')
                                                            <p class="mb-2">
                                                                @if($workshop->video_type === 'youtube')
                                                                    <i class="fab fa-youtube text-danger"></i> 
                                                                    <small>YouTube</small>
                                                                @elseif($workshop->video_type === 'vimeo')
                                                                    <i class="fab fa-vimeo text-info"></i> 
                                                                    <small>Vimeo</small>
                                                                @endif
                                                            </p>
                                                        @endif

                                                        <a href="{{ route('academy.workshops.show', ['slug' => $workshop->slug]) }}" 
                                                           class="btn btn-primary btn-block mt-3">
                                                            <i class="fas fa-eye mr-1"></i> Ver Workshop
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    Nenhum workshop disponível no momento.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
