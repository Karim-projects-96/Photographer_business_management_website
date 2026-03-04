# Database Schema Documentation

## Core Tables
- **`users`**: Stores credentials, brand profiles, and location for the search engine.
- **`projects`**: Tracks the workflow (Inquiry -> Booked -> Paid) and storage timestamps.
- **`availability`**: A lookup table to prevent double-booking on specific dates.
- **`finances`**: Logs every credit (income) and debit (expense) for dashboard analytics.

## Relationships
- One **User** has Many **Projects**.
- One **Project** has Many **Financial** entries.
- **Foreign Keys:** `photographer_id` ensures data isolation between different studios.