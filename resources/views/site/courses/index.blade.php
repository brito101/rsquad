@extends('site.master')

@section('content')
    <main>
        <div class="title-bg">
            <div class="title container">
                <p class="font-2-l-b color-5">Escolha o melhor para você</p>
                <h1 class="font-1-xxl color-0">Nossos cursos<span class="color-p2">.</span></h1>
            </div>
        </div>
        @if ($courses->count() > 0)
            @foreach ($courses as $course)
                @if ($loop->index % 2 == 0)
                    <div>
                        <div class="courses container">
                            <div class="courses-image">
                                <img src="{{ $course->cover ? asset('storage/courses/medium/' . $course->cover) : asset('img/share.png') }}"
                                    alt="{{ $course->name }}">
                                <span
                                    class="font-2-m color-0">{{ $course->is_promotional == 1 ? 'R$ ' . number_format($course->promotional_price, 2, ',', '.') : 'R$ ' . number_format($course->price, 2, ',', '.') }}</span>
                            </div>
                            <div class="courses-content">
                                <h2 class="font-1-xl">{{ $course->name }}</h2>
                                <ul class="font-1-m color-8">
                                    @if ($course->categoriesInfo->count() > 0)
                                        <li>
                                            <img src="{{ asset('img/icons/tracker.svg') }}" alt="">
                                            {{ $course->categoriesInfo->pluck('name')->join(' - ') }}
                                        </li>
                                    @endif
                                </ul>
                                <a class="btn arrow" href="{{ route('site.courses.show', ['uri' => $course->uri]) }}">Saiba
                                    mais</a>
                            </div>
                        </div>
                    @else
                        <div class="courses-bg">
                            <div class="courses container">
                                <div class="courses-image">
                                    <img src="{{ $course->cover ? asset('storage/courses/medium/' . $course->cover) : asset('img/share.png') }}"
                                        alt="{{ $course->name }}">
                                    <span
                                        class="font-2-m color-0">{{ $course->is_promotional == 1 ? 'R$ ' . number_format($course->promotional_price, 2, ',', '.') : 'R$ ' . number_format($course->price, 2, ',', '.') }}</span>
                                </div>
                                <div class="courses-content">
                                    <h2 class="font-1-xl color-0">{{ $course->name }}</h2>
                                    <ul class="font-1-m color-2">
                                        @if ($course->categoriesInfo->count() > 0)
                                            <li>
                                                <img src="{{ asset('img/icons/tracker.svg') }}" alt="">
                                                {{ $course->categoriesInfo->pluck('name')->join(' - ') }}
                                            </li>
                                        @endif
                                    </ul>
                                    <a class="btn arrow"
                                        href="{{ route('site.courses.show', ['uri' => $course->uri]) }}">Saiba mais</a>
                                </div>
                            </div>
                        </div>
                @endif
            @endforeach
        @else
            <div class="courses container">
                <h2 class="font-1-xl color-p2">Em breve... estamos preparando cursos de primeira para você :)</h2>
            </div>
        @endif
    </main>
@endsection
