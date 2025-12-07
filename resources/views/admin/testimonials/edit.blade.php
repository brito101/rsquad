@extends('adminlte::page')

@section('title', '- Editar Depoimento')
@section('plugins.select2', true)
@section('plugins.BootstrapSwitch', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-comment-dots"></i> Editar Depoimento</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}">Depoimentos</a></li>
                        <li class="breadcrumb-item active">Editar</li>
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
                            <h3 class="card-title">Gerenciar Depoimento</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.testimonials.update', ['testimonial' => $testimonial->id]) }}">
                            @csrf
                            @method('PUT')

                            <div class="card-body">

                                <div class="row">
                                    <!-- Student Info -->
                                    <div class="col-md-6 form-group">
                                        <label>Aluno</label>
                                        <input type="text" class="form-control" value="{{ $testimonial->user->name }}" readonly>
                                    </div>

                                    <!-- Course Info -->
                                    <div class="col-md-6 form-group">
                                        <label>Curso</label>
                                        <input type="text" class="form-control" value="{{ $testimonial->course->name }}" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Rating Stars -->
                                    <div class="col-md-6 form-group">
                                        <label>Avaliação (não editável)</label>
                                        <div class="form-control-plaintext">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $testimonial->rating)
                                                    <i class="fas fa-star text-warning fa-lg"></i>
                                                @else
                                                    <i class="far fa-star text-warning fa-lg"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-2">({{ $testimonial->rating }}/5)</span>
                                        </div>
                                        <small class="form-text text-muted">A avaliação do aluno não pode ser alterada por questão de transparência.</small>
                                    </div>

                                    <!-- Date -->
                                    <div class="col-md-6 form-group">
                                        <label>Data do Envio</label>
                                        <input type="text" class="form-control" value="{{ $testimonial->created_at->format('d/m/Y H:i') }}" readonly>
                                    </div>
                                </div>

                                <!-- Testimonial Text -->
                                <div class="form-group">
                                    <label for="testimonial">Depoimento <span class="text-danger">*</span></label>
                                    <textarea name="testimonial" id="testimonial" class="form-control @error('testimonial') is-invalid @enderror" 
                                              rows="5" required minlength="10" maxlength="1000">{{ old('testimonial', $testimonial->testimonial) }}</textarea>
                                    <small class="form-text text-muted">
                                        <span id="char-count">{{ strlen($testimonial->testimonial) }}</span>/1000 caracteres (mínimo 10). 
                                        <strong>Você pode editar o texto para corrigir erros gramaticais.</strong>
                                    </small>
                                    @error('testimonial')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>

                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <x-adminlte-select2 name="status" id="status" enable-old-support>
                                        <option value="pending" {{ old('status', $testimonial->status) == 'pending' ? 'selected' : '' }}>Pendente</option>
                                        <option value="approved" {{ old('status', $testimonial->status) == 'approved' ? 'selected' : '' }}>Aprovado</option>
                                        <option value="rejected" {{ old('status', $testimonial->status) == 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                                    </x-adminlte-select2>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <strong>Pendente:</strong> Aguardando análise | 
                                        <strong>Aprovado:</strong> Aparecerá na home (se destacado) | 
                                        <strong>Rejeitado:</strong> Não será exibido
                                    </small>
                                </div>

                                <!-- Featured -->
                                <div class="form-group">
                                    @if($testimonial->featured)
                                        <x-adminlte-input-switch name="featured" label="Destacar na Home"
                                            data-on-text="Sim" data-off-text="Não" data-on-color="success" checked
                                            id="featured" />
                                    @else
                                        <x-adminlte-input-switch name="featured" label="Destacar na Home"
                                            data-on-text="Sim" data-off-text="Não" data-on-color="success"
                                            id="featured" />
                                    @endif
                                    <small class="form-text text-muted">
                                        Depoimentos destacados aparecem no carrossel da página inicial. 
                                        <strong>Atenção:</strong> Somente depoimentos com status "Aprovado" serão exibidos.
                                    </small>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Character counter
        $('#testimonial').on('input', function() {
            const length = $(this).val().length;
            $('#char-count').text(length);
            
            if (length < 10) {
                $('#char-count').removeClass('text-success').addClass('text-danger');
            } else {
                $('#char-count').removeClass('text-danger').addClass('text-success');
            }
        });

        // Initialize char count
        const initialLength = $('#testimonial').val().length;
        if (initialLength < 10) {
            $('#char-count').addClass('text-danger');
        } else {
            $('#char-count').addClass('text-success');
        }
    });
</script>
@endsection
