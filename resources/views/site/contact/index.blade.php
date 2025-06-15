@extends('site.master')

@section('content')
    <main id="contact">
        <div class="title-bg">
            <div class="title container">
                <p class="font-2-l-b color-5">Respostas em até 24h</p>
                <h1 class="font-1-xxl color-0">Nosso contato<span class="color-p1">.</span></h1>
            </div>
        </div>

        <div class="contact container">
            <section class="contact-data" aria-label="Endereço">
                <h2 class="font-1-m color-0">Entre em contato conosco</h2>
                <div class="contact-address font-2-s color-4">
                    <p>Recife-PE</p>
                </div>
                <address class="contact-mods font-2-s color-4">
                    <a href="mailto:contato@rsquadacademy.com.br">contato@rsquadacademy.com.br</a>
                    <a href="tel:+558199382388">+55 81 9938-2388</a>
                </address>
                <div class="contact-social">
                    <a href="https://www.linkedin.com/company/rsquadacademy" target="_blank" rel="noopener"
                        title="LinkedIn">
                        <img src="{{ asset('img/social/linkedin.svg') }}" width="32" height="32" alt="LinkedIn">
                    </a>
                    <a href="https://instagram.com/rsquad.academy" target="_blank" rel="noopener" title="Instagram">
                        <img src="{{ asset('img/social/instagram.svg') }}" width="32" height="32" alt="Instagram">
                    </a>
                    <a href="https://www.youtube.com/@RSquadAcademy" target="_blank" rel="noopener" title="Youtube">
                        <img src="{{ asset('img/social/youtube.svg') }}" width="32" height="32" alt="Youtube">
                    </a>
                </div>
            </section>
            <section class="contact-form" aria-label="Formulário">
                @if (isset($message))
                    <div class="alert-success">
                        <p class="color-p2">{{ $message }}</p>
                    </div>
                @else
                    <form class="form" action="{{ route('site.contact.send') }}" method="post">
                        @csrf
                        <div>
                            <label for="name">Nome</label>
                            <input type="text" id="name" name="name" placeholder="Seu nome" maxlength="50"
                                required>
                        </div>
                        <div>
                            <label for="phone">Telefone</label>
                            <input type="text" id="phone" name="phone" placeholder="(DDD) 9999-9999" maxlength="20"
                                required>
                        </div>
                        <div class="col-2">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="contato@email.com" required
                                maxlength="50">
                        </div>
                        <div class="col-2">
                            <label for="message">Mensagem</label>
                            <textarea rows="5" id="message" name="message" placeholder="O que você precisa?" required maxlength="500"></textarea>
                        </div>
                        <button class="btn col-2">Enviar Mensagem</button>
                    </form>
                @endif
            </section>
        </div>
    </main>
@endsection

@section('custom_js')
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('js/phone.js') }}"></script>
@endsection
