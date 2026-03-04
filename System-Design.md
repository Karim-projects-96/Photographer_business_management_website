# System Design Specifications

## 1. The 48-Hour Purge Logic
To maintain a free tier, the system strictly manages cloud storage:
- Photographer uploads images -> System logs `proof_upload_date`.
- A daily Cron Job checks for dates > 48 hours.
- Images are automatically deleted via API to free up space.

## 2. Payment-Locked Delivery
- Final High-Res link is stored in MySQL.
- PHP logic: If `balance_remaining > 0`, the "Download" button is replaced with a "Pay Balance" button.
- Once balance is 0, the link is revealed to the client.