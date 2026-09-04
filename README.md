# PLANSI

### Plan what matters.

PLANSI is a calm and focused productivity web application designed to help users organize tasks, priorities, categories, deadlines, and progress in one workspace.

The project started as a task-management MVP and has evolved into a production-ready Laravel application with a complete authentication flow, account settings, security hardening, automated testing, and live deployment.

---

## Live Application

**Production:**  
https://plansi-production.up.railway.app

---

## Current Release

**PLANSI Web MVP v1.1**

Status:

- Stable
- Deployed
- Production tested
- Authentication hardened
- Feature development temporarily frozen after v1.1

---

## Core Features

### Task Management

Users can:

- Create tasks
- Edit tasks
- Delete tasks
- Mark tasks as completed or pending
- Assign priorities
- Assign categories
- Set due dates
- Search tasks
- Filter tasks
- Sort tasks

Tasks remain scoped to the authenticated user.

---

### Categories

Users can:

- Create categories
- Rename categories
- Delete categories
- Organize tasks using categories

Category names are unique per user rather than globally.

Deleting a category does not delete its tasks.

---

### Dashboard

The dashboard provides a real-time overview of the user's workspace, including:

- Total tasks
- Pending tasks
- Completed tasks
- High-priority tasks
- Tasks due today
- Overdue tasks
- Category count
- Completion percentage
- Recent tasks
- Quick actions

---

## Authentication & Account Management

PLANSI v1.1 includes a complete authentication experience.

### Authentication

- User registration
- Login
- Logout
- Session regeneration after authentication
- Secure session invalidation on logout
- Login rate limiting

### Email Verification

- Verification required after registration
- Signed verification URLs
- Verification link resend
- Resend throttling
- Protected application routes require a verified email
- Old verification links become invalid after an email change

### Password Recovery

- Forgot password
- Secure password-reset tokens
- Password-reset token expiration
- Reset request throttling
- Generic responses to reduce account enumeration
- Invalid and expired reset links handled safely
- Branded password-reset emails

### Password Management

Authenticated users can change their password by providing:

- Current password
- New password
- Password confirmation

A centralized password policy is shared between:

- Registration
- Password reset
- Password change

### Profile Settings

Users can update:

- Name
- Email address

Changing the email address:

1. Requires the current password.
2. Marks the new email as unverified.
3. Sends a new verification email.
4. Restricts access to verified application areas until verification succeeds.

---

## Security

PLANSI v1.1 includes a dedicated authentication security review.

Implemented protections include:

- CSRF protection
- Authentication middleware
- Email verification middleware
- Signed verification URLs
- Login throttling
- Password-reset throttling
- Generic password-recovery responses
- Secure password hashing
- Password reset token expiration
- Session regeneration after login
- Session invalidation after logout
- CSRF token regeneration after logout
- Remember-token rotation after password reset/change
- User-scoped task and category access
- Current-password confirmation for sensitive account changes
- Trusted proxy configuration for Railway
- Trusted host validation
- Secure production cookies
- Production debug mode disabled
- Production database-backed sessions

---

## Branded Transactional Emails

PLANSI uses custom branded email templates for:

- Email verification
- Password reset

Emails follow the PLANSI visual identity rather than Laravel's default notification design.

Production email delivery currently uses SMTP.

---

## Technology Stack

### Backend

- PHP 8.4
- Laravel 13
- Eloquent ORM

### Frontend

- Blade
- Tailwind CSS
- Alpine.js
- Vite

### Database

- MySQL

### Authentication

- Laravel session authentication
- Laravel email verification
- Laravel Password Broker

### Deployment

- Railway
- HTTPS
- Production environment variables
- Database-backed production sessions
- Automatic production migrations through the deployment process

---

## Application Structure

Main application areas include:

```text
PLANSI
│
├── Authentication
│   ├── Register
│   ├── Login
│   ├── Logout
│   ├── Email Verification
│   ├── Forgot Password
│   └── Reset Password
│
├── Dashboard
│
├── Tasks
│   ├── Create
│   ├── Edit
│   ├── Delete
│   ├── Search
│   ├── Filter
│   ├── Sort
│   └── Toggle Completion
│
├── Categories
│   ├── Create
│   ├── Update
│   └── Delete
│
└── Account Settings
    ├── Profile
    └── Password & Security
```

---

## Due-Date Logic

Pending tasks are separated into mutually exclusive time buckets.

### Overdue

A pending task with a due date before the current calendar day.

### Due Today

A pending task due at any time during the current calendar day.

### Upcoming

A pending task due after today.

Completed tasks are excluded from these urgency buckets.

The application timezone can be configured using:

```env
APP_TIMEZONE=
```

---

## Automated Testing

PLANSI contains feature and security-focused automated tests covering areas such as:

### Authentication

- Registration
- Login throttling
- Email verification
- Verification notification resend
- Invalid verification hashes
- Old verification links after email changes
- Forgot-password requests
- Generic recovery responses
- Valid password resets
- Invalid reset tokens
- Expired reset tokens
- Password confirmation
- Authenticated password changes
- Current-password validation
- Profile updates
- Email uniqueness
- Email re-verification

### Application

- Guest protection
- Task validation
- Category validation
- User ownership boundaries
- Task/category isolation
- Category deletion behavior
- Due-date calculations
- Filtering and sorting
- Dashboard statistics
- Invalid filters
- Demo workspace behavior
- Custom production error pages

Run the complete test suite with:

```bash
php artisan test
```

The production release was deployed only after the full test suite passed.

---

## Local Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd smart-todo
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Create the environment file

```bash
cp .env.example .env
```

On Windows, you can copy `.env.example` manually and rename it to `.env`.

### 5. Generate the application key

```bash
php artisan key:generate
```

### 6. Configure the database

Update the database values in `.env`.

Example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_todo
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Run migrations

```bash
php artisan migrate
```

### 8. Build frontend assets

For development:

```bash
npm run dev
```

Or create a production build:

```bash
npm run build
```

### 9. Start Laravel

```bash
php artisan serve
```

---

## Local Email Development

Production uses real SMTP delivery.

For local development, email can instead be written to Laravel logs:

```env
MAIL_MAILER=log
```

Generated email content can then be inspected in:

```text
storage/logs/laravel.log
```

Never commit SMTP credentials, application passwords, database passwords, or other secrets to Git.

---

## Local Demo Workspace

PLANSI includes an optional development-only demo workspace.

The demo seeder generates:

- 1 demo account
- 10 categories
- 80 varied tasks

The generated workspace includes examples of:

- Pending tasks
- Completed tasks
- Overdue tasks
- Due-today tasks
- Upcoming tasks
- Uncategorized tasks
- Tasks without due dates
- Different priority levels

The seeder refuses to run outside the `local` or `testing` environments and is not executed by the default database seeder.

Set a temporary password:

```powershell
$env:SMART_TODO_DEMO_PASSWORD = '<choose-a-local-password>'
php artisan db:seed --class=DemoWorkspaceSeeder
```

Then sign in using:

```text
demo@smart-todo.test
```

and the password you supplied.

---

## Production Configuration

Production uses environment-specific configuration.

Important production settings include:

```env
APP_ENV=production
APP_DEBUG=false

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

Secrets and credentials are managed using Railway environment variables and are never committed to the repository.

Database migrations are executed during deployment using:

```bash
php artisan migrate --force
```

---

## Screenshots

Screenshots for the current production release can be added here.

Recommended screens:

1. Dashboard
2. Tasks
3. Create/Edit Task
4. Categories
5. Account Settings
6. Password & Security
7. Email Verification
8. Mobile responsive view

Example structure:

```text
docs/
└── screenshots/
    ├── dashboard.png
    ├── tasks.png
    ├── categories.png
    ├── profile-settings.png
    └── mobile-dashboard.png
```

Then they can be displayed in this README using:

```markdown
![PLANSI Dashboard](docs/screenshots/dashboard.png)
```

---

## Release History

### v1.1.0 — Advanced Authentication & Account Experience

Added:

- Email verification
- Forgot password
- Password reset
- Authenticated password change
- Profile settings
- Email-change re-verification
- Centralized password policy
- Authentication security hardening
- Generic password-recovery responses
- Branded authentication emails
- Production database sessions
- Trusted hosts
- Production mail delivery

Also completed:

- Full automated regression testing
- Railway production migration
- Production smoke testing

### v1.0 — Core Web MVP

Included:

- Authentication
- Dashboard
- Tasks CRUD
- Categories CRUD
- Search
- Filters
- Sorting
- Priorities
- Due dates
- Completion tracking
- Responsive user interface
- User ownership protection
- Automated MVP audit tests

---

## Future Direction

PLANSI is intentionally paused after v1.1 while the current product is used as a stable production release.

Potential future enhancements may include:

- Calendar integration
- Drag-and-drop task organization
- Improved reminders
- Additional productivity views
- Mobile application
- API support
- Extended localization

These features are not part of the current release roadmap and are intentionally deferred.

---

## Project Status

**PLANSI Web MVP v1.1 is complete and deployed.**

The current release is treated as a stable product milestone rather than an unfinished prototype.

> Plan what matters.