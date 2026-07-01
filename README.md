# KCM Musical Store

KCM Musical Store is a custom Laravel e-commerce application built for selling musical instruments, accessories, and related products. It includes a public storefront, customer checkout flow, payment handling, and an admin dashboard for managing products, categories, brands, shipping, orders, and user accounts.

## Project Nature

This is not a starter dashboard or generic admin template. It is a real-world store application focused on retail operations, with features such as:

- Product catalog browsing and search
- Cart and checkout flow
- Order processing and payment integration
- Invoice and receipt generation
- Inventory and stock management
- Admin tools for products, categories, brands, coupons, shipping classes, and users
- Customer authentication, profile, and address management

## Tech Stack

- Laravel 12
- PHP 8.2+
- Vite
- Tailwind CSS 4
- Alpine.js
- Pest for testing

## Requirements

Make sure your environment includes:

- PHP 8.2 or later
- Composer
- Node.js 18 or later
- npm
- A database supported by Laravel, such as MySQL, PostgreSQL, or SQLite

## Setup

1. Install dependencies:

```bash
composer install
php artisan key:generate
```


## Running Locally

Start the full development stack with:

```bash
composer run dev
```

Or run the services separately:

```bash
php artisan serve
npm run dev
```

## Testing

Run the test suite with:

```bash
php artisan test
```

## Production Build

Build frontend assets with:

```bash
npm run build
```

For production deployment, also cache your config, routes, and views as needed using Laravel's optimization commands.

```bash
composer run test
```

Or manually:

```bash
php artisan test
```

Run with coverage:

```bash
php artisan test --coverage
```

Run specific tests:

```bash
php artisan test --filter=ExampleTest
```

## 📜 Available Commands

### Composer Scripts

```bash
# Start development environment
composer run dev

# Run tests
composer run test

# Code formatting (if configured)
composer run format

# Static analysis (if configured)
composer run analyze
```

### NPM Scripts

```bash
# Start Vite dev server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Lint JavaScript/TypeScript
npm run lint

# Format code
npm run format
```

### Artisan Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migrations with seeding
php artisan migrate:fresh --seed

# Generate application key
php artisan key:generate

# Clear all caches
php artisan optimize:clear

# Cache everything for production
php artisan optimize

# Create symbolic link for storage
php artisan storage:link

# Start queue worker
php artisan queue:work

# List all routes
php artisan route:list

# Create a new controller
php artisan make:controller YourController

# Create a new model
php artisan make:model YourModel -m

# Create a new migration
php artisan make:migration create_your_table
```

## Notes

This repository contains a Laravel-based musical store application, not a generic admin starter. The existing folders and routes are organized around storefront, checkout, payment, and back-office retail workflows.
