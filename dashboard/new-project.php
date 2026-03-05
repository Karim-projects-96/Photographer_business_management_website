<?php
// dashboard/new-project.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $total_deal_amount = $_POST['total_deal_amount'];
    $advance_paid = $_POST['advance_paid'];
    $status = 'Booked';

    try {
        $stmt = $pdo->prepare("INSERT INTO projects (photographer_id, client_name, client_phone, event_date, location, total_deal_amount, advance_paid, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $client_name, $client_phone, $event_date, $location, $total_deal_amount, $advance_paid, $status]);
        header("Location: index.php?msg=Project created successfully!");
        exit();
    } catch (PDOException $e) {
        $message = "Error: Could not create project. please check the fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Booking | SnapBroker</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .form-panel {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
            max-width: 600px;
            margin: 3rem auto;
        }

        .form-group {
            margin-bottom: 1.25rem;
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
            padding: 0.8rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            outline: none;
        }
    </style>
</head>

<body style="background: #f1f5f9;">

    <div class="form-panel">
        <h2 style="margin-bottom: 2rem;">Create <span class="text-gradient">New Assignment</span></h2>

        <?php if ($message): ?>
            <p style="color: var(--danger); margin-bottom: 1rem;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Client Name</label>
                <input type="text" name="client_name" required placeholder="Alice Johnson">
            </div>
            <div class="form-group">
                <label>Client WhatsApp/Phone</label>
                <input type="text" name="client_phone" required placeholder="91-9876543210 (with country code)">
            </div>
            <div class="form-group">
                <label>Booking Date</label>
                <input type="date" name="event_date" required>
            </div>
            <div class="form-group">
                <label>Event Location</label>
                <input type="text" name="location" required placeholder="Mumbai Grand Hall">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Total Deal Amount (₹)</label>
                    <input type="number" name="total_deal_amount" required placeholder="50000">
                </div>
                <div class="form-group">
                    <label>Advance Received (₹)</label>
                    <input type="number" name="advance_paid" required value="0">
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">Confirm Booking</button>
                <a href="index.php" class="btn btn-secondary"
                    style="background: #e2e8f0; color: var(--dark); flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>

</body>

</html>