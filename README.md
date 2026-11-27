# Task Manager

A Laravel task manager with PostgreSQL storage, session-based authentication, and a JSON API. The interface uses Blade, Tailwind CSS 4, Vite, and plain JavaScript with Fetch.

## Features

- Register, log in, and log out with authenticated Overview and Tasks pages.
- Create, view, edit, complete, and delete tasks.
- Organize tasks by category and filter by category or status; search and paginate the task list.
- Store tasks in PostgreSQL and scope task access to the signed-in user.
- Display task totals and per-category counts on the Overview page.

## Requirements

- PHP 8.3 or later with `pdo_pgsql`
- Composer
- PostgreSQL 18
- Node.js and npm

## Local setup

1. Install the PHP and Node.js dependencies:

   ```sh
   composer install
   npm install
   ```

2. Create a local environment file and generate the Laravel application key:

   ```sh
   if (-not (Test-Path .env)) { Copy-Item .env.example .env }
   php artisan key:generate
   ```

   If `.env` already exists, keep it and do not overwrite your local settings.

3. In `.env`, configure PostgreSQL:

   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=task_manager
   DB_USERNAME=postgres
   DB_PASSWORD="your-local-postgres-password"
   ```

   Replace the password locally with the one set for your PostgreSQL account. Never commit `.env` or share its password.

4. Create a PostgreSQL database named `task_manager` if it does not already exist. In pgAdmin, right-click **Databases**, select **Create > Database**, enter `task_manager`, and save.

5. Create the tables and the task categories:

   ```sh
   php artisan migrate
   php artisan db:seed --class=CategorySeeder
   ```

   The category seeder adds Work, School, and Personal; it does not create demo users or tasks.

6. In separate terminals, start the Laravel server and Vite:

   ```sh
   php artisan serve
   ```

   ```sh
   npm run dev
   ```

7. Open `http://127.0.0.1:8000` and register an account.

## Task API

The API uses the signed-in browser session and Laravel CSRF protection. Task endpoints only return or modify tasks belonging to the authenticated user. Responses use JSON; invalid input returns JSON validation errors.

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/api/categories` | List categories and the current user's task count per category |
| `GET` | `/api/tasks` | List the current user's tasks |
| `GET` | `/api/tasks/{task}` | Show one of the current user's tasks |
| `POST` | `/api/tasks` | Create a task |
| `PUT` / `PATCH` | `/api/tasks/{task}` | Update a task |
| `DELETE` | `/api/tasks/{task}` | Delete a task |

Task fields are `title`, `description` (optional), `category_id`, `status` (`pending` or `done`), and `due_date` (`YYYY-MM-DD`). New tasks default to `pending`.

## Tests and checks

Run the feature and unit tests:

```sh
php artisan test
```

Tests use in-memory SQLite and do not change the local PostgreSQL database. Check PHP formatting and build the frontend with:

```sh
vendor/bin/pint --test
npm run build
```

## Inspect PostgreSQL data

In pgAdmin, expand **Servers > PostgreSQL 18 > Databases > task_manager > Schemas > public > Tables**. Right-click `users` or `tasks` and choose **View/Edit Data > All Rows**.
