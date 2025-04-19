<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if (config('adminlte.logo_img_xl'))
    @include('adminlte::partials.common.brand-logo-xl')
    @else
    @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->photo ? url('storage/users/' . Auth::user()->photo) : asset('vendor/adminlte/dist/img/avatar.png') }}"
                    class="img-circle elevation-2" style="width: 33.6px; height: 33.6px; object-fit: cover;"
                    alt="{{ Auth::user()->name }}">
            </div>
            <div class="info">
                @if(auth()->user()->roles->pluck('name')[0] == 'Aluno')
                <a href="{{ route('academy.user.edit') }}"
                    class="d-block" title="Editar usuário">{{ Str::words(Auth::user()->name, 1, '') }}</a>
                @else
                <a href="{{ Auth::user()->hasPermissionTo('Editar Usuários') ? route('admin.users.edit', ['user' => Auth::user()->id]) : (Auth::user()->hasPermissionTo('Editar Usuário') ? route('admin.user.edit') : '#') }}"
                    class="d-block" title="Editar usuário">{{ Str::words(Auth::user()->name, 1, '') }}</a>
                @endif
            </div>
        </div>

        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if (config('adminlte.sidebar_nav_animation_speed') !=300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if (!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>