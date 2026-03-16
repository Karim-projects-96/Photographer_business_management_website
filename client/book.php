<?php
// client/book.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();

if (!isset($_SESSION['client_id'])) {
    // If not logged in, redirect them to login page but remember where they wanted to go (optional, but let's just redirect for now)
    header("Location: login.php?msg=Please log in to send a booking request");
    exit();
}

require_once '../includes/db_connect.php';

$pro_id = $_GET['pro_id'] ?? null;
if (!$pro_id) {
    die("Photographer ID is missing.");
}

// Get photographer details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$pro_id]);
$pro = $stmt->fetch();

if (!$pro) {
    die("Photographer not found.");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_SESSION['client_name'];
    $client_phone = $_SESSION['client_phone'];
    $event_date = str_replace('T', ' ', $_POST['event_date']); // For datetime-local or just date
    $location = trim($_POST['location']);
    $budget = floatval($_POST['budget']);
    $status = 'Inquiry'; // Pending confirmation

    try {
        $stmt = $pdo->prepare("INSERT INTO projects (photographer_id, client_name, client_phone, event_date, location, total_deal_amount, advance_paid, status) VALUES (?, ?, ?, ?, ?, ?, 0, ?)");
        $stmt->execute([$pro_id, $client_name, $client_phone, $event_date, $location, $budget, $status]);
        
        $message = "Booking request sent successfully to {$pro['brand_name']}!";
    } catch (PDOException $e) {
        $message = "Error sending booking request. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book <?php echo htmlspecialchars($pro['brand_name']); ?> | SnapBroker</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        body {
            background: radial-gradient(circle at top left, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .booking-card {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--gray);
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 0.85rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            outline: none;
            transition: border 0.3s;
        }

        input:focus {
            border-color: var(--primary);
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="booking-card">
        <div style="margin-bottom: 1.5rem;">
            <a href="../public/portfolio.php?slug=<?php echo htmlspecialchars($pro['slug']); ?>" style="color: var(--gray); text-decoration: none; font-weight: 600;">← Back to Portfolio</a>
        </div>
        
        <h2 style="margin-bottom: 0.5rem;">Send a <span class="text-gradient">Booking Request</span></h2>
        <p style="color: var(--gray); margin-bottom: 2rem;">You are requesting to book <strong><?php echo htmlspecialchars($pro['brand_name']); ?></strong>.</p>

        <?php if ($message): ?>
            <div class="alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <a href="index.php" class="btn btn-primary" style="display: block; text-align: center;">View My Bookings</a>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Event Date</label>
                    <input type="date" name="event_date" required>
                </div>
                <div class="form-group">
                    <label>Event Location</label>
                    <input type="text" name="location" required placeholder="e.g. Mumbai Grand Hotel">
                </div>
                <div class="form-group">
                    <label>Estimated Budget / Deal Amount (₹)</label>
                    <input type="number" name="budget" required placeholder="50000">
                    <small style="color: var(--gray); font-size: 0.8rem; display: block; margin-top: 0.25rem;">This is a proposal. The photographer may negotiate.</small>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Submit Request</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
