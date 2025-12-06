# Integração com Vimeo - RSquad Academy

## Resumo da Implementação

Esta documentação descreve a integração completa com a API do Vimeo para upload, gerenciamento e reprodução de vídeos de aulas na plataforma RSquad Academy.

## Funcionalidades Implementadas

### 1. Upload de Vídeos para o Vimeo
- Upload de vídeos através do painel administrativo (criar/editar aula)
- Substituição automática de vídeos antigos ao fazer upload de novos
- Exclusão automática de vídeos do Vimeo ao deletar aula
- Suporte a múltiplos formatos: MP4, MOV, AVI

### 2. Reprodução de Vídeos
- Player Vimeo integrado na visualização de aulas
- Reprodução dentro de modal na plataforma (sem redirecionamento)
- Priorização de vídeos do Vimeo sobre links externos

### 3. Tracking de Progresso
- Registro automático de visualizações
- Contagem de tempo assistido
- Marcação de aulas como "assistidas"
- Estatísticas de progresso por curso

## Arquivos Criados/Modificados

### Migrations
1. **database/migrations/2025_12_06_134158_create_classroom_progress_table.php**
   - Tabela para rastrear progresso dos alunos
   - Campos: user_id, classroom_id, watched, view_count, watch_time_seconds

2. **database/migrations/2025_12_06_135533_add_vimeo_id_to_classrooms_table.php**
   - Adiciona campos vimeo_id e vimeo_uri à tabela classrooms

### Models
1. **app/Models/ClassroomProgress.php**
   - Relacionamentos com User e Classroom
   - Métodos helper: isWatched(), markAsWatched(), incrementViewCount()

2. **app/Models/Classroom.php** (modificado)
   - Adicionado vimeo_id e vimeo_uri ao fillable
   - Relacionamento com ClassroomProgress

### Services
1. **app/Services/VimeoService.php**
   - Serviço completo para integração com API Vimeo
   - Métodos:
     - `upload($filePath, $params)` - Upload de vídeos
     - `delete($videoUri)` - Exclusão de vídeos
     - `update($videoUri, $params)` - Atualização de metadados
     - `getVideo($videoUri)` - Obter informações do vídeo
     - `setThumbnail($videoUri, $imagePath)` - Definir thumbnail
     - `getEmbedCode($videoId)` - Obter código de embed
     - `getPlayerUrl($videoId)` - Obter URL do player

### Providers
1. **app/Providers/VimeoServiceProvider.php**
   - Registra cliente Vimeo como singleton
   - Configurado em config/app.php

### Controllers
1. **app/Http/Controllers/Admin/ClassroomController.php** (modificado)
   - Método `store()`: Upload de vídeo ao criar aula
   - Método `update()`: Substituição de vídeo ao editar aula
   - Método `destroy()`: Exclusão de vídeo ao deletar aula

2. **app/Http/Controllers/Academy/ClassroomProgressController.php**
   - `registerView()` - Registra visualização da aula
   - `toggleWatched()` - Marca/desmarca aula como assistida
   - `updateWatchTime()` - Atualiza tempo assistido
   - `getProgress()` - Obtém progresso de uma aula
   - `getCourseProgress()` - Obtém progresso de todo o curso

### Views
1. **resources/views/admin/classroom/create.blade.php** (modificado)
   - Campo de upload de vídeo
   - JavaScript para atualizar label do input file

2. **resources/views/admin/classroom/edit.blade.php** (modificado)
   - Campo de upload de vídeo (substituição)
   - Exibe vimeo_id atual se existir
   - Aviso sobre exclusão do vídeo anterior

3. **resources/views/academy/courses/show.blade.php** (modificado)
   - Player Vimeo em modal
   - JavaScript para rastreamento de progresso
   - Eventos do player (play, pause, timeupdate, ended)
   - Priorização de vimeo_id sobre link genérico

### Routes
1. **routes/web.php** (modificado)
   - Rotas de progresso sob prefixo /academy/ com auth middleware:
     - POST /academy/classroom-progress/{classroom}/view
     - POST /academy/classroom-progress/{classroom}/toggle-watched

### Configuration
1. **config/services.php** (modificado)
   - Configuração de credenciais Vimeo

2. **.env.example** (modificado)
   - Variáveis de ambiente necessárias

## Configuração

### 1. Instalar Dependências
Já instalado via Composer:
```bash
composer require vimeo/vimeo-api
```

### 2. Configurar Credenciais do Vimeo

1. Acesse: https://developer.vimeo.com/
2. Crie um novo app
3. Gere um Access Token com as seguintes permissões:
   - Upload
   - Edit
   - Delete
   - Public
   - Private

4. Adicione as credenciais no arquivo `.env`:
```env
VIMEO_CLIENT=sua_client_id
VIMEO_SECRET=sua_client_secret
VIMEO_ACCESS=seu_access_token
```

### 3. Executar Migrations
```bash
php artisan migrate
```

### 4. Limpar Cache
```bash
php artisan config:clear
php artisan cache:clear
```

## Uso

### Admin - Criar Aula com Vídeo
1. Acesse: Admin > Aulas > Nova Aula
2. Preencha os dados da aula
3. Selecione o arquivo de vídeo no campo "Vídeo da Aula"
4. Clique em "Enviar"
5. O vídeo será enviado automaticamente para o Vimeo

### Admin - Editar Aula e Substituir Vídeo
1. Acesse: Admin > Aulas > Editar
2. Se já houver vídeo, será exibido o vimeo_id atual
3. Selecione novo vídeo para substituir
4. Clique em "Enviar"
5. O vídeo antigo será excluído e o novo será enviado

### Admin - Excluir Aula
- Ao excluir uma aula, o vídeo correspondente no Vimeo será automaticamente removido

### Aluno - Assistir Aula
1. Acesse: Academia > Meu Curso
2. Clique em "Assistir Aula"
3. O vídeo será reproduzido em modal
4. O progresso é rastreado automaticamente
5. Use o botão "Marcar como assistida" para marcar conclusão manual

## Estrutura de Dados

### Tabela: classroom_progress
```sql
- id (bigint, PK)
- user_id (bigint, FK -> users.id)
- classroom_id (bigint, FK -> classrooms.id)
- watched (boolean, default false)
- view_count (integer, default 0)
- first_viewed_at (timestamp, nullable)
- last_viewed_at (timestamp, nullable)
- watch_time_seconds (integer, default 0)
- created_at, updated_at
```

### Tabela: classrooms (campos adicionados)
```sql
- vimeo_id (string, nullable)
- vimeo_uri (string, nullable)
```

## Fluxo de Upload

1. Admin faz upload do vídeo via formulário
2. Controller recebe o arquivo
3. VimeoService faz upload para Vimeo via API
4. Vimeo retorna URI (ex: /videos/123456789)
5. vimeo_id (123456789) e vimeo_uri (/videos/123456789) são salvos no banco
6. Aluno visualiza o vídeo usando o vimeo_id

## Fluxo de Tracking

1. Aluno clica em "Assistir Aula"
2. Modal abre com player Vimeo
3. Evento 'play' registra visualização (POST /academy/classroom-progress/{id}/view)
4. Evento 'timeupdate' atualiza tempo assistido a cada 10 segundos
5. Aluno pode marcar como "assistida" manualmente
6. Progress é exibido em barras de progresso na página do curso

## Segurança

- ✅ Middleware `auth` protege rotas de progresso
- ✅ Vídeos configurados como "unlisted" no Vimeo (não aparecem em busca pública)
- ✅ Logs de erros para falhas de upload/exclusão
- ✅ Try-catch em todas operações com API Vimeo

## Logs

Todos os erros são registrados em `storage/logs/laravel.log`:
- Falhas de upload
- Falhas de exclusão
- Respostas inválidas da API

## Melhorias Futuras Possíveis

1. **Fila de Upload**
   - Processar uploads em background com Jobs/Queue
   - Notificar admin quando upload concluir

2. **Thumbnails Personalizados**
   - Gerar/fazer upload de thumbnails customizados

3. **Legendas**
   - Adicionar suporte a upload de legendas (VTT/SRT)

4. **Controle de Privacidade**
   - Permitir admin escolher privacidade do vídeo (unlisted/password/private)

5. **Analytics Avançado**
   - Gráficos de engajamento
   - Pontos mais assistidos/pulados do vídeo
   - Taxa de conclusão por aula

6. **Webhook Vimeo**
   - Receber notificações de processamento do vídeo
   - Atualizar status quando vídeo estiver pronto

## Troubleshooting

### Erro: "Vimeo credentials not configured"
**Solução**: Verifique se as variáveis VIMEO_CLIENT, VIMEO_SECRET e VIMEO_ACCESS estão no .env

### Erro: "Quota exceeded"
**Solução**: Verifique o plano do Vimeo e limites de upload semanais/mensais

### Erro: "Invalid access token"
**Solução**: Gere um novo access token no Vimeo Developer com as permissões corretas

### Vídeo não aparece no player
**Solução**: 
1. Verifique se vimeo_id está salvo no banco
2. Verifique se vídeo foi processado no Vimeo (pode levar alguns minutos)
3. Verifique configuração de privacidade do vídeo

### Upload muito lento
**Solução**:
1. Considere implementar uploads em background (Queue)
2. Comprimir vídeos antes do upload
3. Verificar limites de taxa de upload do Vimeo

## Referências

- [Vimeo API Documentation](https://developer.vimeo.com/api/reference)
- [vimeo/vimeo-api GitHub](https://github.com/vimeo/vimeo.php)
- [Vimeo Player SDK](https://developer.vimeo.com/player/sdk)

---

**Implementado em:** 06/12/2025  
**Laravel Version:** 12.x  
**PHP Version:** 8.4.15  
**Vimeo API Package:** vimeo/vimeo-api v4.0.1
