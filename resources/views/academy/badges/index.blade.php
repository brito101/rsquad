@extends('adminlte::page')

@section('title', '- Minhas Badges')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-medal"></i> Minhas Badges</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('academy.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Minhas Badges</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            @include('components.alert')

            {{-- Statistics Cards --}}
            <div class="row"> 
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $earnedBadges->count() }}</h3>
                            <p>Badges Conquistadas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-medal"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $availableBadges->count() }}</h3>
                            <p>Outras Badges</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ round(($earnedBadges->count() / max(($earnedBadges->count() + $availableBadges->count()), 1)) * 100) }}%</h3>
                            <p>Taxa de Conquista</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- All Badges --}}
            @if($earnedBadges->count() > 0 || $availableBadges->count() > 0)
                <div class="row">
                    {{-- Earned Badges --}}
                    @foreach($earnedBadges as $userBadge)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card card-success card-outline h-100">
                                <div class="card-header text-center bg-white">
                                    <h5 class="mb-0 font-weight-bold">{{ $userBadge['badge_name'] }}</h5>
                                </div>
                                <div class="card-body text-center p-4">
                                    @if($userBadge['badge_image'])
                                        <img src="{{ url('storage/badges/' . $userBadge['badge_image']) }}" 
                                             alt="{{ $userBadge['badge_name'] }}" 
                                             class="img-fluid badge-image"
                                             style="max-width: 100%; height: auto;">
                                    @else
                                        <div class="badge-placeholder">
                                            <i class="fas fa-medal fa-5x text-warning"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-white">
                                    <p class="mb-2 text-center">
                                        <i class="fas fa-book-open mr-1 text-primary"></i>
                                        <strong>{{ $userBadge['course_name'] }}</strong>
                                    </p>
                                    <button class="btn btn-primary btn-block share-linkedin" 
                                            data-badge-id="{{ $userBadge['id'] }}">
                                        <i class="fab fa-linkedin mr-1"></i>Compartilhar no LinkedIn
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Other Badges --}}
                    @foreach($availableBadges as $courseBadge)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card card-outline h-100">
                                <div class="card-header text-center bg-white">
                                    <h5 class="mb-0 font-weight-bold text-muted">{{ $courseBadge['badge_name'] }}</h5>
                                </div>
                                <div class="card-body text-center p-4">
                                    @if($courseBadge['badge_image'])
                                        <img src="{{ url('storage/badges/' . $courseBadge['badge_image']) }}" 
                                             alt="{{ $courseBadge['badge_name'] }}" 
                                             class="img-fluid badge-image-locked"
                                             style="max-width: 100%; height: auto; filter: grayscale(100%); opacity: 0.5;">
                                    @else
                                        <div class="badge-placeholder">
                                            <i class="fas fa-medal fa-5x text-muted" style="opacity: 0.3;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-white text-center">
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-book-open mr-1"></i>
                                        {{ $courseBadge['course_name'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($earnedBadges->count() == 0 && $availableBadges->count() == 0)
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-medal fa-5x text-muted mb-4" style="opacity: 0.3;"></i>
                            <h4 class="text-muted">Nenhuma badge disponível</h4>
                            <p class="text-muted">Matricule-se em cursos para começar a conquistar badges!</p>
                            <a href="{{ route('academy.courses.index') }}" class="btn btn-primary btn-lg mt-3">
                                <i class="fas fa-graduation-cap mr-2"></i>Explorar Cursos
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection

@section('css')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .badge-image {
        max-width: 400px;
        width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }
    
    .badge-image-locked {
        max-width: 400px;
        width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }
    
    .badge-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
    }
    
    .empty-state {
        padding: 3rem 1rem;
    }
    
    .card-outline {
        border-top: 3px solid #dee2e6;
    }
    
    .card-success.card-outline {
        border-top-color: #28a745;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('.share-linkedin').on('click', function() {
            const badgeId = $(this).data('badge-id');
            
            $.ajax({
                url: `/academy/badges/${badgeId}/share`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        const linkedInUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(response.share_url)}`;
                        window.open(linkedInUrl, '_blank', 'width=600,height=400');
                    }
                },
                error: function() {
                    alert('Erro ao gerar link de compartilhamento. Tente novamente.');
                }
            });
        });
    });
</script>
@endsection
