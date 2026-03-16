<?php
// client/profile.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db_connect.php';

$client_id = $_SESSION['client_id'];
$message = '';
$success = false;

// Fetch current client details
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password']; // optional

    try {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE clients SET full_name = ?, email = ?, phone = ?, password_hash = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $phone, $password_hash, $client_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE clients SET full_name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $phone, $client_id]);
        }
        
        // Update session variables if changed
        $_SESSION['client_name'] = $full_name;
        $_SESSION['client_phone'] = $phone;
        
        $message = "Profile updated successfully!";
        $success = true;
        
        // Refresh client data
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$client_id]);
        $client = $stmt->fetch();
        
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $message = "Error: Email or Phone number already associated with another account.";
        } else {
            $message = "Error updating profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile | Client Dashboard</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            padding: 2rem 1.5rem;
        }

        .main-content {
            flex: 1;
            padding: 3rem 5%;
            background: #f8fafc;
        }

        .nav-link {
            display: block;
            padding: 0.8rem 1rem;
            color: var(--gray);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .nav-link:hover:not(.active) {
            background: #f1f5f9;
            color: var(--dark);
        }

        .form-panel {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
            max-width: 600px;
            margin-top: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--gray);
            font-size: 0.95rem;
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
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <h2 style="color: var(--primary); margin-bottom: 2rem;">SnapBroker.</h2>
            <nav>
                <a href="index.php" class="nav-link">My Bookings</a>
                <a href="profile.php" class="nav-link active">Edit Profile</a>
                <a href="../public/index.php" class="nav-link" style="margin-top: 5rem; color: #38bdf8;">← Back to Home screen</a>
                <a href="logout.php" class="nav-link" style="color: var(--danger);">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1 style="font-size: 1.8rem; margin: 0;">My <span class="text-gradient">Profile</span></h1>
                <p style="color: var(--gray);">Update your personal information and account settings.</p>
            </header>

            <div class="form-panel">
                <?php if ($message): ?>
                    <div class="alert <?php echo $success ? 'alert-success' : 'alert-error'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" required value="<?php echo htmlspecialchars($client['full_name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required value="<?php echo htmlspecialchars($client['email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" required value="<?php echo htmlspecialchars($client['phone']); ?>">
                        <small style="color: var(--gray); font-size: 0.8rem; display: block; margin-top: 0.25rem;">
                            Important: Changing this will only show bookings created with the new number.
                        </small>
                    </div>
                    
                    <div class="form-group" style="margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 2rem;">
                        <label>New Password (Optional)</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Save Changes</button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
