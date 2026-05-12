# EBANX Case API

API em Laravel para o case técnico da EBANX.

## Requisitos

### Docker

- Docker 24+
- plugin `docker compose`

### Execução nativa (sem Docker)

- PHP 8.4+ (recomendado para compatibilidade com o `composer.lock` atual)
- Composer 2+
- Extensões PHP: `bcmath`, `mbstring`, `intl`, `pdo_sqlite`, `sqlite3`, `zip`

## Opção 1: Executar com Docker

### Subir aplicação

```bash
docker compose build
docker compose up -d
```

### Verificar status/logs

```bash
docker compose ps
docker compose logs --tail=100 app
docker compose logs --tail=100 nginx
```

### Chamadas de todas as APIs (Docker)

```bash
curl -i http://localhost:8080/reset

curl -i 'http://localhost:8080/balance?account_id=100'

curl -i -X POST http://localhost:8080/event \
  -H 'Content-Type: application/json' \
  -d '{"type":"deposit","destination":"100","amount":10}'

curl -i -X POST http://localhost:8080/event \
  -H 'Content-Type: application/json' \
  -d '{"type":"withdraw","origin":"100","amount":5}'

curl -i -X POST http://localhost:8080/event \
  -H 'Content-Type: application/json' \
  -d '{"type":"transfer","origin":"100","destination":"300","amount":5}'

curl -i 'http://localhost:8080/balance?account_id=100'
curl -i 'http://localhost:8080/balance?account_id=300'
```

### Parar containers

```bash
docker compose down
```

## Opção 2: Executar com PHP nativo

### Instalação e setup

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --force
```

### Subir servidor local

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Chamadas de todas as APIs (PHP nativo)

```bash
curl -i http://127.0.0.1:8000/reset

curl -i 'http://127.0.0.1:8000/balance?account_id=100'

curl -i -X POST http://127.0.0.1:8000/event \
  -H 'Content-Type: application/json' \
  -d '{"type":"deposit","destination":"100","amount":10}'

curl -i -X POST http://127.0.0.1:8000/event \
  -H 'Content-Type: application/json' \
  -d '{"type":"withdraw","origin":"100","amount":5}'

curl -i -X POST http://127.0.0.1:8000/event \
  -H 'Content-Type: application/json' \
  -d '{"type":"transfer","origin":"100","destination":"300","amount":5}'

curl -i 'http://127.0.0.1:8000/balance?account_id=100'
curl -i 'http://127.0.0.1:8000/balance?account_id=300'
```

## Rodar testes

Com Docker (container já iniciado):

```bash
docker compose exec app php artisan test
```

Nativo:

```bash
php artisan test
```

## Arquivos de infraestrutura Docker

- `Dockerfile`
- `docker-compose.yml`
- `docker/nginx/default.conf`
- `docker/php/99-production.ini`
- `docker/php/www.conf`
- `.dockerignore`
