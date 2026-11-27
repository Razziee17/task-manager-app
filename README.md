<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Task Manager

A task manager application built with Laravel. Its frontend uses Blade templates, Tailwind CSS 4 for styling, Vite for building assets, and plain JavaScript in `resources/js/app.js`. It does not use React or Vue.

Users can register, log in, and log out. Overview and task pages require an authenticated session. The task list uses the authenticated session to call Laravel's JSON API with Fetch; tasks are saved in PostgreSQL and scoped to their owner.

### Local PostgreSQL database

The application uses PostgreSQL 18 for its local database. Set the database values in `.env` to `pgsql`, host `127.0.0.1`, port `5432`, database `task_manager`, and username `postgres`. Set `DB_PASSWORD` to the password chosen during PostgreSQL installation. Keep `.env` local and do not commit it.

Run `php artisan migrate` to create the database tables, then run `php artisan db:seed --class=CategorySeeder` to add the Work, School, and Personal categories used by the task form. This does not add demo users or tasks.

The authenticated JSON endpoints are `GET /api/categories` and `GET`, `POST`, `PATCH`, and `DELETE /api/tasks`. Task responses and validation errors use JSON. Tasks are only returned or modified for the signed-in user.

To browse the database, open pgAdmin 4 and connect to the PostgreSQL 18 server. Expand **Databases > task_manager > Schemas > public > Tables**. Refresh the Tables node to see the schema. User accounts created through registration are stored in `users`; tasks created or changed on the Tasks page are stored in `tasks`.

### UI template sources

No third-party UI template was used. The task manager pages and layout were written for this project, starting from the standard Laravel application scaffold.

Technology references:

- [Laravel documentation](https://laravel.com/docs)
- [Blade documentation](https://laravel.com/docs/blade)
- [Tailwind CSS documentation](https://tailwindcss.com/docs)
- [Vite documentation](https://vite.dev/guide/)

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
