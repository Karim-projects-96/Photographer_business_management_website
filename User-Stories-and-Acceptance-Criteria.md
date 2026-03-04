# User Stories & Acceptance Criteria

## Story 1: Client Discovery
- **As a Client**, I want to see only available photographers for my wedding date so I don't waste time inquiring with busy professionals.
- **Acceptance Criteria**: The SQL query must perform a `LEFT JOIN` on the `availability` table and filter out matching dates.

## Story 2: Photographer Onboarding
- **As a Photographer**, I want to set my own "Starting Price" and "Location" so that I appear in relevant searches.
- **Acceptance Criteria**: The profile settings page must update the `users` table successfully with these parameters.

## Story 3: Secure Delivery
- **As a Client**, I want to see my photos before paying the full amount, but I should only be able to download high-res files after final payment.
- **Acceptance Criteria**: The proofing gallery must show watermarked thumbnails; the Google Drive download link must remain hidden until `is_drive_unlocked` is TRUE.