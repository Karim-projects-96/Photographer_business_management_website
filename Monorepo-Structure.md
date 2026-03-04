# Monorepo Project Structure

## 1. Overview
The project is organized into three main portals (Admin, Dashboard, Public) to ensure clean separation of concerns while sharing a single MySQL database.

## 2. Directory Map
- `/admin`: Control panel for the Broker (you) to verify photographers and manage disputes.
- `/dashboard`: The photographer's business suite (Invoices, Uploads, Financial Charts).
- `/public`: The client-facing marketplace, search engine, and individual portfolios.
- `/includes`: Core PHP logic, `db_connect.php`, and global functions.
- `/assets`: CSS (Bootstrap), JS (Chart.js, SignaturePad), and system images.
- `/scripts`: Server-side Cron jobs for the 48-hour data purge and 30-day reminders.