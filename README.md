# TriAL Smart Softwares — Teste Técnico Full Stack

Aplicação full stack de cadastro e autenticação de usuários desenvolvida como resposta ao teste técnico da **TriAL Smart Softwares**.

Cobre o fluxo completo: autenticação JWT, CRUD de usuários com controle de acesso por papel (role), expiração automática de contas via Job agendado e envio de credenciais por e-mail via fila.

---

## Stack

| Camada    | Tecnologias                                                       |
|-----------|-------------------------------------------------------------------|
| Backend   | Laravel 11 · PHP 8.3 · Eloquent ORM · JWT Auth · Queues          |
| Frontend  | Vue 3 · Composition API · Quasar v2 · Pinia · Vue Router v4      |
| Banco     | PostgreSQL 16                                                     |
| Infra     | Docker · Docker Compose · Nginx · PHP-FPM                        |

---

## Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) ≥ 24
- [Docker Compose](https://docs.docker.com/compose/) ≥ 2.20

Não é necessário ter PHP, Node ou qualquer outra dependência instalada localmente.

---

## Instalação e execução

```bash
# 1. Clone o repositório
git clone <url-do-repositorio>
cd trial-fullstack

# 2. Copie o arquivo de configuração
cp .env.example .env

# 3. Suba o projeto
docker compose up --build
```

Na primeira execução o entrypoint do backend realiza automaticamente:

- Geração do `APP_KEY` e do `JWT_SECRET`
- Execução de todas as migrations
- Criação do usuário administrador root

Aguarde todos os containers ficarem saudáveis e acesse:

| Serviço    | URL                        |
|------------|----------------------------|
| Frontend   | http://localhost:9000      |
| API        | http://localhost:8000/api  |
| PostgreSQL | localhost:5432             |

### Credenciais padrão do administrador

| Campo | Valor            |
|-------|------------------|
| Email | root@trial.com   |
| Senha | root123          |

> Definidas pelas variáveis `ROOT_EMAIL` e `ROOT_SENHA` no `.env`.

---

## Variáveis de ambiente

Todas as configurações do projeto estão centralizadas no `.env` da raiz. O backend não precisa de `.env` próprio — o entrypoint gera tudo automaticamente a partir das variáveis abaixo.

| Variável             | Descrição                                           | Padrão                |
|----------------------|-----------------------------------------------------|-----------------------|
| `APP_NAME`           | Nome da aplicação                                   | `TriAL`               |
| `APP_ENV`            | Ambiente (`local` / `production`)                   | `local`               |
| `APP_DEBUG`          | Modo debug                                          | `true`                |
| `APP_URL`            | URL base do backend                                 | `http://localhost:8000` |
| `DB_DATABASE`        | Nome do banco PostgreSQL                            | `trial_db`            |
| `DB_USERNAME`        | Usuário do banco                                    | `trial_user`          |
| `DB_PASSWORD`        | Senha do banco                                      | `trial_pass`          |
| `JWT_SECRET`         | Secret JWT (gerado automaticamente no primeiro boot)| —                     |
| `JWT_TTL`            | Expiração do token em minutos                       | `60`                  |
| `QUEUE_CONNECTION`   | Driver de fila (`database` / `sync`)                | `database`            |
| `MAIL_MAILER`        | Driver de e-mail (`log` / `smtp` / `mailgun`)       | `log`                 |
| `MAIL_FROM_ADDRESS`  | Endereço remetente dos e-mails                      | `noreply@trial.local` |
| `ROOT_EMAIL`         | E-mail do usuário administrador inicial             | `root@trial.com`      |
| `ROOT_SENHA`         | Senha do usuário administrador inicial              | `root123`             |
| `VITE_API_URL`       | URL da API consumida pelo frontend                  | `http://localhost:8000/api` |

> **E-mail em desenvolvimento:** com `MAIL_MAILER=log` as mensagens são gravadas em `backend/storage/logs/laravel.log`.

---

## Endpoints da API

### Públicos

```
POST /api/auth/register   Cadastro de novo usuário
POST /api/auth/login      Login — retorna token JWT
```

### Protegidos — requer `Authorization: Bearer <token>`

```
GET    /api/auth/me              Dados do usuário autenticado
POST   /api/auth/logout          Logout

GET    /api/usuarios             Lista paginada (admin/editor)
POST   /api/usuarios             Cria usuário via fila (admin)
GET    /api/usuarios/{id}        Detalhes de um usuário (admin/editor)
PUT    /api/usuarios/{id}        Atualiza usuário (admin/editor)
DELETE /api/usuarios/{id}        Remove usuário (admin)
PUT    /api/auth/profile         Atualiza perfil do usuário logado
```

### Exemplo — Cadastro

```json
POST /api/auth/register
{
  "nome": "João Silva",
  "email": "joao@example.com",
  "senha": "senha123",
  "senha_confirmation": "senha123",
  "telefone": "11999999999",
  "data_nascimento": "1995-06-15"
}
```

---

## Serviços Docker

O `docker-compose.yml` orquestra cinco serviços:

| Serviço     | Descrição                                                      |
|-------------|----------------------------------------------------------------|
| `postgres`  | Banco de dados PostgreSQL 16                                   |
| `backend`   | Laravel 11 via PHP-FPM                                         |
| `nginx`     | Proxy reverso — expõe a API na porta 8000                      |
| `worker`    | Processa a fila de jobs (`queue:work`)                         |
| `scheduler` | Executa o agendamento diário (`schedule:work`)                 |
| `frontend`  | Vue 3 + Quasar em modo dev — porta 9000                        |

---

## Job de expiração de contas

O `ExpirarUsuariosJob` roda diariamente à meia-noite via scheduler. Ele aciona o `ExpirarUsuariosService`, que busca todos os usuários com `status = ativo` e `data_expiracao < hoje` e os marca como `expirado`.

O resultado é registrado no log no formato:
```
[2026-04-21] ExpirarUsuariosJob: 3 usuário(s) expirado(s)
```

### Testando manualmente

```bash
# Disparar o job diretamente
docker compose exec backend php artisan tinker
>>> dispatch(new App\Jobs\ExpirarUsuariosJob())

# Criar usuário com conta já vencida para testar
>>> App\Models\Usuario::factory()->create([
...   'data_expiracao' => now()->subDays(3),
...   'status' => 'ativo',
... ])

# Acompanhar o log
docker compose logs -f worker
```

---

## Estrutura do projeto

```
trial-fullstack/
├── .env.example               ← configuração unificada (único arquivo a copiar)
├── docker-compose.yml
├── nginx/
│   └── default.conf
│
├── backend/                   Laravel 11 · PHP 8.3
│   ├── app/
│   │   ├── Domain/
│   │   │   └── Usuario/
│   │   │       ├── Contracts/         interfaces do domínio
│   │   │       └── Services/          regras de negócio (ExpirarUsuariosService)
│   │   ├── Http/
│   │   │   ├── Controllers/           AuthController · UsuarioController
│   │   │   └── Requests/              validações com mensagens em PT-BR
│   │   ├── Infrastructure/
│   │   │   └── Repositories/          implementações Eloquent dos contratos
│   │   ├── Jobs/                      CriarUsuarioJob · ExpirarUsuariosJob
│   │   └── Models/                    Usuario (JWTSubject · mutator de senha)
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/                   RootUsuarioSeeder
│   ├── routes/
│   │   ├── api.php                    endpoints REST
│   │   └── console.php                agendamento diário do job
│   └── entrypoint.sh                  bootstrap automático do container
│
└── frontend/                  Vue 3 · Quasar v2
    └── src/
        ├── boot/
        │   └── axios.js               instância Axios + interceptor JWT
        ├── components/
        │   ├── auth/                  LoginForm · RegisterForm
        │   ├── common/                RoleBadge · StatusBadge
        │   ├── dashboard/             StatCard · UserInfoCard
        │   ├── layout/                AppHeader · AppSidebar
        │   └── users/                 UserFormDialog
        ├── pages/
        │   ├── auth/                  LoginPage · TermosPage · SuportePage
        │   └── dashboard/             DashboardPage · UsersPage · ProfilePage · SettingsPage · SearchPage
        ├── router/                    Vue Router v4 + guards requiresAuth/guest
        ├── stores/                    auth · users · theme (Pinia)
        └── utils/
            └── format.js              formatDate · formatPhone · formatDias · getInitials
```

---

## Arquitetura do backend

O backend segue **Domain-Driven Design (DDD)** adaptado ao Laravel:

- **Domain** — contratos (interfaces) e serviços com regras de negócio puras, sem dependência de framework
- **Infrastructure** — implementações concretas dos repositórios via Eloquent
- **Http** — Controllers finos e Form Requests com validação (camada de entrada HTTP)
- **Jobs** — processos assíncronos desacoplados, injetam serviços via construtor

O `AppServiceProvider` realiza o binding `Interface → Implementação`, garantindo inversão de dependência em toda a aplicação.

---

## Comandos úteis

```bash
# Acompanhar logs em tempo real
docker compose logs -f backend
docker compose logs -f worker

# Acessar o container do backend
docker compose exec backend sh

# Rodar migrations manualmente
docker compose exec backend php artisan migrate

# Reverter migrations
docker compose exec backend php artisan migrate:rollback

# Parar todos os containers
docker compose down

# Parar e remover volumes (reseta o banco completamente)
docker compose down -v
```
