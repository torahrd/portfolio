# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

TasteRetreat - A Laravel-based social platform for sharing and discovering restaurants. Users can create posts about their favorite shops, follow other users, and engage through comments.

**Tech Stack:**
- Laravel 10.48.29 (PHP 8.2)
- MySQL 8.0
- Alpine.js with CSP build
- TailwindCSS
- Docker environment
- Cloudinary for image storage
- Google Places API integration

## Essential Commands

### Development
```bash
# Start Docker environment
docker-compose up -d

# Install dependencies
composer install
npm install

# Build frontend assets
npm run dev    # Development with hot reload
npm run build  # Production build

# Database operations
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Reset and seed database
php artisan db:seed --class=DevelopmentSeeder  # Seed development data

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/CommentSectionCspTest.php

# Run with coverage
php artisan test --coverage
```

### Code Quality
```bash
# Format PHP code with Laravel Pint
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test
```

## Architecture Overview

### MVC Structure
- **Controllers**: `app/Http/Controllers/` - Handle HTTP requests, coordinate between models and views
- **Models**: `app/Models/` - Eloquent ORM models with relationships
- **Views**: `resources/views/` - Blade templates with component-based architecture
- **Routes**: `routes/web.php` (main app), `routes/api.php` (AJAX endpoints)

### Key Components

#### Authentication & Authorization
- Laravel Breeze for authentication
- Custom middleware in `app/Http/Middleware/`
- Policies in `app/Policies/` for resource authorization
- Private profiles and follow system implemented

#### Content Security Policy (CSP)
- **CRITICAL**: Project uses Alpine.js CSP build for security compliance
- CSP configuration in `config/csp.php`
- Security headers middleware: `app/Http/Middleware/AddSecurityHeaders.php`
- JavaScript must be CSP-compliant (no inline scripts, use data attributes)

#### Frontend Architecture
- Component-based Blade templates in `resources/views/components/`
- Alpine.js for interactivity (CSP build)
- JavaScript modules in `resources/js/components/`
- TailwindCSS for styling

#### API Integration
- Google Places API service: `app/Services/GooglePlacesService.php`
- Shop search service: `app/Services/ShopSearchService.php`
- Cloudinary for image uploads

### Database Schema
Key models and relationships:
- **User** → hasMany Posts, Comments, Shops
- **Post** → belongsTo User, Shop; hasMany Comments
- **Shop** → hasMany Posts; belongsTo User (creator)
- **Comment** → belongsTo Post, User; nested comments supported
- **Follow** system with pending/accepted states

## Current Development Focus

According to `todo.md`, the project is in Phase 1: Security Hardening
- CSP implementation for comment system (TDD approach)
- Private post authorization
- Production security deployment
- Backup system implementation

## Important Considerations

### Security Requirements
- **CSP Compliance**: All JavaScript must follow CSP rules
- No inline event handlers (use Alpine.js directives)
- Use `:value` + `@input` instead of `x-model` for form bindings
- API endpoints must validate and sanitize all inputs

### Testing Strategy
- TDD (Test-Driven Development) approach for new features
- Factories available for Shop, Post, Comment models
- Test database uses SQLite in-memory for speed

### Production Environment
- URL: https://taste-retreat.com
- Deployment requires cache clearing and migration checks
- HSTS headers configured for HTTPS enforcement
- Environment-specific configurations in `.env`

### Common Pitfalls to Avoid
- Don't use `x-model` with Alpine.js CSP build (use `:value` + `@input`)
- Always check for N+1 queries when loading relationships
- Validate file uploads and implement size limits
- Test both AJAX and non-AJAX paths for forms (progressive enhancement)

## Development Workflow

1. Check `todo.md` for current tasks and priorities
2. Follow TDD cycle: Red → Green → Refactor → Commit
3. Run tests before committing
4. Use Laravel Pint for code formatting
5. Document significant changes in `docs/` directory
6. Create feature branches from `main` branch

## Debugging Tips

- Laravel Telescope available for request debugging
- Check `storage/logs/laravel.log` for application logs
- Browser console for CSP violations
- `php artisan tinker` for interactive debugging
- Docker logs: `docker-compose logs -f app`