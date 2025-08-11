# Lumos API

API de contas e transações com autenticação via token Bearer (Laravel Sanctum).

Stack: PHP 8.3 (Laravel 12), Nginx, Postgres 16, Redis 7, Docker Compose. Documentação em Swagger UI.

## Requisitos

- Docker e Docker Compose

## Subindo o projeto

1) Clone o repositório e entre na pasta `lumos`.

2) Crie o arquivo `.env` e ajuste para usar Postgres/Redis do Compose:

```env
cp .env.example .env

# Principais ajustes no .env
APP_URL=http://localhost:8080

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=lumos
DB_USERNAME=lumos
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PORT=6379
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

3) Instale as dependências PHP e gere a chave da aplicação (dentro do container):

```bash
docker compose run --rm app composer install
docker compose run --rm app php artisan key:generate
```

4) Suba os serviços:

```bash
docker compose up -d
```

O serviço `app` executa migrations automaticamente na inicialização. Se preferir forçar:

```bash
docker compose exec app php artisan migrate
```

## Endereços

- API: http://localhost:8080/api
- Swagger UI: http://localhost:8081

## Fluxo de autenticação (rápido)

1) Registre um usuário

```bash
curl -X POST http://localhost:8080/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Alice","email":"alice@example.com","password":"secret123"}'
```

2) Faça login e copie o token `token` retornado

```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"alice@example.com","password":"secret123"}'
```

3) Use o token como Bearer nas rotas protegidas

```bash
TOKEN="coloque_o_token_aqui"

# Criar conta
curl -X POST http://localhost:8080/api/accounts \
  -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d '{"name":"Main","balance":100.0}'

# Listar contas
curl -H "Authorization: Bearer $TOKEN" http://localhost:8080/api/accounts

# Depósito/Saque
curl -X POST http://localhost:8080/api/transact \
  -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d '{"account_id":"<ID>","amount":50.0,"type":"deposit"}'

# Transferência
curl -X POST http://localhost:8080/api/transfer \
  -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" \
  -d '{"account_from":"<ID_FROM>","account_to":"<ID_TO>","amount":25.0}'
```

Detalhes completos de contratos e respostas estão no `openapi.yml` e no Swagger UI.

## Testes

```bash
docker compose run --rm app php artisan test
```

## Comandos úteis

```bash
# Logs do app
docker compose logs -f app

# Parar e remover serviços
docker compose down
```

