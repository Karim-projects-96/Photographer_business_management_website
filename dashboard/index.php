<?php
// dashboard/index.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];

// Get Stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE photographer_id = ?");
$stmt->execute([$user_id]);
$total_projects = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(total_deal_amount) FROM projects WHERE photographer_id = ?");
$stmt->execute([$user_id]);
$total_revenue = $stmt->fetchColumn() ?: 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE photographer_id = ? AND status = 'Inquiry'");
$stmt->execute([$user_id]);
$pending_inquiries = $stmt->fetchColumn();

// Get recent projects
$stmt = $pdo->prepare("SELECT * FROM projects WHERE photographer_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Studio Dashboard | SnapBroker</title>
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

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
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
    </style>
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <h2 style="color: var(--primary); margin-bottom: 2rem;">SnapBroker.</h2>
            <nav>
                <a href="index.php" class="nav-link active">Dashboard</a>
                <a href="projects.php" class="nav-link">My Projects</a>
                <a href="finance.php" class="nav-link">Financials</a>
                <a href="portfolio.php" class="nav-link">Public Profile</a>
                <a href="../public/index.php" class="nav-link" style="margin-top: 5rem; color: #38bdf8;">← Back to Home screen</a>
                <a href="logout.php" class="nav-link" style="color: var(--danger);">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
                <div>
                    <h1 style="font-size: 1.8rem; margin: 0;">Welcome, <span class="text-gradient">
                            <?php echo $_SESSION['brand_name']; ?>
                        </span></h1>
                    <p style="color: var(--gray);">Here is your studio overview for today.</p>
                </div>
                <a href="new-project.php" class="btn btn-primary">+ New Booking</a>
            </header>

            <div class="stat-grid">
                <div class="stat-card">
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 0.5rem;">Active Projects</p>
                    <h2 style="font-size: 2rem;">
                        <?php echo $total_projects; ?>
                    </h2>
                </div>
                <div class="stat-card">
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Revenue</p>
                    <h2 style="font-size: 2rem;">₹
                        <?php echo number_format($total_revenue, 2); ?>
                    </h2>
                </div>
                <div class="stat-card">
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 0.5rem;">New Inquiries</p>
                    <h2 style="font-size: 2rem; color: var(--secondary);">
                        <?php echo $pending_inquiries; ?>
                    </h2>
                </div>
            </div>

            <h3 style="margin-bottom: 1.5rem;">Recent Assignments</h3>
            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Event Date</th>
                        <th>Total Deal</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_projects as $p): ?>
                        <tr>
                            <td style="font-weight: 600;">
                                <?php echo $p['client_name']; ?>
                            </td>
                            <td>
                                <?php echo date('M d, Y', strtotime($p['event_date'])); ?>
                            </td>
                            <td>₹
                                <?php echo number_format($p['total_deal_amount'], 2); ?>
                            </td>
                            <td style="color: var(--danger); font-weight: 600;">
                                ₹
                                <?php echo number_format(($p['total_deal_amount'] - $p['advance_paid']), 2); ?>
                            </td>
                            <td><span class="badge badge-info">
                                    <?php echo $p['status']; ?>
                                </span></td>
                            <td><a href="view-project.php?id=<?php echo $p['id']; ?>"
                                    style="color: var(--primary); text-decoration: none; font-weight: 600;">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent_projects)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--gray);">No projects found. Create your
                                first booking!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>