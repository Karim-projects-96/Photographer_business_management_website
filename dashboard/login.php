<?php
// dashboard/login.php
session_start();
require_once '../includes/db_connect.php';

$message = isset($_GET['msg']) ? $_GET['msg'] : "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['brand_name'] = $user['brand_name'];
        header("Location: index.php");
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
    <title>Pro Login | SnapBroker</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        body {
            background: radial-gradient(circle at top right, #f8fafc 0%, #e2e8f0 100%);
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
        <h2 style="margin-bottom: 0.5rem;">Welcome <span class="text-gradient">Back</span></h2>
        <p style="color: var(--gray); margin-bottom: 2rem;">Access your studio dashboard.</p>

        <?php if ($message): ?>
            <p
                style="color: var(--primary); margin-bottom: 1rem; background: #e0e7ff55; padding: 0.5rem; border-radius: 4px;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="pro@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Sign In</button>
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                New photographer? <a href="register.php" style="color: var(--primary); font-weight:600;">Join for
                    free</a>
            </p>
        </form>
    </div>
</body>

</html>