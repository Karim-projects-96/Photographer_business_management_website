# API & Endpoint Contracts

## Client Side
- `GET /api/search.php`: Parameters `(city, category, date)`. Returns available photographer profiles.
- `POST /api/sign-contract.php`: Accepts Base64 signature and `project_id`. Generates PDF.

## Photographer Side
- `POST /api/upload.php`: Authenticates session and sends images to Cloudinary.
- `GET /api/finance-data.php`: Returns JSON for Chart.js to render monthly profit graphs.

## System Side (Cron)
- `GET /scripts/cleanup.php`: Triggered by server to delete files older than 48 hours.