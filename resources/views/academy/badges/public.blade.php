<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="{{ $userBadge->course->badge_name }} 🏆">
    <meta property="og:description" content="{{ $userBadge->user->name }} conquistou a badge {{ $userBadge->course->badge_name }} ao completar o curso {{ $userBadge->course->name }}!">
    @if($userBadge->course->badge_image)
    <meta property="og:image" content="{{ url('storage/badges/' . $userBadge->course->badge_image) }}">
    <meta property="og:image:width" content="400">
    <meta property="og:image:height" content="400">
    @endif
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="RSquad Academy">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $userBadge->course->badge_name }} 🏆">
    <meta name="twitter:description" content="{{ $userBadge->user->name }} conquistou esta badge!">
    @if($userBadge->course->badge_image)
    <meta name="twitter:image" content="{{ url('storage/badges/' . $userBadge->course->badge_image) }}">
    @endif
    
    <title>{{ $userBadge->course->badge_name }} - {{ $userBadge->user->name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .badge-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            text-align: center;
        }
        .badge-image {
            max-width: 200px;
            margin: 2rem auto;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .badge-title {
            color: #667eea;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .user-info {
            font-size: 1.2rem;
            color: #555;
            margin: 1.5rem 0;
        }
        .course-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
        }
        .earned-date {
            color: #28a745;
            font-weight: bold;
            margin-top: 1rem;
        }
        .logo {
            max-width: 150px;
            margin-top: 2rem;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="badge-container">
        <h1 class="badge-title">
            <i class="fas fa-medal text-warning"></i>
            {{ $userBadge->course->badge_name }}
        </h1>
        
        @if($userBadge->course->badge_image)
            <img src="{{ url('storage/badges/' . $userBadge->course->badge_image) }}" 
                 alt="{{ $userBadge->course->badge_name }}" 
                 class="badge-image img-fluid">
        @else
            <div class="my-5">
                <i class="fas fa-medal fa-10x text-warning"></i>
            </div>
        @endif
        
        <div class="user-info">
            Conquistada por <strong>{{ $userBadge->user->name }}</strong>
        </div>
        
        <div class="course-info">
            <h5><i class="fas fa-graduation-cap mr-2"></i>Curso</h5>
            <p class="mb-0"><strong>{{ $userBadge->course->name }}</strong></p>
        </div>
        
        <div class="earned-date">
            <i class="fas fa-check-circle mr-2"></i>
            Conquistada em {{ $userBadge->earned_at->format('d/m/Y') }}
        </div>
        
        <hr class="my-4">
        
        <p class="text-muted small">
            Esta badge certifica a conclusão bem-sucedida do curso.
        </p>
    </div>
</body>
</html>
