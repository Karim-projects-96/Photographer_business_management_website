# Photographer Scoring & Ranking Engine

## 1. Ranking Algorithm
To ensure clients find the best talent, photographers are ranked on the home page using a weighted score:
- **Profile Completion (20%)**: Does the user have a bio, location, and gallery?
- **Response Time (30%)**: How quickly do they acknowledge new inquiries?
- **Verified Status (50%)**: Has the Admin manually verified their identity?

## 2. Technical Implementation
- A background script calculates a `trust_score` (0-100) for each user.
- The `users` table is updated weekly.
- SQL Search Query: `SELECT * FROM users ORDER BY trust_score DESC, is_verified DESC;`