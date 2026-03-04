# Environment Setup & Deployment

## 1. Local Development (XAMPP/WAMP)
- **PHP Version:** 8.2.x
- **MySQL Version:** 8.0
- **Apache:** `mod_rewrite` enabled for clean URLs.
- **Composer:** Required for Cloudinary and Google Drive SDKs.

## 2. Server Automation (Cron Jobs)
To automate the "Broker" logic, the following Crontab entries are required:
- **Daily Cleanup (0 0 * * *):** `php /var/www/html/scripts/cleanup.php` 
  (Deletes Cloudinary files > 48 hours old).
- **Monthly Reminder (0 0 1 * *):** `php /var/www/html/scripts/reminders.php` 
  (Notifies photographers to clear local storage after 30 days).

## 3. Deployment Checklist
- Change `DB_HOST`, `DB_USER`, and `DB_PASS` in `config.php`.
- Set `is_debug = false` to hide PHP errors from public users.
- Ensure the `/uploads` and `/temp` folders have `755` permissions.