@extends('adminlte::master')
@section('title', '- 403 | Ooops! Acesso não autorizado!')

@section('adminlte_css')
    <style>
        body {
            margin-top: -50px;
            background-color: #3b3b3b !important;
        }
    </style>
@stop

@section('body')
    <div class="error-area d-flex justify-content-center align-content-center pt-5">
        <div class="d-table">
            <div class="d-table-cell pt-5">
                <div class="error-content text-center">
                    <img src="{{ asset('img/403-error.webp') }}" alt="Image" class="w-100 mb-5">
                    <h3 class="text-light">Ooops! Acesso não autorizado!</h3>
                    <p class="text-white-50">Sinto muito, mas você está tentando acessar uma área sem permissão!</p>
                    @include('errors.component.button')
                </div>
            </div>
        </div>
    </div>
@endsection
