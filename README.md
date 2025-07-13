<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

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

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Tosto Coffee Website

This repository contains the website for Tosto Coffee, a coffee shop chain in Panama.

## Local Development Setup

1. Clone the repository
2. Create a `.env` file based on `.env.example`
3. Install PHP dependencies: `composer install`
4. Install JavaScript dependencies: `npm install`
5. Generate application key: `php artisan key:generate`
6. Create a database and update the `.env` file with your database credentials
7. Run migrations: `php artisan migrate`
8. Compile assets: `npm run dev` or `npm run prod`
9. Start the development server: `php artisan serve`

## Authentication System

This application uses an external authentication service called "Clau" instead of Laravel's built-in auth system. The authentication flow works as follows:

1. User submits login credentials on the login page
2. The credentials are sent to the Clau API via the `ApiAuthController@login` method
3. If authentication is successful, the API returns a token
4. The token is stored both in a cookie and in the session
5. The `TransferClauTokenMiddleware` ensures the token is transferred from the cookie to the session when needed
6. Protected routes use the `ClauTokenMiddleware` to verify that the user is authenticated

## Deployment to DigitalOcean

### Environment Configuration

Make sure to set the following environment variables in your DigitalOcean App Platform settings:

```
APP_NAME="Tosto Coffee"
APP_ENV=production
APP_KEY=[your-app-key]
APP_DEBUG=false
APP_URL=https://tostocoffee.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=[your-db-host]
DB_PORT=25060
DB_DATABASE=[your-db-name]
DB_USERNAME=[your-db-username]
DB_PASSWORD=[your-db-password]
MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt

SESSION_DRIVER=file
SESSION_LIFETIME=120

CLAU_API_URL="https://api2.clau.io"
CLAU_API_POS_KEY=[your-pos-key]
CLAU_API_AUTH_KEY=[your-auth-key]
CLAU_API_KEY_PROVIDER=[your-key-provider]
CLAU_APPID=[your-appid]
CLAU_ORIGIN="web_tosto"
CLAU_WEBHOOK_ORIGIN="clauhook"
CLAU_WEBHOOK_KEY=[your-webhook-key]

ZOHO_API_URL="https://www.zohoapis.com/crm/v5"
ZOHO_API_REFRESH_URL="https://accounts.zoho.com/oauth/v2/token"
ZOHO_CLIENT_ID=[your-client-id]
ZOHO_CLIENT_SECRET=[your-client-secret]
ZOHO_REFRESH_TOKEN=[your-refresh-token]
ZOHO_AUTH_TOKEN=[your-auth-token]
```

### Build Commands

In your DigitalOcean App Platform settings, use the following build commands:

```
composer install --no-dev --optimize-autoloader
npm ci
npm run prod
```

### Run Command

```
heroku-php-apache2 public/
```

### Troubleshooting

If you encounter authentication issues, check the following:

1. Make sure the Clau API credentials are correctly set in the environment variables
2. Check the application logs for detailed error messages
3. Use the diagnostic routes (in debug mode only):
   - `/diagnostic/session` - Shows session information
   - `/diagnostic/api-test` - Tests API connection
   - `/diagnostic/clau-test` - Tests Clau API connection
   - `/diagnostic/env` - Shows environment configuration

## Recent Fixes

### Authentication Flow Fix (July 2024)

- Added `TransferClauTokenMiddleware` to ensure tokens are properly transferred from cookies to session
- Improved error handling in API controllers
- Added detailed logging throughout the authentication flow
- Added diagnostic routes for troubleshooting

## License

All rights reserved. This code is proprietary and confidential.
