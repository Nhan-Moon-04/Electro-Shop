Password reset (custom PasswordResetToken) setup

What I changed

-   Added migration: database/migrations/2025_10_19_000001_create_password_reset_tokens_table.php
-   Added model: app/Models/PasswordResetToken.php
-   Added Mailable: app/Mail/PasswordResetTokenMail.php
-   Added email view: resources/views/emails/password_reset_token.blade.php
-   Updated controller: app/Http/Controllers/Api/ForgotPasswordController.php to use the new table and Mailable

How to enable Gmail SMTP

1. Update your .env with these values (example):

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=nthiennhan1611@gmail.com
MAIL_PASSWORD="hijj xnuv juap lqbi"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=nthiennhan1611@gmail.com
MAIL_FROM_NAME="ElectroShop Support"

Note: For Gmail you must use an app password (recommended) or enable "less secure apps" on older accounts. Do NOT commit real credentials to source control.

Migrate and test

-   php artisan migrate
-   Trigger the forgot-password flow from the frontend or call API /api/auth/forgot-password with {"email":"..."}

Security notes and suggestions

-   Token length: currently 64 hex chars from bin2hex(random_bytes(32)) — secure
-   Expiry: 60 minutes by default
-   Token is single-use (Used flag)
-   Consider rate-limiting requests to /forgot-password and queueing emails for reliability
-   Optionally remove older tokens via scheduled task

If you want I can:

-   Add unit tests for the controllers
-   Queue the email sending
-   Add UI feedback for success/error states
