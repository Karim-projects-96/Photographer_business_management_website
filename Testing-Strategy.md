# Software Testing Plan

## 1. Unit Testing (Logic)
- **Finance Calc:** Test the PHP function to ensure `Total - Advance = Balance` is accurate.
- **Slug Generator:** Ensure special characters in Brand Names are correctly converted to URL-friendly slugs (e.g., "A&B Studio" -> "ab-studio").

## 2. Integration Testing (APIs)
- **Cloudinary:** Verify that images uploaded via the Dashboard appear in the Client's proofing gallery.
- **Google Drive:** Confirm that the "Download" button only appears when the MySQL `is_drive_unlocked` flag is true.

## 3. Security & Boundary Testing
- **Session Hijacking:** Ensure a logged-in Photographer cannot access `/dashboard/edit.php?id=XX` where XX belongs to another user.
- **Date Conflict:** Attempt to book a photographer on a date already marked as 'Booked' in the `availability` table to ensure the system blocks it.