<?php

use Illuminate\Support\Facades\Auth;

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => env('APP_NAME'),
    'title_prefix' => env('APP_NAME'),
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => env('APP_NAME'),
    'logo_img' => 'img/logo-rounded.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => env('APP_NAME'),

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => false,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => true,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'admin',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'text' => 'Profile',
            'url' => '/admin/user/edit',
            'icon' => 'fas fa-fw fa-user',
            'topnav_right' => true,
            'can' => 'Acessar Administração',
        ],
        [
            'text' => 'Profile',
            'url' => '/academy/user/edit',
            'icon' => 'fas fa-fw fa-user',
            'topnav_right' => true,
            'can' => 'Acessar Academia',
        ],
        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'Pesquisar',
        ],

        // Custom menus
        [
            'text' => 'Dashboard',
            'url' => '/admin',
            'icon' => 'fa fa-fw fa-digital-tachograph mr-2',
            'can' => 'Acessar Administração',
        ],
        [
            'text' => 'Dashboard',
            'url' => '/academy',
            'icon' => 'fa fa-fw fa-digital-tachograph mr-2',
            'can' => 'Acessar Academia',
        ],
        [
            'text' => 'Usuários',
            'url' => '#',
            'icon' => 'fas fa-fw fa-users mr-2',
            'can' => 'Acessar Usuários',
            'submenu' => [
                [
                    'text' => 'Listagem de Usuários',
                    'url' => 'admin/users',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Listar Usuários',
                ],
                [
                    'text' => 'Cadastro de Usuário',
                    'url' => 'admin/users/create',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Criar Usuários',
                ],
            ],
        ],
        [
            'text' => 'Alunos',
            'url' => '#',
            'icon' => 'fas fa-fw fa-graduation-cap mr-2',
            'can' => 'Acessar Alunos',
            'submenu' => [
                [
                    'text' => 'Listagem de Alunos',
                    'url' => 'admin/students',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Listar Alunos',
                ],
                [
                    'text' => 'Cadastro de Aluno',
                    'url' => 'admin/students/create',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Criar Alunos',
                ],
            ],
        ],
        [
            'text' => 'Meus Cursos',
            'url' => 'academy/courses',
            'icon' => 'fa fa-fw fa-newspaper mr-2',
            'can' => 'Acessar Academia',
        ],
        [
            'text' => 'Cursos',
            'url' => '#',
            'icon' => 'fas fa-fw fa-newspaper mr-2',
            'can' => 'Acessar Cursos',
            'submenu' => [
                [
                    'text' => 'Listagem de Cursos',
                    'url' => 'admin/courses',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Listar Cursos',
                ],
                [
                    'text' => 'Cadastro de Curso',
                    'url' => 'admin/courses/create',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Criar Cursos',
                ],
                [
                    'text' => 'Listagem de Módulos',
                    'url' => 'admin/course-modules',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Listar Módulos de Cursos',
                ],
                [
                    'text' => 'Cadastro de Módulo',
                    'url' => 'admin/course-modules/create',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Criar Módulos de Cursos',
                ],
                [
                    'text' => 'Listagem de Aulas',
                    'url' => 'admin/classes',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Listar Aulas',
                ],
                [
                    'text' => 'Cadastro de Aula',
                    'url' => 'admin/classes/create',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Criar Aulas',
                ],
            ],
        ],
        [
            'text' => 'Blog',
            'url' => '#',
            'icon' => 'fas fa-fw fa-blog mr-2',
            'can' => 'Acessar Blog',
            'submenu' => [
                [
                    'text' => 'Listagem de Postagens',
                    'url' => 'admin/blog',
                    'icon' => 'fas fa-fw fa-chevron-right mr-2',
                    'can' => 'Listar Posts',
                ],
                [
                    'text' => 'Cadastro de Postagem',
                    'url' => 'admin/blog/create',
                    'icon' => 'fas fa-fw fa-chevron-right mr-2',
                    'can' => 'Criar Posts',
                ],
                [
                    'text' => 'Listagem de Categorias',
                    'url' => 'admin/blog-categories',
                    'icon' => 'fas fa-fw fa-chevron-right mr-2',
                    'can' => 'Listar Categorias do Blog',
                ],
                [
                    'text' => 'Cadastro de Categoria',
                    'url' => 'admin/blog-categories/create',
                    'icon' => 'fas fa-fw fa-chevron-right mr-2',
                    'can' => 'Criar Categorias do Blog',
                ],
            ],
        ],
        [
            'text' => 'Cheat Sheets',
            'url' => '#',
            'icon' => 'fas fa-fw fa-th-list mr-2',
            'can' => 'Acessar Cheat Sheet',
            'submenu' => [
                [
                    'text' => 'Listagem de Cheats',
                    'url' => 'admin/cheat-sheets',
                    'icon' => 'fas fa-fw fa-chevron-right mr-2',
                    'can' => 'Listar Cheat Sheet',
                ],
                [
                    'text' => 'Cadastro de Cheat',
                    'url' => 'admin/cheat-sheets/create',
                    'icon' => 'fas fa-fw fa-chevron-right mr-2',
                    'can' => 'Criar Cheat Sheet',
                ],
                [
                    'text' => 'Listagem de Categorias',
                    'url' => 'admin/cheat-sheets-categories',
                    'icon' => 'fas fa-fw fa-chevron-right mr-2',
                    'can' => 'Listar Categorias do Cheat Sheet',
                ],
                [
                    'text' => 'Cadastro de Categoria',
                    'url' => 'admin/cheat-sheets-categories/create',
                    'icon' => 'fas fa-fw fa-chevron-right mr-2',
                    'can' => 'Criar Categorias do Cheat Sheet',
                ],
            ],
        ],
        [
            'text' => 'Configurações',
            'icon' => 'fas fa-fw fa-cogs mr-2',
            'can' => 'Acessar Configurações',
            'submenu' => [
                [
                    'text' => 'Categorias de Cursos',
                    'url' => '#',
                    'icon' => 'fas fa-fw fa-tags mr-2',
                    'can' => 'Acessar Categorias de Cursos',
                    'submenu' => [
                        [
                            'text' => 'Listagem de Categorias',
                            'url' => 'admin/course-categories',
                            'icon' => 'fas fa-fw fa-chevron-right',
                            'can' => 'Listar Categorias de Cursos',
                        ],
                        [
                            'text' => 'Cadastro de Categoria',
                            'url' => 'admin/course-categories/create',
                            'icon' => 'fas fa-fw fa-chevron-right',
                            'can' => 'Criar Categorias de Cursos',
                        ],
                    ],
                ],
            ],
        ],
        [
            'text' => 'ACL',
            'icon' => 'fas fa-fw fa-user-shield mr-2',
            'can' => 'Acessar ACL',
            'submenu' => [

                [
                    'text' => 'Listagem de Perfis',
                    'url' => 'admin/role',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Listar Perfis',
                ],
                [
                    'text' => 'Cadastro de Perfil',
                    'url' => 'admin/role/create',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Criar Perfis',
                ],
                [
                    'text' => 'Listagem de Permissões',
                    'url' => 'admin/permission',
                    'icon' => 'fas fa-fw fa-chevron-right',
                ],
                [
                    'text' => 'Cadastro de Permissão',
                    'url' => 'admin/permission/create',
                    'icon' => 'fas fa-fw fa-chevron-right',
                    'can' => 'Criar Permissões',
                ],
            ],
        ],
        [
            'text' => 'Changelog',
            'url' => 'admin/changelog',
            'icon' => 'fas fa-fw fa-code mr-2',
            'can' => 'Acessar Administração',
        ],
        [
            'text' => 'Site',
            'url' => '/',
            'icon' => 'fas fa-fw fa-link mr-2',
            'target' => '_blank',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'DatatablesPlugins' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/jszip/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/pdfmake/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/pdfmake/vfs_fonts.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css',
                ],
            ],
        ],
        'BootstrapSwitch' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-switch/js/bootstrap-switch.min.js',
                ],
            ],
        ],
        'BootstrapSelect' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/chart.js/Chart.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'vendor/chart.js/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'BsCustomFileInput' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bs-custom-file-input/bs-custom-file-input.min.js',
                ],
            ],
        ],
        'select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/select2/js/select2.full.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2/css/select2.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css',
                ],
            ],
        ],
        'Summernote' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.css',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
