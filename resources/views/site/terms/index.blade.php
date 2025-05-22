@extends('site.master')

@section('content')
    <main>
        <div class="title-bg">
            <div class="title container">
                <p class="font-2-l-b color-5">Termos de uso</p>
                <h1 class="font-1-xxl color-0">termos e condições<span class="color-p2">.</span></h1>
            </div>
        </div>

        <div class="terms font-2-s color-10 container">
            <h2 class="font-1-l color-11">Restrições ao uso</h2>
            <p>Você só poderá usar este site propósitos permitidos por mim. Você não poderá usá-lo em qualquer outro
                objetivo,
                especialmente comercial, sem o nosso consentimento prévio. Não associe minhas marcas a nenhuma outra. Não
                exponha nosso
                nome, logotipo, logomarca entre outros, indevidamente e de forma a causar confusão.</p>
            <h2 class="font-1-l color-11">Propriedade da informação</h2>
            <p>O conteúdo do site não pode ser copiado, distribuído, publicado, carregado, postado ou transmitido por
                qualquer outro
                meio sem o nosso consentimento prévio, a não ser que a finalidade seja apenas a divulgação.</p>
            <h2 class="font-1-l color-11">Aviso legal</h2>
            <p>A informação obtida ao usar este site não é completa e não cobre todas as questões, tópicos ou fatos que
                possam ser
                relevantes para seus objetivos. O uso deste site é de sua total responsabilidade. O conteúdo é oferecido
                como está e sem
                garantias de qualquer tipo, expressas ou implícitas. O conteúdo deste site não é palavra final sobre
                qualquer assunto, e
                podemos fazer melhorias a qualquer momento.</p>
            <p>Você, e não a {{ env('APP_NAME') }}, assume o custo de qualquer serviço, reparo ou correção necessários no
                caso de
                qualquer
                perda ou dano consequente do uso deste site ou seu conteúdo.</p>
            <p>Você entende que a {{ env('APP_NAME') }} não pode e não garante que arquivos disponíveis para download da
                Internet
                estejam
                livres de vírus, worms, cavalos de Tróia ou outro código que possa manifestar propriedades contaminadoras ou
                destrutivas.</p>
            <h2 class="font-1-l color-11">Limitação de responsabilidade</h2>
            <p>A {{ env('APP_NAME') }} não será responsável por qualquer dano eventual, direto, indireto, punitivo, real,
                consequente,
                especial, exemplar ou de qualquer outro tipo, incluindo perda de receita ou renda, dor e sofrimento,
                estresse emocional
                ou similares.</p>
            <h2 class="font-1-l color-11">Marcas registradas</h2>
            <p>Marcas e logos presentes neste site são propriedade da {{ env('APP_NAME') }} ou da parte que as
                disponibilizaram para
                a {{ env('APP_NAME') }}. A {{ env('APP_NAME') }} e as partes que disponibilizaram marca e logo detém todos
                os direitos sobre as
                mesmas.</p>
            <h2 class="font-1-l color-11">Informação provida pelo usuário</h2>
            <p>Você não pode publicar enviar, apresentar ou fazer conexão a esse site com qualquer material que:</p>
            <p>Você não tenha o direito de postar, incluindo material de propriedade de terceiros, defenda atividade ilegal
                ou discutir
                a intenção de fazer algo ilegal; seja vulgar, obsceno, pornográfico ou indecente ou que não diga respeito
                diretamente a
                este site; possa ameaçar ou insultar outros, difamar, caluniar, invadir a privacidade, perseguir, ser
                obsceno,
                pornográfico, racista, assediar ou ofender; busca explorar ou prejudicar crianças expondo-as a conteúdo
                inapropriado,
                perguntar sobre informações pessoais ou qualquer outro do tipo; infrinja qualquer propriedade intelectual ou
                outro
                direito de pessoa ou entidade, incluindo violações de direitos autorais, marca registrada ou direitos de
                publicidade;
                violam qualquer lei ou podem ser considerados para violar a lei; personifique ou deturpar sua conexão com
                qualquer
                entidade ou pessoa; ou ainda manipula títulos ou identificadores para encobrir a origem do conteúdo; promova
                qualquer
                empreendimento comercial (ex: oferecer produtos ou serviços em promoção) ou que engaje de qualquer forma em
                uma
                atividade comercial (ex: realizar sorteios ou concursos, expor banners patrocinadores e/ou solicitar bens e
                serviços)
                exceto que especificamente autorizado neste site; solicitar fundos, divulgações ou patrocinadores; incluir
                programas com
                vírus, worms e/ou Cavalos de Tróia ou qualquer outro código, arquivo ou programa de computador destinado a
                interromper,
                destruir ou limitar a funcionalidade de qualquer software ou hardware de computador ou telecomunicações;
                interrompa o
                fluxo normal da conversa, faça com que a tela “role” mais rápido que os os outros usuários conseguem
                acompanhar ou mesmo
                agir de modo a afetar a habilidade de outras pessoas de se engajar em atividades em tempo real neste site;
                inclua
                arquivos em formato MP3; desobedeça qualquer política ou regra estabelecida de tempos em tempos para o uso
                desse site ou
                qualquer rede conectada a ele; ou contenha hiperlinks para sites que contenham conteúdo que se enquadrem nas
                descrições
                acima.</p>
            <p>Mesmo sem a obrigação de fazê-lo, a {{ env('APP_NAME') }} reserva o direito de monitorar o uso deste site
                para
                determinar o
                cumprimento desse Termo de Uso assim como o de remover ou vetar qualquer informação por qualquer razão. De
                qualquer
                forma você é completamente responsável pelo conteúdo de seus envios. Você sabe e concorda que nem a
                {{ env('APP_NAME') }} ou
                qualquer terceiro provendo conteúdo para a {{ env('APP_NAME') }} assumirá qualquer responsabilidade por
                nenhuma ação
                ou inação
                da {{ env('APP_NAME') }} ou referido terceiro a respeito de qualquer envio.</p>
            <h2 class="font-1-l color-11">Segurança</h2>
            <p>É proibido usar qualquer serviço ou ferramenta conectada a este site para comprometer a segurança ou mexer
                com os
                recursos do sistema e/ou contas. O uso ou distribuição de ferramentas destinadas para comprometer a
                segurança (ex:
                programas para descobrir senha, ferramentas de crack ou de sondagem da rede) são estritamente proibidos. Se
                você estiver
                envolvido em qualquer violação da segurança do sistema, a {{ env('APP_NAME') }} se reserva o direito de
                fornecer
                suas
                informações para os administradores de sistema de outros sites para ajudá-los a resolver incidentes de
                segurança. A {{ env('APP_NAME') }} se reserva o direito de investigar potenciais violações a esse Termo de
                Uso.</p>
        </div>
    </main>
@endsection
