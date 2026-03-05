<?php
// dashboard/register.php
require_once '../includes/db_connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];
    $brand_name = $_POST['brand_name'];
    $city = $_POST['city'];
    $category = $_POST['category'];

    // Generate SEO Friendly Slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $brand_name)));

    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, full_name, brand_name, slug, city, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$email, $password, $full_name, $brand_name, $slug, $city, $category]);
        header("Location: login.php?msg=Registration successful! Please login.");
        exit();
    } catch (PDOException $e) {
        $message = "Error: Email or Studio Name already exists.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Join SnapBroker | Pro Registration</title>
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

        input,
        select {
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
        <h2 style="margin-bottom: 0.5rem;">Join as a <span class="text-gradient">Pro</span></h2>
        <p style="color: var(--gray); margin-bottom: 2rem;">Start managing your photography business.</p>

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
                <label>Studio/Brand Name</label>
                <input type="text" name="brand_name" required placeholder="Shutter Magic">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="pro@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" required placeholder="Mumbai">
            </div>
            <div class="form-group">
                <label>Primary Category</label>
                <select name="category">
                    <option value="Wedding">Wedding</option>
                    <option value="Portraits">Portraits</option>
                    <option value="Events">Events</option>
                    <option value="Commercial">Commercial</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Create My
                Portfolio</button>
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                Already a member? <a href="login.php" style="color: var(--primary); font-weight:600;">Login Here</a>
            </p>
        </form>
    </div>
</body>

</html>