# TODO

## 1. Integração com Vimeo para acompanhamento de aulas e marcação de "assistido"

Implementar uma camada de integração com o player do Vimeo focada em acompanhamento de consumo de aulas, de forma que a cada abertura/execução do vídeo haja o registro de visualização do aluno no sistema.
Além disso, será disponibilizada uma interação via JavaScript para que o aluno possa marcar ou desmarcar manualmente o status de “assistido”, permitindo uma melhor gestão do próprio progresso.

Escopo detalhado:

- Análise de requisitos e modelagem de dados para registro de visualizações por aluno, curso e aula (tabelas, relacionamentos, índices básicos). Estimativa: 2 horas;
- Criação de migrações e models (Laravel Eloquent) para armazenamento de eventos de visualização e status “assistido”/“não assistido”. Estimativa: 2 horas;
- Implementação de rotas e Controller/API interna para registrar eventos de play/visualização e atualização de status, respeitando autenticação do aluno. Estimativa: 3 horas;
- Integração do player Vimeo com JavaScript (eventos do player, chamadas AJAX/fetch para a API interna) e atualização visual do progresso na interface do aluno. Estimativa: 4 horas;
- Testes funcionais e ajustes finos (cenários de múltiplas visualizações, mudanças de status, comportamento em diferentes navegadores e telas). Estimativa: 2 horas.
