# Laravel Docker Environment Documentation

## Overview
This Docker environment is designed for Laravel development with Caddy server, MySQL, and Redis. It's optimized for performance and includes all necessary tools for Laravel/PHP development.

## Directory Structure
```
.
├── docker/
│   ├── caddy/
│   │   └── Caddyfile          # Caddy server configuration
│   ├── mysql/
│   │   └── my.cnf             # MySQL configuration
│   └── php/
│       ├── Dockerfile         # PHP-FPM image configuration
│       └── php.ini            # PHP configuration
├── docker-compose.yml         # Docker services configuration
├── Makefile                   # Command shortcuts
└── wiki.md                    # This documentation
```

## Services
1. **App (PHP-FPM)**
   - PHP 8.2 with essential extensions
   - Composer
   - Node.js and NPM
   - Custom PHP configuration

2. **Caddy Server**
   - Modern, fast web server
   - Automatic HTTPS (disabled in local)
   - Serves on port 8000

3. **MySQL**
   - Version 8.0
   - Optimized configuration
   - Exposed on port 3306

4. **Redis**
   - Alpine-based for minimal footprint
   - Persistent storage

## Available Commands
Use `make` followed by these commands:

- `help`: Show available commands
- `build`: Build containers
- `up`: Start containers
- `down`: Stop containers
- `logs`: View container logs
- `shell`: Enter app container as root
- `test`: Run Laravel tests
- `clean`: Remove all containers and volumes
- `install`: Install dependencies
- `migrate`: Run database migrations
- `cache`: Clear Laravel cache

## PHP Extensions
Included extensions:
- pdo_mysql
- mbstring
- exif
- pcntl
- bcmath
- gd
- soap
- redis

To add new extensions:
1. Add them to the Dockerfile
2. Rebuild the container: `make build`

## Logging
- All services use JSON logging
- Logs are rotated (max 200KB per file, 10 files max)
- View logs using `make logs`
- Service-specific logs available in respective containers

## Development Workflow
1. Clone your Laravel project
2. Copy these Docker files to your project
3. Run `make build && make up`
4. Run `make install` to install dependencies
5. Access your application at `http://localhost:8000`

## Performance Optimizations
- Alpine-based images for smaller footprint
- Optimized PHP-FPM configuration
- Tuned MySQL settings
- Redis for caching
- Opcache enabled and configured

## Troubleshooting
1. **Permission Issues**
   - Use `make shell` to enter container as root
   - Adjust permissions with: `chown -R laravel:laravel /var/www`

2. **Database Connection Issues**
   - Ensure correct environment variables in `.env`
   - Wait for MySQL to fully initialize

3. **Cache Issues**
   - Run `make cache` to clear all Laravel caches