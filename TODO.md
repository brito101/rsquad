# TODO

## 4/6 - Módulo de Workshops com página específica na camada externa

Criação de um módulo específico para Workshops, com estrutura similar ao módulo de cursos, porém adaptado às características particulares deste formato (ex.: data/hora do evento, modalidade ao vivo ou gravado, carga horária específica, etc.).
Esse módulo incluirá:

- Gestão de workshops no painel administrativo;
- Página pública de listagem de workshops;
- Página de detalhes de cada workshop na camada externa (informações, descrição, público-alvo, etc.); e
- Reaproveitamento máximo da estrutura de cursos, quando aplicável, mantendo padronização de código e UX.
Escopo detalhado
- Modelagem de dados para workshops (tabela específica, campos para datas, status, descrição, vínculo com vídeos ou materiais, etc.). Estimativa: 2 horas;
- Implementação de models, controllers e rotas para CRUD completo de workshops no backend (Laravel). Estimativa: 5 horas;
- Desenvolvimento de telas administrativas. Estimativa: 4 horas, incluindo:
    - Listagem, criação, edição, remoção; e
    - Definição de campos de destaque (por exemplo, workshops em evidência).
    - Criação da página pública de listagem de workshops (camada externa), com filtros simples se necessário. Estimativa: 4 horas;
    - Testes integrados e ajustes de UX para garantir consistência com a experiência atual da RSquad Academy. Estimativa: 3 horas