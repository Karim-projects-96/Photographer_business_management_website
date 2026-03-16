<?php
// client/login.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
require_once '../includes/db_connect.php';

$message = isset($_GET['msg']) ? $_GET['msg'] : "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch();

    if ($client && password_verify($password, $client['password_hash'])) {
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_name'] = $client['full_name'];
        $_SESSION['client_phone'] = $client['phone'];
        header("Location: ../public/index.php");
        exit();
    } else {
        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client Login | SnapBroker</title>
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
            max-width: 400px;
            padding: 2.5rem;
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
    </style>
</head>

<body>
    <div class="auth-card glass-panel">
        <div style="margin-bottom: 1.5rem;">
            <a href="../public/index.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">← Back to Home screen</a>
        </div>
        <h2 style="margin-bottom: 0.5rem;">Client <span class="text-gradient">Login</span></h2>
        <p style="color: var(--gray); margin-bottom: 2rem;">Access your booking details and deliverables.</p>

        <?php if ($message): ?>
            <p
                style="color: var(--primary); margin-bottom: 1rem; background: #e0e7ff55; padding: 0.5rem; border-radius: 4px;">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="client@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Log In</button>
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                New client? <a href="register.php" style="color: var(--primary); font-weight:600;">Register Here</a>
            </p>
        </form>
    </div>
</body>

</html>
