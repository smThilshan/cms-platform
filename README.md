# CMS Platform

A headless CMS built with **Laravel 12** (API) and **React 19 + Vite** (frontend). Supports role-based access control (RBAC) with granular privileges, content pages with a rich-text editor, a drag-and-drop menu builder, and full user management. The API is fully documented with **Swagger / OpenAPI** via L5-Swagger, accessible at `http://localhost:8000/api/documentation` — every endpoint is annotated with request schemas, response shapes, and Bearer token authentication.

---

## Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 · PHP 8.2+ · PostgreSQL |
| Auth | Laravel Sanctum (Bearer tokens) |
| Frontend | React 19 · Vite · TypeScript · Tailwind CSS v4 |
| State | TanStack Query (server) · React Context (auth) |
| Editor | CKEditor 5 |

---

## Prerequisites

- PHP 8.2+ with extensions: `pdo_pgsql`, `mbstring`, `xml`, `gd`
- Composer 2
- PostgreSQL 14+
- Node.js 20+ and npm
- (Optional) Docker for running PostgreSQL

---

## Setup from a clean checkout

### 1. Start PostgreSQL

If you have Docker:

```bash
docker run -d \
  --name cms-postgres \
  -e POSTGRES_USER=postgres \
  -e POSTGRES_PASSWORD=secret \
  -e POSTGRES_DB=cms_platform \
  -p 5432:5432 \
  postgres:16
```

Or use your system installation and create the database manually:

```sql
CREATE DATABASE cms_platform;
```

---

### 2. Backend

```bash
cd backend
composer install
```

Copy the environment file and set your values:

```bash
cp .env.example .env
```

Open `.env` and confirm (or update) these keys:

```dotenv
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cms_platform
DB_USERNAME=postgres
DB_PASSWORD=secret
```

Generate the application key:

```bash
php artisan key:generate
```

Run migrations and seed the database:

```bash
php artisan migrate --seed
```

Create the storage symlink so uploaded images are publicly accessible:

```bash
php artisan storage:link
```

Start the backend development server:

```bash
php artisan serve
# listening on http://localhost:8000
```

---

### 3. Frontend

```bash
cd frontend
npm install
npm run dev
# listening on http://localhost:5173
```

---

## Seeded credentials

The seeder (`DatabaseSeeder.php`) creates two users with `firstOrCreate`, so it is safe to re-run.

| Role | Email | Password | Privileges |
|---|---|---|---|
| Admin | `admin@cms.test` | `password` | All privileges |
| Moderator | `moderator@cms.test` | `password` | All except delete operations and user management |

---

## URLs

| URL | Description |
|---|---|
| `http://localhost:5173` | Public-facing site |
| `http://localhost:5173/login` | Admin login |
| `http://localhost:5173/admin` | Admin panel (redirects to Pages) |
| `http://localhost:8000/api/documentation` | Swagger / OpenAPI docs |

---

## Running tests

```bash
cd backend
php artisan test
# 42 tests, 72 assertions
```

---

## Privilege system

Privileges are defined as a PHP enum (`app/Enums/Privilege.php`) — the enum is the single source of truth. The seeder reads enum cases to populate the `privileges` table, so adding a new privilege is a one-file change.

| Privilege key | Who has it by default |
|---|---|
| `pages.list / create / edit / delete` | Admin + Moderator (no delete for Moderator) |
| `menu.list / create / edit / delete` | Admin + Moderator (no delete for Moderator) |
| `roles.list / create / edit / delete` | Admin only |
| `privileges.list` | Admin only |
| `users.list / create / edit / delete` | Admin only |

---

## Project structure

```
cms-platform/
├── backend/          Laravel 12 API
│   ├── app/
│   │   ├── Enums/        Privilege enum (source of truth)
│   │   ├── Http/
│   │   │   ├── Controllers/Admin/
│   │   │   ├── Middleware/CheckPrivilege.php
│   │   │   └── Requests/Admin/
│   │   ├── Models/
│   │   ├── Policies/
│   │   └── Services/     Business logic (PageService, MenuService, etc.)
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/api.php
│
└── frontend/         React 19 + Vite + TypeScript
    └── src/
        ├── api/          Axios wrappers (one file per resource)
        ├── components/   Shared UI (Button, Input, ImagePicker, RichTextEditor, PrivilegeGate)
        ├── contexts/     AuthContext (token + in-memory user object)
        ├── pages/
        │   ├── admin/    CRUD pages for all resources
        │   └── public/   HomePage + PublicPage (slug-based)
        └── types/        Shared TypeScript interfaces
```

---

## Key design decisions

- **Bearer tokens, not cookies** — Sanctum's `statefulApi()` (cookie + CSRF) is not enabled. All API requests send `Authorization: Bearer <token>`. This makes the API usable from any client without CSRF concerns.
- **Privilege gate is in-memory** — `PrivilegeGate` and `hasPrivilege()` read from the user object already in memory (fetched once at login). No extra API calls per gate check.
- **CKEditor requires `licenseKey: 'GPL'`** — CKEditor 5 v42+ requires this key even for free open-source use. Without it the editor silently fails to initialise.
- **StrictMode removed** — React 18+ StrictMode double-mounts components in development. CKEditor fails on the second mount of the same DOM node. `<StrictMode>` is not used in `main.tsx`.
- **Idempotent seeder** — All `firstOrCreate` calls mean `migrate --seed` is safe to re-run against an existing populated database.
