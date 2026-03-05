-- SnapBroker SaaS Database Initialization Script
-- Version: 2.0

CREATE DATABASE IF NOT EXISTS snapbroker_db;
USE snapbroker_db;

-- 1. Photographers/Studios Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    brand_name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    city VARCHAR(100),
    category ENUM('Wedding', 'Portraits', 'Events', 'Commercial', 'Maternity', 'Other') DEFAULT 'Other',
    commission_rate DECIMAL(5,2) DEFAULT 5.00,
    razorpay_account_id VARCHAR(255),
    bio TEXT,
    profile_image_url VARCHAR(255),
    theme_color VARCHAR(7) DEFAULT '#3498db',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Projects Table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photographer_id INT NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    client_phone VARCHAR(20) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(255),
    total_deal_amount DECIMAL(15,2) NOT NULL,
    advance_paid DECIMAL(15,2) DEFAULT 0.00,
    status ENUM('Inquiry', 'Booked', 'Completed', 'Archive') DEFAULT 'Inquiry',
    
    -- Proofing Logic
    proofing_link VARCHAR(255),
    proof_upload_timestamp TIMESTAMP NULL,
    is_watermarked BOOLEAN DEFAULT TRUE,
    purge_status ENUM('Pending', 'Deleted', 'Extended') DEFAULT 'Pending',
    
    -- Final Delivery
    high_res_link VARCHAR(255),
    is_drive_unlocked BOOLEAN DEFAULT FALSE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (photographer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Availability Table
CREATE TABLE IF NOT EXISTS availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photographer_id INT NOT NULL,
    blocked_date DATE NOT NULL,
    reason VARCHAR(255),
    project_id INT NULL,
    FOREIGN KEY (photographer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- 4. Finances Table
CREATE TABLE IF NOT EXISTS finances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photographer_id INT NOT NULL,
    project_id INT NULL,
    type ENUM('Credit', 'Debit') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    broker_commission DECIMAL(15,2) DEFAULT 0.00,
    net_amount DECIMAL(15,2) NOT NULL,
    description TEXT,
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (photographer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- 5. Notifications Log
CREATE TABLE IF NOT EXISTS notifications_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    recipient_phone VARCHAR(20) NOT NULL,
    message_type ENUM('40hr-Warning', 'Payment-Reminder', 'Verification') NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);
