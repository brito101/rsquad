<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $course->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            width: 297mm;
            height: 210mm;
            position: relative;
            margin: 0;
            padding: 0;
        }

        .background-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            z-index: 0;
        }

        .background-container img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .certificate-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 100px;
            z-index: 1;
        }

        .student-name {
            position: absolute;
            top: 43%;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            text-align: center;
            font-size: {{ strlen($user->name) > 30 ? '36px' : (strlen($user->name) > 25 ? '42px' : '48px') }};
            font-weight: bold;
            color: #1a3a5c;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .course-details {
            position: absolute;
            top: 54%;
            left: 50%;
            transform: translateX(-50%);
            width: 75%;
            text-align: center;
            font-size: 16px;
            color: #8b7355;
            line-height: 1.8;
        }

        .course-name {
            font-weight: bold;
            color: #6b5840;
        }

        .verification-code {
            position: absolute;
            bottom: 20px;
            right: 40px;
            font-size: 10px;
            color: #666;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="background-container">
        @php
            $imagePath = public_path('certificate-template.png');
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                $src = 'data:image/png;base64,' . $imageData;
            } else {
                $src = '';
            }
        @endphp
        @if($src)
            <img src="{{ $src }}" alt="Certificate Background">
        @endif
    </div>
    
    <div class="certificate-content">
        <div class="student-name">
            {{ $user->name }}
        </div>

        <div class="course-details">
            concluiu o curso de <span class="course-name">{{ $course->name }}</span><br>
            com carga horária total de {{ $course->total_hours }} horas, com teoria<br>
            e prática, que ocorreu no período de {{ $certificate->getFormattedPeriod() }}.
        </div>

        <div class="verification-code">
            Código de verificação: {{ $certificate->verification_code }}
        </div>
    </div>
</body>
</html>
