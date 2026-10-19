# Sistema de Agendamento — Backend

Projeto integrador — TSI · IFSP São João da Boa Vista.

Um único projeto Laravel com duas frentes:

- **API** (`/api/v1/...`): consumida pelo front em React (cliente final). Autenticação via **Sanctum**.
- **Painel Administrativo** (`/admin`): back-office do estabelecimento, feito em **Filament**.

As duas frentes usam os mesmos models, migrations, policies e regras de negócio.

## Stack

- PHP 8.2+ · Laravel 12 · Filament 5 · Sanctum · Pest
- Banco: SQLite no desenvolvimento local (padrão) / MySQL no ambiente oficial

## Como rodar

```bash
git clone <url-do-repositorio>
cd sistema-barbearia-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan make:filament-user   # cria um usuário para entrar no /admin
php artisan serve
```

- API: http://localhost:8000/api/v1/status
- Painel: http://localhost:8000/admin

> No XAMPP, as extensões `intl` e `zip` precisam estar ativas no `php.ini`.

Para usar MySQL, ajuste no `.env`: `DB_CONNECTION=mysql`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

## Testes

```bash
./vendor/bin/pest
```

Os testes usam SQLite em memória, então não precisam de banco configurado.

## Organização das pastas

| Pasta | Frente | Conteúdo |
|---|---|---|
| `app/Models`, `database/migrations` | Base (compartilhada) | Modelo de dados. Mudanças passam pela revisão do líder. |
| `app/Policies`, `app/Enums` | Base (compartilhada) | Permissões e papéis (RF #01–#03). Usadas pela API e pelo Filament. |
| `app/Services` | Base (compartilhada) | **Regras de negócio** (agendamento, disponibilidade, caixa...). |
| `app/Http/Controllers/Api/V1`, `routes/api.php` | API | Endpoints do cliente final. |
| `app/Filament` | Painel | Resources, páginas e widgets do Filament. |
| `tests/Feature/Api`, `tests/Feature/Painel`, `tests/Unit` | Cada frente | Testes Pest. |

**Regra principal:** controllers e telas do Filament **não têm regra de negócio**. Os dois chamam os mesmos `Services`.
Exemplo: criar um agendamento pela API ou pelo painel passa pelo mesmo `AgendamentoService`, com a mesma checagem de conflito.

### Padrão de resposta da API

Use `App\Http\Responses\ApiResponse`:

```json
// sucesso
{ "data": { ... }, "message": "opcional" }

// erro
{ "message": "Dados inválidos.", "errors": { "email": ["..."] } }
```

## Fluxo de trabalho (Git)

```
main     ← versão estável (entrega de cada sprint)
develop  ← integração do dia a dia
feature/api-<tarefa>     ex.: feature/api-login
feature/admin-<tarefa>   ex.: feature/admin-servicos
```

1. `git checkout develop && git pull`
2. `git checkout -b feature/api-login`
3. Commits pequenos → `git push -u origin feature/api-login`
4. Abrir um **Pull Request para a `develop`** → revisão → merge com **"Create a merge commit"** (não use squash, para preservar o histórico)
5. No fim da sprint: PR da `develop` → `main`

Uma branch por tarefa, de vida curta (poucos dias).
