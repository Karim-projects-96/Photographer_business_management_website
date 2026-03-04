# System Architecture

## Tech Stack
- **OS:** Linux (Ubuntu/Debian)
- **Web Server:** Apache with Mod_Rewrite enabled
- **Database:** MySQL 8.0 (Relational Storage)
- **Language:** PHP 8.2+ (Procedural or OOP)

## Data Flow Logic
1. **Request:** Client searches for 'Pune' + 'Wedding'.
2. **Processing:** PHP queries MySQL `users` and `availability` tables.
3. **Response:** JSON results returned to Bootstrap frontend.
4. **Action:** Project created; Cloudinary API handles watermarked uploads.