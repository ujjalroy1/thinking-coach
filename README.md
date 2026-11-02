# Laravel Project

## Requirements

Before running this project, make sure you have installed the following tools on your Windows computer:

1. **[Laravel Herd](https://herd.laravel.com/windows)** – Local PHP development environment (includes PHP and web server).
2. **[Composer](https://getcomposer.org/download/)** – PHP dependency manager.
3. **[MySQL](https://dev.mysql.com/downloads/installer/)** – Database management system.
4. **[phpMyAdmin](https://www.phpmyadmin.net/downloads/)** – Web-based MySQL database management tool.
5. **[Visual Studio Code](https://code.visualstudio.com/)** – Recommended code editor.
6. **[Git](https://git-scm.com/downloads)** – Version control system.

## Installation & Setup

### 1. Install Required Software

- Download and install Laravel Herd.
- Install Composer if it is not already available.
- Install MySQL and configure your database credentials.
- Download and configure phpMyAdmin to manage your MySQL databases.
- Install Visual Studio Code and Git.

### 2. Clone the Repository

```bash
git clone https://github.com/ujjalroy1/thinking-coach.git
cd thinking-coach
```

### 3. Navigate to the Project Directory

```bash
cd project-name
```

### 4. Install PHP Dependencies

```bash
composer install
```

### 5. Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 6. Configure Database

Open the `.env` file and update your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create a database with the same name using phpMyAdmin.

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Start the Development Server

```bash
php artisan serve
```

Open your browser and visit:

```text
http://127.0.0.1:8000
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel makes common web development tasks easier, including:

- Simple, fast routing engine.
- Powerful dependency injection container.
- Multiple back-ends for session and cache storage.
- Expressive, intuitive database ORM (Eloquent).
- Database migrations.
- Background job processing.
- Real-time event broadcasting.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has extensive documentation and video tutorials.

- [Laravel Documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com)
- [Laravel Learn](https://laravel.com/learn)

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot.

Install Laravel Boost:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

Please review and follow the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please contact Taylor Otwell at [taylor@laravel.com](mailto:taylor@laravel.com).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
