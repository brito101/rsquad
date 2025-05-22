@if (auth()->user())
    @switch(auth()->user()->roles->pluck('name')[0])
        @case('Programador')
        @case('Administrador')

        @case('Instrutor')
            <a href="{{ route('admin.home') }}" class="btn btn-lg btn-warning">
                Retornar à página inicial
            </a>
        @break

        @case('Aluno')
            <a href="{{ route('academy.home') }}" class="btn btn-lg btn-warning">
                Retornar à página inicial
            </a>
        @break

        @default
            <a href="{{ route('site.home') }}" class="btn btn-lg btn-warning">
                Retornar à página inicial
            </a>
        @break
    @endswitch
@else
    <a href="{{ route('site.home') }}" class="btn btn-lg btn-warning">
        Retornar à página inicial
    </a>
@endif
