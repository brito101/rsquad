@extends('site.master')

@section('content')
<main class="container" style="min-height: 70vh; padding: 60px 20px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="text-center mb-5">
                <h1 class="font-1-xxl color-0">Verificação de Certificado<span class="color-p2">.</span></h1>
                <p class="font-2-l color-5">Valide a autenticidade de certificados emitidos pela RSquad Academy</p>
            </div>

            @if(isset($certificate) && $certificate)
                {{-- Certificate Found --}}
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white text-center py-4">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h3 class="mb-0">Certificado Válido</h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                @if($certificate->course->cover)
                                    <img src="{{ asset('storage/courses/medium/' . $certificate->course->cover) }}" 
                                         alt="{{ $certificate->course->name }}" 
                                         class="img-fluid rounded shadow-sm">
                                @else
                                    <img src="{{ asset('img/defaults/medium/courses.webp') }}" 
                                         alt="Curso" 
                                         class="img-fluid rounded shadow-sm">
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4 class="font-weight-bold mb-4 color-p1">{{ $certificate->course->name }}</h4>
                                
                                <div class="mb-3">
                                    <p class="mb-2"><strong class="color-11">Aluno:</strong></p>
                                    <p class="font-2-l color-5">{{ $certificate->user->name }}</p>
                                </div>

                                <div class="mb-3">
                                    <p class="mb-2"><strong class="color-11">Carga Horária:</strong></p>
                                    <p class="font-2-l color-5">{{ $certificate->course->total_hours }} horas</p>
                                </div>

                                <div class="mb-3">
                                    <p class="mb-2"><strong class="color-11">Período de Realização:</strong></p>
                                    <p class="font-2-l color-5">{{ $certificate->getFormattedPeriod() }}</p>
                                </div>

                                <div class="mb-3">
                                    <p class="mb-2"><strong class="color-11">Data de Emissão:</strong></p>
                                    <p class="font-2-l color-5">{{ $certificate->issued_at->format('d/m/Y H:i') }}</p>
                                </div>

                                <div class="mb-3">
                                    <p class="mb-2"><strong class="color-11">Código de Verificação:</strong></p>
                                    <p class="font-2-l">
                                        <code class="bg-light px-3 py-2 rounded">{{ $certificate->verification_code }}</code>
                                    </p>
                                </div>

                                <div class="alert alert-info mt-4">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Autenticidade Confirmada:</strong> Este certificado foi emitido pela RSquad Academy 
                                    e é válido em todo território nacional.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-light py-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Documento verificado em {{ now()->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>

            @elseif(isset($code))
                {{-- Certificate Not Found --}}
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-danger text-white text-center py-4">
                        <i class="fas fa-times-circle fa-3x mb-3"></i>
                        <h3 class="mb-0">Certificado Não Encontrado</h3>
                    </div>
                    <div class="card-body p-5 text-center">
                        <p class="font-2-l color-5 mb-4">
                            Não foi possível localizar um certificado com o código fornecido:
                        </p>
                        <p class="font-2-xl">
                            <code class="bg-light px-4 py-3 rounded">{{ $code }}</code>
                        </p>
                        <div class="alert alert-warning mt-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Possíveis causas:</strong>
                            <ul class="text-left mt-2 mb-0">
                                <li>O código foi digitado incorretamente</li>
                                <li>O certificado ainda não foi emitido</li>
                                <li>O código é inválido ou expirado</li>
                            </ul>
                        </div>
                        <a href="#searchForm" class="btn btn-primary mt-3">
                            <i class="fas fa-search mr-2"></i>
                            Tentar Novamente
                        </a>
                    </div>
                </div>

            @else
                {{-- Search Form --}}
                <div class="card shadow-lg border-0" id="searchForm">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h3 class="mb-0">Verificar Certificado</h3>
                    </div>
                    <div class="card-body p-5">
                        <p class="text-center font-2-l color-5 mb-4">
                            Digite o código de verificação do certificado para validar sua autenticidade
                        </p>
                        
                        <form method="POST" action="{{ route('certificates.verify.search') }}" class="mt-4">
                            @csrf
                            <div class="form-group">
                                <label for="verification_code" class="font-weight-bold">Código de Verificação:</label>
                                <input type="text" 
                                       class="form-control form-control-lg text-center @error('verification_code') is-invalid @enderror" 
                                       id="verification_code" 
                                       name="verification_code" 
                                       placeholder="Ex: ABC123XYZ456"
                                       value="{{ old('verification_code') }}"
                                       required 
                                       style="letter-spacing: 2px; font-family: monospace;">
                                @error('verification_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                                <i class="fas fa-check-circle mr-2"></i>
                                Verificar Certificado
                            </button>
                        </form>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Como encontrar o código:</strong> O código de verificação está localizado 
                            no rodapé do certificado, próximo à margem inferior direita.
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-center mt-5">
                <a href="{{ route('site.home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-home mr-2"></i>
                    Voltar para Home
                </a>
            </div>

        </div>
    </div>
</main>

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: 3px solid rgba(0,0,0,0.1);
}

code {
    font-size: 1.1rem;
    font-weight: 600;
}

.alert ul {
    padding-left: 20px;
}
</style>
@endsection
