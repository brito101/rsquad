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
            width: 1246px;
            height: 882px;
            position: relative;
            overflow-y: hidden;
            margin: 0;
            padding: 0;
            margin-bottom: -32px;
        }

        .background-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 1246px;
            height: 882px;
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
            left: 42%;
            width: 58%;
            height: 100%;
            overflow-y: hidden!important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding-right: 50px;
            z-index: 1;
        }

        .student-name {
            width: 100%;
            text-align: center;
            font-size: 40px;
            font-weight: normal;
            color: #1a3a5c;
            letter-spacing: 1px;
            line-height: 1.2;
            margin-top: -40px;
        }

        .course-details {
            position: absolute;
            top: 55%;
            width: 85%;
            text-align: center;
            font-size: 28px;
            color: #AA8B34;
            line-height: 1.25;
        }

        .course-name {
            font-weight: normal;
            color: #AA8B34;
        }

        .verification-code {
            position: absolute;
            bottom: 10px;
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
