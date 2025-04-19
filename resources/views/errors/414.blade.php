@extends('adminlte::master')
@section('title', '- 414 | Oops! Requisição muito longa!')

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
                    <img src="{{ asset('img/414-error.webp') }}" alt="Image" class="w-100 my-auto">
                    <h3 class="text-light">Oops! Requisição muito longa!</h3>
                    <p class="text-white-50">A URL acessada está com um parâmetro muito longo. Limite os itens selecionados e
                        tente novamente.</p>
                    @include('errors.component.button')
                </div>
            </div>
        </div>
    </div>
@endsection
