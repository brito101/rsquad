@extends('adminlte::page')

@section('title', '- Certificado')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-certificate"></i> Certificado</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('academy.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('academy.certificates.index') }}">Meus Certificados</a></li>
                        <li class="breadcrumb-item active">Certificado</li>
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
                            <h3 class="card-title">
                                <i class="fas fa-certificate mr-2"></i>
                                {{ $certificate->course->name }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <strong><i class="fas fa-user mr-2"></i>Aluno:</strong><br>
                                    {{ $certificate->user->name }}
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-clock mr-2"></i>Carga Horária:</strong><br>
                                    {{ $certificate->course->total_hours }} horas
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-calendar mr-2"></i>Período:</strong><br>
                                    {{ $certificate->getFormattedPeriod() }}
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-barcode mr-2"></i>Código:</strong><br>
                                    <code class="bg-light p-1">{{ $certificate->verification_code }}</code>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('academy.certificates.download', $certificate->id) }}" 
                                           class="btn btn-primary btn-lg"
                                           title="Baixar PDF">
                                            <i class="fas fa-download"></i> Baixar PDF
                                        </a>
                                        <a href="{{ $certificate->getLinkedInCertificationUrl() }}" 
                                           class="btn btn-info btn-lg"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           title="Adicionar ao Perfil do LinkedIn">
                                            <i class="fab fa-linkedin"></i> Adicionar ao LinkedIn
                                        </a>
                                        <button type="button" 
                                                class="btn btn-secondary btn-lg"
                                                onclick="copyPublicUrl()"
                                                title="Copiar link público do certificado">
                                            <i class="fas fa-link"></i> Copiar Link Público
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview do Certificado -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="certificate-preview">
                                        <iframe 
                                            src="{{ route('academy.certificates.view', $certificate->id) }}" 
                                            class="certificate-iframe"
                                            title="Certificado"
                                            frameborder="0">
                                        </iframe>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('academy.certificates.index') }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Voltar para Meus Certificados
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

@section('css')
    <style>
        code {
            font-size: 1rem;
            letter-spacing: 1px;
        }
        .btn-group {
            display: flex;
            gap: 0;
        }
        .btn-group .btn {
            flex: 1;
            border-radius: 0;
        }
        .btn-group .btn:first-child {
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }
        
        /* Melhorar visualização do certificado */
        .certificate-preview {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            position: relative;
        }
        
        .certificate-preview::before {
            content: '';
            display: block;
            padding-top: 70.71%; /* Proporção A4 landscape */
        }
        
        .certificate-iframe {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            width: calc(100% - 40px);
            height: calc(100% - 40px);
            border: none;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background: white;
        }
    </style>
@stop

@section('js')
    <script>
        function copyPublicUrl() {
            const url = "{{ $certificate->getPublicUrl() }}";
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() {
                    @if(config('adminlte.plugins.Sweetalert2.active'))
                        Swal.fire({
                            icon: 'success',
                            title: 'Copiado!',
                            text: 'Link público do certificado copiado para a área de transferência.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    @else
                        alert('Link público do certificado copiado para a área de transferência!');
                    @endif
                }, function(err) {
                    console.error('Erro ao copiar:', err);
                    promptCopy(url);
                });
            } else {
                promptCopy(url);
            }
        }

        function promptCopy(url) {
            const textarea = document.createElement('textarea');
            textarea.value = url;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                @if(config('adminlte.plugins.Sweetalert2.active'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Copiado!',
                        text: 'Link de verificação copiado para a área de transferência.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                @else
                    alert('Link de verificação copiado para a área de transferência!');
                @endif
            } catch (err) {
                console.error('Erro ao copiar:', err);
                prompt('Copie este link:', url);
            }
            
            document.body.removeChild(textarea);
        }
    </script>
@stop
