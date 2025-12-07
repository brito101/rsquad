# TODO

## 2/6 - Certificados dinâmicos por curso e área de certificados do aluno

Após o aluno atingir 100% de conclusão dos vídeos/aulas do curso, será liberado um certificado de conclusão. Esse certificado será gerado a partir de um template em HTML, permitindo geração dinâmica com os seguintes dados:

- Nome do aluno;
- Nome do curso; e
- Data de conclusão (data em que atingiu 100% do vídeo/conteúdo).

Também será criada uma página específica para o aluno visualizar e baixar todos os certificados já conquistados.

Escopo detalhado:

- Definição do layout e requisitos do certificado (logomarca, cores, fontes, assinaturas digitais/impressas, campo de código de verificação se desejado). Estimativa: 2 horas;
- Desenvolvimento do template em HTML/CSS, preparado para conversão em PDF ou exibição para impressão. Estimativa: 4 horas;
- Integração da lógica de conclusão do curso com o sistema de certificados. Estimativa: 4 horas;
- Implementação de Service/Controller para geração de certificados (dinâmicos). Estimativa: 4 horas, incluindo:
    - Rotas para visualização/download (PDF); e
    - Armazenamento/registro de emissões.
- Desenvolvimento de página “Meus Certificados” na área do aluno, listando todos os certificados disponíveis, com download rápido. Estimativa: 3 horas;
- Testes de geração, verificação de dados e ajustes de layout em diferentes resoluções, além de testes com múltiplos cursos/alunos. Estimativa: 3 horas