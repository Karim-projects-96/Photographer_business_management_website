# High-Level Architecture

## System Overview
The platform is built on a LAMP stack (Linux, Apache, MySQL, PHP) using a Multi-tenant approach where one database serves multiple photographer "tenants."

## Integration Points
- **Database:** MySQL for structured data (Users, Projects, Finances).
- **Temporary Storage:** Cloudinary API for proofing galleries (Auto-watermarking).
- **Final Storage:** Google Drive API for high-resolution delivery after 100% payment.
- **Frontend:** Responsive Bootstrap 5 with Chart.js for financial analytics.