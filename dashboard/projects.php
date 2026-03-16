<?php
// dashboard/projects.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Deletion
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND photographer_id = ?");
        $stmt->execute([$delete_id, $user_id]);
        $message = "Project deleted successfully.";
    } catch (PDOException $e) {
        $message = "Error deleting project.";
    }
}

// Get all projects for this user
$stmt = $pdo->prepare("SELECT * FROM projects WHERE photographer_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Projects | SnapBroker</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: var(--dark);
            color: white;
            padding: 2rem 1.5rem;
        }

        .main-content {
            flex: 1;
            padding: 3rem 5%;
            background: #f1f5f9;
        }

        .nav-link {
            display: block;
            padding: 0.8rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .nav-link.active {
            background: #1e293b;
            color: white;
        }

        .nav-link:hover {
            color: white;
            background: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-size: 0.85rem;
            color: var(--gray);
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 1.2rem 1rem;
            font-size: 0.95rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-info {
            background: #e0f2fe;
            color: #0369a1;
        }
        
        .action-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-right: 1rem;
        }
        
        .action-link.delete {
            color: var(--danger);
        }
        
        .alert {
            padding: 1rem;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <h2 style="color: var(--primary); margin-bottom: 2rem;">SnapBroker.</h2>
            <nav>
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="projects.php" class="nav-link active">My Projects</a>
                <a href="finance.php" class="nav-link">Financials</a>
                <a href="portfolio.php" class="nav-link">Public Profile</a>
                <a href="../public/index.php" class="nav-link" style="margin-top: 5rem; color: #38bdf8;">← Back to Home screen</a>
                <a href="logout.php" class="nav-link" style="color: var(--danger);">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
                <div>
                    <h1 style="font-size: 1.8rem; margin: 0;">My <span class="text-gradient">Projects</span></h1>
                    <p style="color: var(--gray);">Manage all your bookings and assignments.</p>
                </div>
                <a href="new-project.php" class="btn btn-primary">+ New Booking</a>
            </header>
            
            <?php if ($message): ?>
                <div class="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Event Date</th>
                        <th>Location</th>
                        <th>Total Deal</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $p): ?>
                        <tr>
                            <td style="font-weight: 600;">
                                <?php echo htmlspecialchars($p['client_name']); ?>
                            </td>
                            <td>
                                <?php echo date('M d, Y', strtotime($p['event_date'])); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($p['location'] ?? 'N/A'); ?>
                            </td>
                            <td style="font-weight: 600;">
                                ₹<?php echo number_format($p['total_deal_amount'], 2); ?>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <?php echo htmlspecialchars($p['status']); ?>
                                </span>
                            </td>
                            <td style="display: flex; align-items: center; gap: 0.5rem;">
                                <a href="view-project.php?id=<?php echo $p['id']; ?>" class="action-link">View</a>
                                <!-- Delete Form -->
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');" style="margin: 0;">
                                    <input type="hidden" name="delete_id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm" style="background: var(--danger); border: none;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($projects)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--gray); padding: 3rem;">
                                No projects found. Click on "New Booking" to add a project.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>
