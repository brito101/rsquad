# TODO

## Adicionais:

### PDFs com Marca D'água

**Estrutura de Banco de Dados:**
- Adicionar campos nas tabelas existentes:
  - `courses`: `pdf_file` (nullable)
  - `course_modules`: `pdf_file` (nullable)
  - `course_classes`: `pdf_file` (nullable)
- Tabela `pdf_downloads` para auditoria: `id`, `user_id`, `downloadable_type`, `downloadable_id`, `file_name`, `downloaded_at`, `ip_address`

**Bibliotecas PHP Necessárias:**
- `setasign/fpdi` - Para manipular PDFs existentes
- `tecnickcom/tcpdf` - Para adicionar conteúdo ao PDF
- Instalar via Composer: `composer require setasign/fpdi tecnickcom/tcpdf`

**Implementação Admin:**
- Adicionar campo de upload de PDF nos formulários de:
  - Criar/Editar Curso
  - Criar/Editar Módulo
  - Criar/Editar Aula
- Mostrar PDF atual com opção de download e remoção

**Implementação Academy:**
- Botão "Baixar Material (PDF)" nas páginas de curso/módulo/aula
- Processo de download:
  1. Validar se aluno está matriculado no curso
  2. Ler PDF original do storage privado
  3. Adicionar marca d'água com:
     - Nome completo do aluno
     - Email do aluno
     - Data e hora do download
     - ID único do download (para rastreamento)
  4. Retornar PDF modificado para download (não salvar no servidor)
  5. Registrar log na tabela `pdf_downloads`

**Marca D'água:**
- Posição: diagonal no centro de cada página do PDF
- Estilo: texto semi-transparente (opacity 0.3)
- Conteúdo: "Licenciado para: [Nome] - [Email] - [Data] - ID: [UUID]"
- Fonte: cinza claro, tamanho 20pt

**Segurança:**
- PDFs originais em `storage/app/private/pdfs/` (não acessíveis via URL)
- Validação rigorosa: aluno só baixa se matriculado
- Rate limiting: máximo 5 downloads por arquivo por hora
- Middleware de autorização
- Log completo de todos os downloads para auditoria

**Controller Example:**
```php
public function downloadCoursePdf($courseId)
{
    // 1. Validar matrícula
    // 2. Obter PDF original
    // 3. Aplicar marca d'água usando FPDI + TCPDF
    // 4. Registrar download
    // 5. Retornar PDF
}
```
