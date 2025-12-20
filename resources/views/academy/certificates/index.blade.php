@extends('adminlte::page')

@section('title', '- Meus Certificados')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-certificate"></i> Meus Certificados</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('academy.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Meus Certificados</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            @include('components.alert')

            {{-- Statistics Cards --}}
            @if($statistics['total_certificates'] > 0)
            <div class="row"> 
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $statistics['total_certificates'] }}</h3>
                            <p>Certificados Conquistados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $statistics['courses_completed'] }}</h3>
                            <p>Cursos Completos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $statistics['total_hours'] }}h</h3>
                            <p>Horas de Capacitação</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $statistics['latest_certificate'] ? $statistics['latest_certificate']->issued_at->format('m/Y') : '-' }}</h3>
                            <p>Último Certificado</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Certificates Grid --}}
            @if($certificates->count() > 0)
                <div class="row">
                    @foreach($certificates as $certificate)
                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-gradient-dark">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-certificate mr-2"></i>
                                        {{ $certificate->course->name }}
                                    </h5>
                                </div>
                                
                                <div class="card-body">                                                                        
                                    <div>
                                        <p class="mb-2 text-sm">
                                            <i class="fas fa-clock mr-2"></i>
                                            <strong>Carga horária:</strong> {{ $certificate->course->total_hours }}h
                                        </p>
                                        <p class="mb-2 text-sm">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <strong>Emitido em:</strong> {{ $certificate->issued_at->format('d/m/Y') }}
                                        </p>
                                        <p class="mb-2 text-sm">
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            <strong>Período:</strong> {{ $certificate->getFormattedPeriod() }}
                                        </p>
                                        <p class="mb-0 text-sm">
                                            <i class="fas fa-shield-alt mr-2"></i>
                                            <strong>Código:</strong> {{ $certificate->verification_code }}
                                        </p>
                                    </div>
                                </div>

                                <div class="card-footer bg-light">
                                    <div class="btn-group btn-block" role="group">
                                        <a href="{{ $certificate->getPublicUrl() }}" 
                                           class="btn btn-sm btn-primary" 
                                           target="_blank"
                                           title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="downloadCertificateAsPDF('{{ $certificate->id }}', '{{ $certificate->verification_code }}')"
                                                title="Baixar PDF">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <a href="{{ $certificate->getLinkedInCertificationUrl() }}" 
                                           class="btn btn-sm btn-info"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           title="Adicionar ao LinkedIn">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-secondary"
                                                onclick="copyVerificationUrl('{{ $certificate->getPublicUrl() }}')"
                                                title="Copiar link público">
                                            <i class="fas fa-link"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-certificate fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Nenhum certificado conquistado ainda</h4>
                        <p class="text-muted">Complete 100% de um curso para receber seu certificado!</p>
                        <a href="{{ route('academy.courses.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Ver Meus Cursos
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection

@section('js')
<!-- Modal de Loading -->
<div class="modal fade" id="pdfLoadingModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Carregando...</span>
                </div>
                <h5>Gerando PDF...</h5>
                <p class="text-muted mb-0">Aguarde enquanto preparamos seu certificado.</p>
            </div>
        </div>
    </div>
</div>

<!-- html2canvas + jsPDF -->
<script src="{{ asset('vendor/pdf/html2canvas.min.js')}}"></script>
<script src="{{ asset('vendor/pdf/jspdf.umd.min.js')}}"></script>

<script>
function downloadCertificateAsPDF(certificateId, verificationCode) {
    // Mostra modal de loading
    $('#pdfLoadingModal').modal('show');

    const certificateUrl = `/certificates/public/${verificationCode}/view`;
    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.top = '-9999px';
    iframe.style.width = '1246px';
    iframe.style.height = '882px';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);
    
    iframe.onload = function() {
        setTimeout(() => {
            try {
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                const studentName = iframeDoc.querySelector('.student-name');
                
                if (!studentName || !studentName.textContent.trim()) {
                    setTimeout(arguments.callee, 500);
                    return;
                }
                
                const body = iframeDoc.body;
                
                html2canvas(body, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    width: 1246,
                    height: 882,
                    windowWidth: 1246,
                    windowHeight: 882,
                    scrollX: 0,
                    scrollY: 0,
                    logging: false
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/jpeg', 1.0);
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF({
                        orientation: 'landscape',
                        unit: 'px',
                        format: [1246, 882]
                    });
                    
                    pdf.addImage(imgData, 'JPEG', 0, 0, 1246, 882);
                    pdf.save(`certificado-${verificationCode}.pdf`);
                    
                    document.body.removeChild(iframe);
                    
                    // Fecha modal
                    $('#pdfLoadingModal').modal('hide');
                }).catch(error => {
                    console.error('Erro html2canvas:', error);
                    document.body.removeChild(iframe);
                    $('#pdfLoadingModal').modal('hide');
                });
                
            } catch (error) {
                console.error('Erro:', error);
                if (document.body.contains(iframe)) {
                    document.body.removeChild(iframe);
                }
                $('#pdfLoadingModal').modal('hide');
            }
        }, 2000);
    };
    
    iframe.src = certificateUrl;
}

function copyVerificationUrl(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('O link de verificação foi copiado para a área de transferência.', 'Link copiado!');
    }, function() {
        alert('Erro ao copiar o link. Por favor, copie manualmente: ' + url);
    });
}
</script>
@endsection
