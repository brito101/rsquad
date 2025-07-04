@extends('adminlte::page')

@section('title', '- Editar Aluno')
@section('plugins.BootstrapSelect', true)
@section('plugins.select2', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSwitch', true)

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-fw fa-graduation-cap"></i> Editar Aluno</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    @can('Listar Alunos')
                    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Alunos</a></li>
                    @endcan
                    <li class="breadcrumb-item active">Editar Aluno</li>
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
                        <h3 class="card-title">Dados Cadastrais do Aluno</h3>
                    </div>

                    <form method="POST" action="{{ route('admin.students.update', ['student' => $user->id]) }}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-6 form-group px-0 pr-md-2 mb-0">
                                    <label for="name">Nome</label>
                                    <input type="text" class="form-control" id="name"
                                        placeholder="Nome Completo" name="name"
                                        value="{{ old('name') ?? $user->name }}" required>
                                </div>
                                <div class="col-12 col-md-6 form-group px-0 pl-md-2 mb-0">
                                    <x-adminlte-input-file name="photo" label="Foto"
                                        placeholder="Selecione uma imagem..." legend="Selecionar" />
                                </div>

                                <div class="col-12 form-group px-0 mb-0">
                                    <x-adminlte-textarea name="bio" label="Bio" rows=5 igroup-size="md"
                                        placeholder="Insira uma descrição opcional sobre você..."
                                        maxlength="10000">{{ old('bio') ?? $user->bio }}
                                    </x-adminlte-textarea>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                    <label for="telephone">Telefone</label>
                                    <input type="tel" class="form-control" id="telephone" placeholder="Telefone"
                                        name="telephone" value="{{ old('telephone') ?? $user->telephone }}">
                                </div>
                                <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                    <label for="cell">Celular</label>
                                    <input type="tel" class="form-control" id="cell" placeholder="Celular"
                                        name="cell" value="{{ old('cell') ?? $user->cell }}">
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" placeholder="E-mail"
                                        name="email" value="{{ old('email') ?? $user->email }}" required>
                                </div>
                                <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                    <label for="password">Senha</label>
                                    <input type="password" class="form-control" id="password" placeholder="Senha"
                                        minlength="8" name="password" value="">
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                    <label for="linkedin">Linkedin</label>
                                    <input type="text" class="form-control" id="linkedin"
                                        placeholder="URL do Linkedin" name="linkedin"
                                        value="{{ old('linkedin') ?? $user->linkedin }}">
                                </div>
                                <div class="col-12 col-md-4 form-group px-0 px-md-2">
                                    <label for="instagram">Instagram</label>
                                    <input type="text" class="form-control" id="instagram"
                                        placeholder="URL do Instagram" name="instagram"
                                        value="{{ old('instagram') ?? $user->instagram }}">
                                </div>
                                <div class="col-12 col-md-4 form-group px-0 pl-md-2">
                                    <label for="youtube">Youtube</label>
                                    <input type="text" class="form-control" id="youtube"
                                        placeholder="URL do Youtube" name="youtube"
                                        value="{{ old('youtube') ?? $user->youtube }}">
                                </div>
                            </div>

                            @php
                            $config = [
                            'title' => 'Selecione múltiplos...',
                            'liveSearch' => true,
                            'liveSearchPlaceholder' => 'Pesquisar...',
                            'showTick' => true,
                            'actionsBox' => true,
                            ];
                            @endphp

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-6 form-group px-0 pr-md-2 mb-0">
                                    <x-adminlte-select-bs id="courses" name="courses[]" label="Cursos"
                                        label-class="text-dark" igroup-size="md" :config="$config" multiple
                                        class="border">
                                        @foreach ($courses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ in_array($course->id, $pivot->pluck('course_id')->toArray()) ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                        @endforeach
                                    </x-adminlte-select-bs>
                                </div>
                                @can('Atribuir Perfis')
                                <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                    <label for="role">Tipo de Usuário</label>
                                    <x-adminlte-select2 name="role">
                                        @foreach ($roles as $role)
                                        <option
                                            {{ old('role') == $role->name ? 'selected' : ($user->role_id == $role->id ? 'selected' : '') }}
                                            value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>
                                @endcan
                            </div>

                            <div
                                class="col-12 col-md-6 form-group px-0 {{ Auth::user()->hasPermissionTo('Atribuir Perfis') ? 'pr-md-2' : 'pl-md-2' }} d-flex flex-wrap justify-content-start">
                                @if ($user->google2fa_secret_enabled)
                                <x-adminlte-input-switch name="google2fa_secret_enabled"
                                    label="Duplo Fator de Autenticação (Google Auth)" data-on-text="Sim"
                                    data-off-text="Não" data-on-color="teal" checked id="google2fa"
                                    data-action="{{ route('admin.user.google2fa') }}"
                                    data-user="{{ $user->id }}" />
                                @else
                                <x-adminlte-input-switch name="google2fa_secret_enabled"
                                    label="Duplo Fator de Autenticação (Google Auth)" data-on-text="Sim"
                                    data-off-text="Não" data-on-color="teal" id="google2fa"
                                    data-action="{{ route('admin.user.google2fa') }}"
                                    data-user="{{ $user->id }}" />
                                @endif

                                <div class="col-12 px-0 px-0 pr-md-2" id="seed-container">
                                    <div>
                                        @if (
                                        ($user->google2fa_secret_enabled && Auth::user()->id == $user->id) ||
                                        ($user->google2fa_secret_enabled && Auth::user()->hasRole('Programador')))
                                        <p class="w-100 d-inline-block px-0">
                                            Semente: <span
                                                style="letter-spacing: .2rem; margin-left: 20px; font-weight: 700;">{{ $user->google2fa_secret }}</span>
                                        </p>
                                        <img alt="QRCode"
                                            src="data:image/png;base64,{{ base64_encode($user->getQRCodeInline()) }}" />
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @can('Atribuir Perfis')
                            @if ($user->id != Auth::user()->id)
                            <div
                                class="col-12 col-md-6 form-group px-0 pr-md-2 d-flex flex-wrap justify-content-start">
                                @if ($user->first_access)
                                <x-adminlte-input-switch name="first_access" label="Primeiro Acesso"
                                    data-on-text="Sim" data-off-text="Não" data-on-color="teal" checked
                                    id="first_access" />
                                @else
                                <x-adminlte-input-switch name="first_access" label="Primeiro Acesso"
                                    data-on-text="Sim" data-off-text="Não" data-on-color="teal"
                                    id="first_access" />
                                @endif
                            </div>
                            @endif
                            @endcan

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script src="{{ asset('vendor/jquery/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('js/address.js') }}"></script>
<script src="{{ asset('js/phone.js') }}"></script>
<script src="{{ asset('js/google2fa.js') }}"></script>
@endsection