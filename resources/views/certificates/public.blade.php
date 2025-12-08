<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $certificate->course->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #1a1a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .certificate-container {
            max-width: 1400px;
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            position: relative;
        }

        .certificate-frame {
            width: 100%;
            height: 0;
            padding-bottom: 70.71%; /* Proporção A4 landscape (210/297) */
            position: relative;
            background: white;
        }

        .certificate-frame iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .certificate-container {
                border-radius: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-frame">
            <iframe src="{{ route('certificates.public.view', $certificate->verification_code) }}" 
                    title="Certificado de {{ $certificate->course->name }}"
                    loading="eager">
            </iframe>
        </div>
    </div>
</body>
</html>
