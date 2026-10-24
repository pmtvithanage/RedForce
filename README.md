# Red Force Security Solutions

Red Force is a PHP-based security operations and workforce management system. It provides separate workflows for administrators, clients, supervisors, premise officers, mobile riders, and caretakers.

## Features

- Role-based authentication and dashboards
- Client service requests, sites, packages, and payment history
- Officer recruitment, assignments, schedules, attendance, and performance tracking
- Incident reporting and review workflows
- Equipment requests and approval workflows
- Leave request management
- In-app messages and notifications
- Advertisement management
- Reports and administrative dashboards
- PayHere payment integration support

## Technology stack

- PHP 8.2+
- MySQL or MariaDB
- Apache with URL rewriting enabled
- Composer
- PHPMailer
- PHPUnit
- HTML, CSS, and JavaScript

## Project structure

```text
app/
  config/         Application and database configuration
  controllers/    Request controllers
  helpers/        Shared helper functions
  libraries/      MVC framework libraries
  models/         Database and domain models
  views/          User interface templates
  bootloader.php  Application bootstrap
dev/
  redforce_db.sql Database schema and seed data
public/
  index.php       Web application entry point
  css/            Stylesheets
  js/             Frontend JavaScript
  uploads/        User-uploaded files
tests/            PHPUnit and Selenium tests
```

## Requirements

- PHP 8.2 or later
- Apache and MySQL/MariaDB, such as XAMPP or LAMPP
- Composer
- PHP extensions required by PHP and PHPUnit, including PDO/MySQL

## Installation

1. Clone the repository into the Apache document root. With XAMPP, this is commonly `htdocs/RedForce`.
2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Create the application database and import the supplied schema:

   ```bash
   mysql -u root -p -e "CREATE DATABASE redforce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
   mysql -u root -p redforce_db < dev/redforce_db.sql
   ```

   You can also import `dev/redforce_db.sql` using phpMyAdmin.

4. Update `app/config/config.php` for the local environment:

   - `DB_HOST`, `DB_USER`, `DB_PASSWORD`, and `DB_NAME`
   - `URL_ROOT` to match the local installation path
   - SMTP credentials if email features are required
   - Payment gateway credentials and sandbox/production mode

   Keep real credentials out of version control.

5. Ensure Apache can serve the `public/` directory and that `.htaccess`/URL rewriting is enabled.

## Running locally

For an Apache installation, open:

```text
http://localhost/RedForce/public/
```

If the project is configured so that Apache points directly to `public/`, use the corresponding virtual-host URL instead.

The front controller accepts routes in the following format:

```text
/public/index.php?url=Controller/method/parameter
```

The default route opens the home page.

## Testing

Run the PHPUnit test suite with:

```bash
vendor/bin/phpunit
```

Browser-based tests require the Selenium setup and a running browser driver. See `tests/selenium.config.php` and the files under `selenium-server/` for the project-specific configuration.

## Database changes

The `dev/` directory contains the base database dump and incremental SQL migrations. Apply migrations in the order required by the feature being enabled, and back up the database before changing an existing installation.

## Security notes

- Replace development credentials before deploying.
- Do not commit SMTP passwords, payment secrets, database passwords, or uploaded private documents.
- Use HTTPS in production.
- Set `PAYMENT_SANDBOX` to `false` only after production payment credentials and callback URLs have been configured.
