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
            background: #030303;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .certificate-container {
            width: 1246px;
            height: 882px;
            max-width: 100%;
            max-height: calc(100vh - 40px);
            background: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .certificate-frame {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .certificate-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        @media (max-width: 1280px) {
            body {
                padding: 10px;
            }
            
            .certificate-container {
                max-height: calc(100vh - 20px);
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
