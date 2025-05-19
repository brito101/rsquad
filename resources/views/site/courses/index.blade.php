@extends('site.master')

@section('content')
    <main>
        <div class="title-bg">
            <div class="title container">
                <p class="font-2-l-b color-5">Escolha o melhor para você</p>
                <h1 class="font-1-xxl color-0">nossos cursos<span class="color-p1">.</span></h1>
            </div>
        </div>
        @foreach ($courses as $course)
            @if ($loop->index % 2 == 0)
                <div class="courses container">
                    <div class="courses-image">
                        <img src="{{ asset('storage/courses/medium/' . $course->cover) }}" alt="{{ $course->name }}">
                    </div>
                    <div class="courses-content">
                        <h2 class="font-1-xl">{{ $course->name }}</h2>
                        <p class="font-2-s color-8">{!! $course->description !!}</p>
                        <ul class="font-1-m color-8">
                            <li>
                                <img src="./img/icones/eletrica.svg" alt="">
                                Motor Elétrico
                            </li>
                            <li>
                                <img src="./img/icones/carbono.svg" alt="">
                                Fibra de Carbono
                            </li>
                            <li>
                                <img src="./img/icones/velocidade.svg" alt="">
                                50 km/h
                            </li>
                            <li>
                                <img src="./img/icones/rastreador.svg" alt="">
                                Rastreador
                            </li>
                        </ul>
                        <a class="btn arrow" href="./bicicletas/nimbus.html">Mais Sobre</a>
                    </div>
                </div>
            @else
                <div class="courses-bg">
                    <div class="courses container">
                        <div class="courses-image">
                            <img src="{{ asset('storage/courses/medium/' . $course->cover) }}" alt="{{ $course->name }}">
                        </div>
                        <div class="courses-content">
                            <h2 class="font-1-xl color-0">{{ $course->name }}</h2>
                            <p class="font-2-s color-5">{!! $course->description !!}</p>
                            <ul class="font-1-m color-5">
                                <li>
                                    <img src="./img/icones/eletrica.svg" alt="">
                                    Motor Elétrico
                                </li>
                                <li>
                                    <img src="./img/icones/carbono.svg" alt="">
                                    Fibra de Carbono
                                </li>
                                <li>
                                    <img src="./img/icones/velocidade.svg" alt="">
                                    45 km/h
                                </li>
                                <li>
                                    <img src="./img/icones/rastreador.svg" alt="">
                                    Rastreador
                                </li>
                            </ul>
                            <a class="btn arrow" href="./bicicletas/magic.html">Mais Sobre</a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </main>
@endsection
