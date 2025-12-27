@extends('adminlte::page')

@section('title', '- Curso ' . $course->name)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_top_nav_right')
    <li class="nav-item">
        <span class="nav-link">
            <i class="fas fa-chart-pie text-success"></i> 
            <strong>Progresso:</strong> 
            <span id="progress-display">{{ $watchedClasses }}/{{ $totalClasses }}</span>
            (<span id="progress-percentage">{{ $progressPercentage }}</span>%)
        </span>
    </li>
@endsection

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-newspaper"></i> Curso {{ $course->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('academy.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('academy.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item active">Curso {{ $course->name }}</li>
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
                            <h3 class="card-title">Detalhes do Curso</h3>

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
                                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                    <!-- Progress Bar -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-success progress-bar-striped" role="progressbar" 
                                                     id="course-progress-bar"
                                                     style="width: {{ $progressPercentage }}%;" 
                                                     aria-valuenow="{{ $progressPercentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ $progressPercentage }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-muted">Módulos</span>
                                                    <span
                                                        class="info-box-number text-center text-muted mb-0">{{ $modules->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-muted">Total de Aulas</span>
                                                    <span
                                                        class="info-box-number text-center text-muted mb-0">{{ $totalClasses }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-white">Aulas Assistidas</span>
                                                    <span
                                                        class="info-box-number text-center text-white mb-0" id="watched-count">{{ $watchedClasses }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($course->pdf_file)
                                        <div class="row">
                                            <div class="col-12">
                                                <a href="{{ route('academy.pdf.course.download', $course->id) }}" 
                                                   class="btn btn-danger btn-block">
                                                    <i class="fas fa-file-pdf mr-2"></i>
                                                    Baixar Material do Curso (PDF)
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-12">
                                            @foreach ($course->modules as $module)
                                                <div class="card" style="min-height: 50px;">
                                                    <div class="card-header">
                                                        @if ($module->cover)
                                                            <img class="img-circle img-bordered-sm"
                                                                src="{{ url('storage/course-modules/icon/' . $module->cover) }}"
                                                                alt="{{ $module->name }}">
                                                        @endif
                                                        <span class="username">
                                                            @if ($module->link)
                                                                @if ($module->release_date)
                                                                    @if ($module->release_date && $module->release_date <= now())
                                                                        <a href="{{ $module->link }}" target="_blank"
                                                                            class="h3 text-primary">{{ $module->name }}</a>
                                                                    @else
                                                                        <a href="#"
                                                                            class="h3 text-primary">{{ $module->name }}
                                                                            <span
                                                                                class="badge badge-primary text-sm float-right">Liberação
                                                                                em
                                                                                {{ date('d/m/Y', strtotime($module->release_date)) }}</span></a>
                                                                    @endif
                                                                @else
                                                                    <a href="{{ $module->link }}" target="_blank"
                                                                        class="h3 text-primary">{{ $module->name }}</a>
                                                                @endif
                                                            @else
                                                                <a href="#"
                                                                    class="h3 text-primary">{{ $module->name }}</a>
                                                            @endif
                                                        </span>
                                                        <p class="mt-3 text-muted">{!! $module->description !!}</p>

                                                        @if($module->pdf_file)
                                                            <a href="{{ route('academy.pdf.module.download', $module->id) }}" 
                                                               class="btn btn-sm btn-danger">
                                                                <i class="fas fa-file-pdf mr-1"></i>
                                                                Baixar Material do Módulo (PDF)
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <div class="card-body">
                                                        <ul class="list-group">
                                                            @foreach ($classes as $classroom)
                                                                @if ($classroom->course_module_id == $module->id)
                                                                    @php
                                                                        $progress = $userProgress->get($classroom->id);
                                                                        $isWatched = $progress && $progress->watched;
                                                                        $isReleased = !$classroom->release_date || $classroom->release_date <= now();
                                                                    @endphp
                                                                    
                                                                    <li class="list-group-item">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="d-flex align-items-center flex-grow-1">
                                                                                @if ($isReleased)
                                                                                    <div class="icheck-primary mr-3">
                                                                                        <input type="checkbox" 
                                                                                               style="cursor: pointer" 
                                                                                               id="watched-{{ $classroom->id }}"
                                                                                               class="toggle-watched-checkbox"
                                                                                               data-classroom-id="{{ $classroom->id }}"
                                                                                               {{ $isWatched ? 'checked' : '' }}>
                                                                                        <label for="watched-{{ $classroom->id }}"></label>
                                                                                    </div>
                                                                                @endif
                                                                                
                                                                                <div class="flex-grow-1">
                                                                                    <strong>{{ $classroom->name }}</strong>
                                                                                    @if (!$isReleased)
                                                                                        <span class="badge badge-warning ml-2">
                                                                                            <i class="fas fa-lock"></i> 
                                                                                            Liberação em {{ date('d/m/Y', strtotime($classroom->release_date)) }}
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            @if (($classroom->vimeo_id || $classroom->link) && $isReleased)
                                                                                <a href="{{ route('academy.classroom.show', $classroom->id) }}" 
                                                                                   class="btn btn-sm btn-primary ml-2">
                                                                                    <i class="fas fa-play"></i> Assistir
                                                                                </a>
                                                                            @endif

                                                                            @if ($classroom->pdf_file && $isReleased)
                                                                                <a href="{{ route('academy.pdf.classroom.download', $classroom->id) }}" 
                                                                                   class="btn btn-sm btn-danger ml-1">
                                                                                    <i class="fas fa-file-pdf"></i> PDF
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                                    @if ($course->cover)
                                        <img class="img-fluid mb-4"
                                            src="{{ url('storage/courses/medium/' . $course->cover) }}" alt="Course Cover">
                                    @endif

                                    <h3 class="text-primary">{{ $course->name }}</h3>

                                    {!! $course->description !!}

                                    <br>
                                    <div class="text-muted">
                                        @if ($course->instructors->count() > 0)
                                            <p class="text-sm">Instrutores:
                                                @foreach ($course->instructorsInfo as $instructor)
                                                    <b class="d-block">{{ $instructor->name }}</b>
                                                @endforeach
                                            </p>
                                        @endif
                                        @if ($course->categories->count() > 0)
                                            <p class="text-sm">Categorias:
                                                @foreach ($course->categoriesInfo as $category)
                                                    <b class="d-block">{{ $category->name }}</b>
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Testimonial Section - Only show when course is 100% complete and no testimonial exists --}}
                    @can('Criar Depoimentos')
                        @if(!$userTestimonial)
                            <div class="card card-primary" id="testimonial-section" style="display: {{ $progressPercentage >= 100 ? 'block' : 'none' }};">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-comment-dots"></i> Avalie este Curso</h3>
                                </div>
                                <div class="card-body">
                                    <p>Compartilhe sua experiência com este curso! Seu feedback é muito importante.</p>
                                    
                                    <form action="{{ route('academy.courses.testimonial.store', $course->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="form-group">
                                            <label for="rating">Sua Avaliação <span class="text-danger">*</span></label>
                                            <div class="rating-stars">
                                                <input type="hidden" name="rating" id="rating" value="{{ old('rating', 0) }}">
                                                <div class="stars" style="font-size: 2rem; cursor: pointer;">
                                                    <i class="far fa-star" data-value="1"></i>
                                                    <i class="far fa-star" data-value="2"></i>
                                                    <i class="far fa-star" data-value="3"></i>
                                                    <i class="far fa-star" data-value="4"></i>
                                                    <i class="far fa-star" data-value="5"></i>
                                                </div>
                                            </div>
                                            @error('rating')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="testimonial">Seu Depoimento <span class="text-danger">*</span></label>
                                            <textarea 
                                                name="testimonial" 
                                                id="testimonial" 
                                                class="form-control @error('testimonial') is-invalid @enderror" 
                                                rows="5" 
                                                placeholder="Conte-nos sobre sua experiência com este curso..."
                                                required
                                                minlength="10"
                                                maxlength="1000">{{ old('testimonial') }}</textarea>
                                            <small class="form-text text-muted">
                                                <span id="char-count">0</span>/1000 caracteres (mínimo 10)
                                            </small>
                                            @error('testimonial')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Enviar Depoimento
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endcan

                </div>
            </div>
        </div>
    </section>
@endsection

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('js')
<script>
    $(document).ready(function() {
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Toggle watched checkbox
        $(document).on('change', '.toggle-watched-checkbox', function() {
            const checkbox = $(this);
            const classroomId = checkbox.data('classroom-id');
            const isWatched = checkbox.is(':checked');
            
            toggleWatchedStatus(classroomId, checkbox, isWatched);
        });

        // Rating stars functionality
        $('.stars i').on('click', function() {
            const value = $(this).data('value');
            $('#rating').val(value);
            
            // Update stars display
            $('.stars i').each(function(index) {
                if (index < value) {
                    $(this).removeClass('far').addClass('fas text-warning');
                } else {
                    $(this).removeClass('fas text-warning').addClass('far');
                }
            });
        });

        // Stars hover effect
        $('.stars i').on('mouseenter', function() {
            const value = $(this).data('value');
            $('.stars i').each(function(index) {
                if (index < value) {
                    $(this).removeClass('far').addClass('fas text-warning');
                } else {
                    $(this).removeClass('fas text-warning').addClass('far');
                }
            });
        });

        $('.stars').on('mouseleave', function() {
            const currentValue = $('#rating').val();
            $('.stars i').each(function(index) {
                if (index < currentValue) {
                    $(this).removeClass('far').addClass('fas text-warning');
                } else {
                    $(this).removeClass('fas text-warning').addClass('far');
                }
            });
        });

        // Character counter
        $('#testimonial').on('input', function() {
            const length = $(this).val().length;
            $('#char-count').text(length);
            
            if (length < 10) {
                $('#char-count').removeClass('text-success').addClass('text-danger');
            } else {
                $('#char-count').removeClass('text-danger').addClass('text-success');
            }
        });

        // Initialize char count if there's old input
        if ($('#testimonial').val().length > 0) {
            $('#char-count').text($('#testimonial').val().length);
        }

        // Initialize stars if there's old rating
        const oldRating = $('#rating').val();
        if (oldRating > 0) {
            $('.stars i').each(function(index) {
                if (index < oldRating) {
                    $(this).removeClass('far').addClass('fas text-warning');
                }
            });
        }
    });

    function toggleWatchedStatus(classroomId, checkbox, isWatched) {
        $.ajax({
            url: `/academy/classroom-progress/${classroomId}/toggle-watched`,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    const newWatchedState = response.data.watched;
                    checkbox.prop('checked', newWatchedState);
                    
                    // Update progress counters
                    updateProgressCounters();
                    
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

    function updateProgressCounters() {
        // Count watched classes using checkboxes
        const watchedCount = $('.toggle-watched-checkbox:checked').length;
        const totalClasses = {{ $totalClasses }};
        const progressPercentage = totalClasses > 0 ? Math.round((watchedCount / totalClasses) * 100 * 100) / 100 : 0;

        // Update display
        $('#watched-count').text(watchedCount);
        $('#progress-display').text(`${watchedCount}/${totalClasses}`);
        $('#progress-percentage').text(progressPercentage);

        // Update progress bar
        $('#course-progress-bar').css('width', `${progressPercentage}%`);
        $('#course-progress-bar').attr('aria-valuenow', progressPercentage);
        $('#course-progress-bar').text(`${progressPercentage}%`);

        // Show/hide testimonial section based on progress
        const hasTestimonial = {{ $userTestimonial ? 'true' : 'false' }};
        if (!hasTestimonial) {
            if (progressPercentage >= 100) {
                $('#testimonial-section').slideDown(400);
            } else {
                $('#testimonial-section').slideUp(400);
            }
        }
    }
</script>
@endsection
