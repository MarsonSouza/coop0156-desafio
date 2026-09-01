# Coop0156 — Análise de Crédito Cooperativo

Solução do desafio técnico PHP/Laravel. O enunciado original está em [`DESAFIO.md`](DESAFIO.md).

Todas as 4 etapas obrigatórias e os 2 diferenciais opcionais (filas e tela de clientes) foram implementados.

## O que foi feito

- **CRUD de clientes** com Form Requests, API Resources, Route Model Binding e mensagens de validação em pt-BR.
- **Análise de crédito**: cadastra/localiza o cliente pelo CPF, consulta o Bureau via `Http::`, aplica as regras (renda mínima, faixas de score, comprometimento de renda) e persiste o resultado. A regra de negócio fica em `App\Services` — o controller só valida e responde.
- **Resiliência**: timeout, erro HTTP e resposta sem `score` viram `BureauIndisponivelException` → resposta **HTTP 503** limpa, sem 500 inesperado; a análise permanece `pendente`.
- **Simulação e contratação**: JS das telas `analise` e `simulacao` + endpoint `contratar`.
- **Diferencial — filas**: `contratar` move a análise para `processando_contratacao` e despacha o `ProcessarContratacaoJob`, que finaliza para `contratado` e registra log.
- **Diferencial — tela de clientes** (`/clientes`): cadastro, listagem paginada, edição, exclusão e modal com as análises de cada cliente. Máscaras de CPF, moeda e telefone.
- **Testes**: 32 testes / 109 asserções (`php artisan test`), usando `Http::fake()`.

## Como executar

Requer **PHP 8.4+** (o `composer.lock` trava pacotes que exigem `>= 8.4.1`) e Composer. O banco é SQLite — nada a instalar.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate

php artisan serve --no-reload   # --no-reload: a app faz HTTP no próprio mock do Bureau
```

Acesse http://localhost:8000. Com **Laravel Sail** funciona igual (`./vendor/bin/sail up -d`); nesse caso ajuste no `.env`: `SCORE_BUREAU_API_URL=http://localhost/api/mock/bureau`.

```bash
php artisan test          # testes
php artisan queue:work    # worker da fila (diferencial)
```

### Sem PHP local (via Docker)

Se não tiver PHP 8.4 na máquina, dá pra rodar tudo pela imagem `laravelsail/php84-composer` (só precisa de Docker):

```bash
# atalho: define uma vez no terminal
dr() { docker run --rm -u "$(id -u):$(id -g)" -e HOME=/var/www/html \
  -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest "$@"; }

dr composer install
cp .env.example .env
dr php artisan key:generate
touch database/database.sqlite
dr php artisan migrate

dr php artisan test          # testes
dr php artisan queue:work    # worker da fila

# servidor web (mapeando a porta 8000)
docker run --rm --name coop-serve -u "$(id -u):$(id -g)" -e PHP_CLI_SERVER_WORKERS=6 \
  -v "$(pwd):/var/www/html" -w /var/www/html -p 8000:8000 \
  laravelsail/php84-composer:latest php artisan serve --host=0.0.0.0 --port=8000 --no-reload
```

## Rotas

**Web:** `/` (análise) · `/clientes` (cadastro/listagem) · `/simulacao/{id}` (contratação)

**API:**

| Método | Rota | Observação |
|---|---|---|
| `GET` | `/api/clientes` | Paginado — `?page=N`, `?limit=N` (padrão 10) |
| `POST` | `/api/clientes` | 201 |
| `GET` | `/api/clientes/{id}` | inclui as análises do cliente |
| `PUT/PATCH` | `/api/clientes/{id}` | atualização parcial |
| `DELETE` | `/api/clientes/{id}` | 204 |
| `POST` | `/api/analise-credito` | cria o cliente se o CPF for novo |
| `POST` | `/api/analise-credito/{id}/contratar` | só análises aprovadas; enfileira o Job |

## Notas

- **Regras de crédito** (nesta ordem): renda `< 1500` → reprovado; `score < 400` → reprovado; `score 400–699` → 4,5% a.m.; `score ≥ 700` → 2,9% a.m.; parcela `> 30%` da renda → reprovado. Parcela = `(valor + valor × taxa × 12) / 12`.
- **`clientes.email` virou `nullable`** (migration extra): o cadastro automático da análise só recebe nome/CPF/renda. O CRUD continua exigindo e-mail.
- **`phpunit.xml`**: testes rodam em SQLite `:memory:`.
- **`config/services.php` / `.env`**: a URL padrão do Bureau apontava para `/api/mock/score`; a rota real é `/api/mock/bureau/{cpf}` — corrigido.
