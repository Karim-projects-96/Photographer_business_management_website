<?php
// client/register.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
require_once '../includes/db_connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO clients (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $phone, $password]);
        header("Location: login.php?msg=Registration successful! Please log in.");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $message = "Error: Email or Phone number already registered.";
        } else {
            $message = "An error occurred during registration. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client Registration | SnapBroker</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        body {
            background: radial-gradient(circle at top left, #f1f5f9 0%, #cbd5e1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
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
            transition: border 0.3s;
        }

        input:focus {
            border-color: var(--primary);
        }
    </style>
</head>

<body>
    <div class="auth-card glass-panel">
        <div style="margin-bottom: 1.5rem;">
            <a href="../public/index.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">← Back to Home screen</a>
        </div>
        <h2 style="margin-bottom: 0.5rem;">Join as a <span class="text-gradient">Client</span></h2>
        <p style="color: var(--gray); margin-bottom: 2rem;">Track all your photography bookings easily.</p>

        <?php if ($message): ?>
            <p style="color: var(--danger); margin-bottom: 1rem;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required placeholder="John Doe">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="client@example.com">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required placeholder="91-9876543210 (must match booking)">
                <small style="color: var(--gray); font-size: 0.75rem;">Use the exact phone number provided to the photographer.</small>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Create My Account</button>
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                Already have an account? <a href="login.php" style="color: var(--primary); font-weight:600;">Login Here</a>
            </p>
        </form>
    </div>
</body>

</html>
