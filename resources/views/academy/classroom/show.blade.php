@extends('adminlte::page')

@section('title', '- ' . $classroom->name)

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-play-circle"></i> {{ $classroom->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('academy.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('academy.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('academy.courses.show', $classroom->course_id) }}">{{ $classroom->course->name }}</a></li>
                        <li class="breadcrumb-item active">{{ $classroom->name }}</li>
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

                    <!-- Video Player Card -->
                    <div class="card">
                        <div class="card-body p-0">
                            <!-- Vimeo Player -->
                            <div class="embed-responsive embed-responsive-16by9" style="max-width: 100%; background: #000;">
                                @if($classroom->vimeo_player_url)
                                    <div id="vimeo-thumbnail-poster" 
                                         class="position-absolute w-100 h-100"
                                         style="top: 0; left: 0; 
                                                background-size: cover; background-position: center; 
                                                cursor: pointer; z-index: 10;
                                                {{ $classroom->vimeo_thumbnail ? 'background-image: url(\'' . $classroom->vimeo_thumbnail . '\');' : 'display: none;' }}">
                                        @if($classroom->vimeo_thumbnail)
                                            <div class="d-flex align-items-center justify-content-center h-100">
                                                <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 80px; height: 80px;">
                                                    <i class="fas fa-play text-white" style="font-size: 32px; margin-left: 6px;"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <iframe id="vimeo-player"
                                            class="embed-responsive-item"
                                            src="{{ $classroom->vimeo_player_url }}{{ strpos($classroom->vimeo_player_url, '?') !== false ? '&' : '?' }}autoplay={{ $classroom->vimeo_thumbnail ? '0' : '1' }}&title=0&byline=0&portrait=0"
                                            frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                @elseif($classroom->link)
                                    <iframe id="vimeo-player"
                                            class="embed-responsive-item"
                                            src="{{ $classroom->link }}"
                                            frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <p class="text-white">Vídeo não disponível</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info and Controls Card -->
                    <div class="card">
                        <div class="card-body">
                            <h3>{{ $classroom->name }}</h3>
                            
                            @if($classroom->description)
                                <div class="mt-3">
                                    {!! $classroom->description !!}
                                </div>
                            @endif

                            <!-- Navigation and Checkbox -->
                            <div class="row mt-4 align-items-center">
                                <div class="col-md-4">
                                    @if($previousClassroom)
                                        <a href="{{ route('academy.classroom.show', $previousClassroom->id) }}" 
                                           class="btn btn-outline-primary btn-block">
                                            <i class="fas fa-chevron-left"></i> Aula Anterior
                                        </a>
                                    @endif
                                </div>

                                <div class="col-md-4 text-center">
                                    <div class="icheck-primary d-inline-block">
                                        <input type="checkbox" 
                                               style="cursor: pointer" 
                                               id="watched-{{ $classroom->id }}"
                                               class="toggle-watched-checkbox"
                                               data-classroom-id="{{ $classroom->id }}"
                                               {{ $isWatched ? 'checked' : '' }}>
                                        <label for="watched-{{ $classroom->id }}" class="font-weight-normal">
                                            Marcar como assistida
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    @if($nextClassroom)
                                        <a href="{{ route('academy.classroom.show', $nextClassroom->id) }}" 
                                           class="btn btn-primary btn-block">
                                            Próxima Aula <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('css')
<style>
    #vimeo-thumbnail-poster:hover .fa-play {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }
</style>
@endsection

@section('js')
<script src="https://player.vimeo.com/api/player.js"></script>
<script>
    let vimeoPlayer = null;
    let viewRegistered = false;

    $(document).ready(function() {
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const classroomId = {{ $classroom->id }};

        // Initialize Vimeo Player
        const iframe = document.getElementById('vimeo-player');
        if (iframe) {
            vimeoPlayer = new Vimeo.Player(iframe);

            // Register events
            vimeoPlayer.on('play', function() {
                if (!viewRegistered) {
                    registerView(classroomId);
                    viewRegistered = true;
                }
            });
        }

        // Hide thumbnail poster when clicked
        const poster = document.getElementById('vimeo-thumbnail-poster');
        if (poster) {
            poster.onclick = function() {
                poster.style.display = 'none';
            };
        }

        // Toggle watched checkbox
        $(document).on('change', '.toggle-watched-checkbox', function() {
            const checkbox = $(this);
            const classroomId = checkbox.data('classroom-id');
            const isWatched = checkbox.is(':checked');
            
            toggleWatchedStatus(classroomId, checkbox, isWatched);
        });
    });

    function registerView(classroomId) {
        $.ajax({
            url: `/academy/classroom-progress/${classroomId}/view`,
            method: 'POST',
            success: function(response) {
                console.log('View registered:', response);
            },
            error: function(xhr) {
                console.error('Error registering view:', xhr.responseJSON);
            }
        });
    }

    function toggleWatchedStatus(classroomId, checkbox, isWatched) {
        $.ajax({
            url: `/academy/classroom-progress/${classroomId}/toggle-watched`,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    const newWatchedState = response.data.watched;
                    checkbox.prop('checked', newWatchedState);
                    
                    // Show success message
                    toastr.success(response.message);
                } else {
                    // Revert checkbox on error
                    checkbox.prop('checked', !isWatched);
                    toastr.error(response.message || 'Erro ao atualizar status');
                }
            },
            error: function(xhr) {
                // Revert checkbox on error
                checkbox.prop('checked', !isWatched);
                console.error('Error toggling watched status:', xhr.responseJSON);
                toastr.error('Erro ao atualizar status da aula');
            }
        });
    }
</script>
@endsection
