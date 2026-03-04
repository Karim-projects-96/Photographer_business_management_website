# MVP Technical Documentation

## 1. Authentication Logic
- Photographers register with `password_hash()` encryption.
- Sessions are used to restrict access to the `/dashboard` folder.

## 2. Dynamic Routing
- Uses `.htaccess` to map `snapbroker.com/studio-name` to `profile.php?slug=studio-name`.
- This gives every photographer a professional, personalized URL.

## 3. Storage Strategy
- **Proofing:** Cloudinary (Temporary).
- **Final Delivery:** Google Drive (Permanent & Payment-Locked).
- **Database:** MySQL (Meta-data, transaction logs, and timestamps).