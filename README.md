# Hello Laravel

A simple Laravel application for testing PHP/Laravel hosting on CribOps.

## Features

- **Hello World endpoint** (`/hello`) - Simple response for basic testing
- **Status endpoint** (`/status`) - JSON response showing:
  - Laravel and PHP versions
  - MySQL database connectivity status
  - Redis (Valkey) connectivity status

## Endpoints

| Endpoint | Description |
|----------|-------------|
| `/` | Default Laravel welcome page |
| `/hello` | Returns "Hello Laravel!" |
| `/status` | JSON health check with database and Redis status |

## Environment Configuration

This app is designed to work with CribOps hosting infrastructure:

- **Database**: MySQL on RDS (same cluster as WordPress sites)
- **Cache/Sessions**: Redis on Valkey/Hive (same as WordPress sites)

See `.env.example` for required environment variables.

## Status Response Example

```json
{
  "status": "ok",
  "laravel_version": "11.x",
  "php_version": "8.3.x",
  "timestamp": "2025-01-08T12:00:00+00:00",
  "database": {
    "status": "connected",
    "driver": "mysql",
    "name": "hello_laravel"
  },
  "redis": {
    "status": "connected",
    "response": "PONG"
  }
}
```

If either service is unavailable, status will be "degraded" with HTTP 503.

## Local Development

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations (if any)
php artisan migrate

# Start development server
php artisan serve
```

## License

MIT
