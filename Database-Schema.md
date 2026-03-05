# Database Schema Documentation (v2.0)

## 1. Core Tables & Fields

### **`users`** (Photographers/Studios)
- `id` (PK), `email`, `password_hash`, `full_name`, `brand_name`, `slug` (unique)
- `city`, `category` (Dropdown: Wedding, Portraits, etc.)
- `commission_rate` (Default: 5%), `razorpay_account_id` (For split payments)
- `bio`, `profile_image_url`, `theme_color` (Hex)

### **`projects`** (The Core Workflow)
- `id` (PK), `photographer_id` (FK -> users.id), `client_name`, `client_phone`
- `event_date`, `location`, `total_deal_amount`, `advance_paid`
- `status` (Inquiry, Booked, Completed, Archive)
- **Proofing Fields:** 
    - `proofing_link`, `proof_upload_timestamp`, `is_watermarked` (Boolean)
    - `purge_status` (Pending, Deleted, Extended)
- **Final Delivery:** 
    - `high_res_link`, `is_drive_unlocked` (Boolean - triggered when balance = 0)

### **`availability`**
- `id` (PK), `photographer_id` (FK), `blocked_date`, `reason` (Project ID or Manual Block)

### **`finances`** (Ledger)
- `id` (PK), `photographer_id` (FK), `project_id` (FK - Optional)
- `type` (Credit/Debit), `amount`, `broker_commission`, `net_amount`
- `description`, `transaction_date`

### **`notifications_log`** (Tracking WhatsApp/System Alerts)
- `id` (PK), `project_id` (FK), `recipient_phone`, `message_type` (40hr-Warning, Payment-Reminder, Verification)
- `sent_at`

## 2. Relationships & Constraints
- **Studio Isolation:** Every query MUST filter by `photographer_id`.
- **One-to-Many:** One User (Photographer) -> Many Projects.
- **One-to-Many:** One Project -> Many Financial entries (e.g., Advance + Final Payment).
- **Triggers:** Updating `projects.advance_paid` to equal `total_deal_amount` should automatically set `is_drive_unlocked = true`.