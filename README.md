# RSquad Academy

<p align="center">
<img src="rsquadacademy.jpg" alt="RSquad Academy"/>
</p>

## Project with Laravel 12 and Docker wuth Laravel Pint, PEST, Debugar, AdminLTE3, DataTables server side and Spatie ACL

## **1. Modelagem de Casos de Uso (Use Case)**

### **Atores:**

-   Visitante
-   Aluno
-   Administrador

### **Casos de Uso:**

#### Visitante

-   Visualizar página inicial
-   Visualizar cursos disponíveis
-   Visualizar cheatsheets
-   Visualizar artigos/posts
-   Visualizar termos de uso e política de cookies
-   Enviar formulário de contato
-   Se cadastrar como aluno

#### Aluno

-   Autenticar/Login
-   Visualizar seu dashboard
-   Visualizar cursos matriculados
-   Acessar aulas liberadas por data
-   Visualizar cheatsheets
-   Visualizar posts
-   Editar dados do perfil

#### Administrador

-   Autenticar/Login
-   Gerenciar alunos
-   Gerenciar cursos
-   Gerenciar módulos e aulas (com liberação por data)
-   Gerenciar posts/artigos
-   Gerenciar cheatsheets
-   Gerenciar conteúdo da home (testemunhos, equipe, dados institucionais)
-   Acompanhar progresso dos alunos (opcional)

---

## **2. Modelagem do Banco de Dados (entidades principais)**

```mermaid
erDiagram
    USER ||--o{ COURSE_ENROLLMENT : has
    USER {
        int id PK
        string name
        string email
        string password
        string role (admin/aluno)
        timestamp created_at
    }

    COURSE ||--|{ MODULE : contains
    MODULE ||--|{ LESSON : contains
    COURSE {
        int id PK
        string title
        text description
        timestamp created_at
    }

    MODULE {
        int id PK
        int course_id FK
        string title
    }

    LESSON {
        int id PK
        int module_id FK
        string title
        string video_link
        date release_date
    }

    COURSE_ENROLLMENT {
        int id PK
        int user_id FK
        int course_id FK
        timestamp enrolled_at
    }

    POST {
        int id PK
        string title
        text content
        int author_id FK
        timestamp created_at
    }

    CHEATSHEET {
        int id PK
        string title
        text content
        int author_id FK
        timestamp created_at
    }

    TESTIMONIAL {
        int id PK
        string author
        string role
        text content
    }

    TEAM_MEMBER {
        int id PK
        string name
        string role
        string image_url
        string bio
    }
```

---

## **3. Layout e Componentização (para o Frontend)**

**Página Home**

-   Hero
-   Sobre a empresa
-   Cursos (listagem com link para detalhes)
-   Cheatsheets (atalhos)
-   Posts/Artigos recentes
-   Equipe
-   Testemunhos
-   Call to action
-   Footer

**Outras Páginas**

-   Página de Curso (detalhes + matrícula)
-   Página de Cheatsheet
-   Página de Post
-   Cheatsheet/Post Individual
-   Página de login
-   Dashboard Aluno
-   Dashboard Admin

---

## **Resumo da Estrutura Modular**

| Módulo              | Função Principal                                         |
| ------------------- | -------------------------------------------------------- |
| **Usuários**        | Registro, autenticação, gerenciamento de permissões      |
| **Cursos**          | Cadastro de cursos e módulos                             |
| **Aulas**           | Controle de aulas com liberação por data                 |
| **Alunos**          | Cadastro, matrícula, progresso                           |
| **Posts**           | Blog institucional com conteúdos técnicos e atualizações |
| **Cheatsheets**     | Conteúdo técnico resumido em formato rápido              |
| **Dashboard Admin** | Painel de gestão do conteúdo                             |
| **Dashboard Aluno** | Painel com cursos e aulas disponíveis                    |

### Resources

-   Basic user controller
-   2FA authentication
-   Visitors log
-   API routes with JWT auth

### Usage in development environment

-   `cp .env.example .env`
-   Edit .env parameters
-   `composer install`
-   `php artisan key:generate`
-   `php artisan jwt:secret`
-   `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`
-   `sail artisan storage:link`
-   `sail artisan migrate --seed`
-   `sail npm install && npm run dev`

### Programmer login

-   user: <programador@base.com>
-   pass: 12345678
