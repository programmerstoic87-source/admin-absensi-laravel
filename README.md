# Admin Absensi Laravel

This project is a Laravel starter for an attendance admin system with multi-branch support, role-based access, attendance tracking, izin approval, patrol checkpoints, and user-facing API endpoints.

## Stack
- PHP 8.2
- Laravel 11
- PostgreSQL / Supabase-ready
- Blade admin UI
- REST API endpoints for user app

## Features
- Admin login and role checks
- Multi-cabang architecture
- User management
- Location management
- Shift configuration
- Patrol checkpoints
- Attendance review
- Izin approval workflow
- Reports and audit logs
- JSON API for mobile / app user

## Setup
1. Copy `.env.example` to `.env`
2. Configure DB settings
3. Run:
   ```bash
   composer install
   php artisan migrate
   php artisan db:seed
   php artisan serve
   ```
4. Login with:
   - Email: admin@example.com
   - Password: admin123

## Default routes
- Admin login: `/login`
- Dashboard: `/dashboard`
- API login: `/api/login`
- User dashboard: `/api/dashboard`

## Notes
This repository is intentionally scaffolded to be ready for a real production init, but it still requires dependency installation and final DB setup using your environment.
