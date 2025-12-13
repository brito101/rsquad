<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
            font-family: Arial, Helvetica, sans-serif;
            width: 297mm;
            height: 210mm;
            position: relative;
            margin: 0;
            padding: 0;
            overflow: hidden;
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
            width: 297mm;
            height: 210mm;
            display: block;
        }

        .certificate-content {
            position: absolute;
            top: 0;
            left: 124mm;
            width: 173mm;
            height: 210mm;
            z-index: 1;
            padding-right: 15mm;
        }

        .student-name {
            position: absolute;
            top: 90mm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 40px;
            font-weight: normal;
            color: #1a3a5c;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .course-details {
            position: absolute;
            top: 115mm;
            left: 0;
            width: 100%;
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
            bottom: 7mm;
            right: 12mm;
            font-size: 8pt;
            color: #666666;
            font-family: Courier, monospace;
            z-index: 2;
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
        <div class="student-name">{{ $user->name }}</div>

        <div class="course-details">
            concluiu o curso de <span class="course-name">{{ $course->name }}</span><br>
            com carga horária total de {{ $course->total_hours }} horas, com teoria<br>
            e prática, que ocorreu no período de {{ $certificate->getFormattedPeriod() }}.
        </div>
    </div>
    
    <div class="verification-code">
        Código de verificação: {{ $certificate->verification_code }}
    </div>
</body>
</html>
