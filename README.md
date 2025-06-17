<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# FinMan - Multi-Tenant Financial SaaS

A secure, multi-tenant financial reporting and company valuation SaaS application built with Laravel and Tailwind CSS.

## Features Implemented

### Core Functionality
- ✅ **Multi-tenant Architecture**: Complete data isolation by company
- ✅ **User Authentication**: Laravel Breeze with email verification
- ✅ **Company Setup**: Guided onboarding for new companies
- ✅ **Financial Dashboard**: Comprehensive overview of company finances
- ✅ **Transaction Management**: Track income and expenses
- ✅ **Company Valuation**: Basic DCF and revenue multiple calculations
- ✅ **Analytics**: Burn rate, runway, and profit tracking

### Security & Data Isolation
- ✅ **TenantMiddleware**: Ensures users only see their company's data
- ✅ **Role-based Access**: Admin and user roles
- ✅ **Email Verification**: Required for account activation
- ✅ **Trial Management**: 30-day free trial system

### Technical Features
- ✅ **Modern UI**: Tailwind CSS with responsive design
- ✅ **File Uploads**: Company logo support
- ✅ **Database**: SQLite with proper relationships
- ✅ **Eloquent Models**: Rich business logic and scopes

## Database Schema

### Companies Table
- Company profile (name, logo, colors, contact info)
- Subscription management (trial, active, suspended)
- Multi-tenant isolation

### Users Table
- Linked to companies with foreign key
- Role-based permissions (admin/user)
- Email verification support

### Transactions Table
- Complete financial transaction tracking
- Categories, methods, fees, and balances
- Multi-tenant filtered by company_id

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite

### Installation
1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations:
   ```bash
   php artisan migrate
   ```

5. Build assets:
   ```bash
   npm run build
   ```

6. Create storage link:
   ```bash
   php artisan storage:link
   ```

7. Start the server:
   ```bash
   php artisan serve
   ```

## Application Flow

1. **User Registration**: New users register with email verification
2. **Company Setup**: Users create their company profile (30-day trial)
3. **Dashboard Access**: Multi-tenant middleware ensures data isolation
4. **Financial Management**: Add transactions, view analytics, track valuation

## Key Routes

- `/` - Welcome page
- `/register` - User registration
- `/login` - User login
- `/setup/company` - Company onboarding
- `/dashboard` - Main financial dashboard
- `/transactions` - Transaction management
- `/company/profile` - Company settings

## Multi-Tenancy Implementation

Each user belongs to exactly one company. The `TenantMiddleware` ensures:
- Users can only access their company's data
- All database queries are automatically filtered by `company_id`
- Company information is shared with all views
- Subscription status is enforced

## Next Steps

Ready to implement:
- Transaction CRUD operations
- Advanced analytics and charts
- Export functionality
- User management within companies
- Subscription payment integration
- API endpoints for mobile apps

## Architecture

The application follows Laravel best practices:
- **Models**: Rich domain models with business logic
- **Controllers**: Thin controllers handling HTTP requests
- **Middleware**: Multi-tenancy and security enforcement
- **Views**: Blade templates with Tailwind CSS
- **Database**: Eloquent ORM with proper relationships

## Security Considerations

- All data is isolated by company
- Email verification required
- File upload restrictions
- CSRF protection
- SQL injection prevention through Eloquent
- XSS protection through Blade templating

---

**Status**: Ready for testing and further development. The core multi-tenant financial SaaS foundation is complete and secure.

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

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
