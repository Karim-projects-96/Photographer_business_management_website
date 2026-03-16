<?php
// dashboard/view-project.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];
$project_id = $_GET['id'] ?? null;

if (!$project_id) {
    die("Project ID not provided.");
}

$message = "";

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'accept') {
        try {
            $stmt = $pdo->prepare("UPDATE projects SET status = 'Booked' WHERE id = ? AND photographer_id = ?");
            $stmt->execute([$project_id, $user_id]);
            $message = "Booking confirmed successfully!";
        } catch (PDOException $e) {
            $message = "Error confirming booking.";
        }
    } elseif ($_POST['action'] == 'update_links') {
        $proofing = $_POST['proofing_link'] ?? null;
        $high_res = $_POST['high_res_link'] ?? null;
        try {
            $stmt = $pdo->prepare("UPDATE projects SET proofing_link = ?, high_res_link = ? WHERE id = ? AND photographer_id = ?");
            $stmt->execute([$proofing, $high_res, $project_id, $user_id]);
            $message = "Deliverables updated successfully!";
        } catch (PDOException $e) {
            $message = "Error updating deliverables.";
        }
    }
}

// Fetch project
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND photographer_id = ?");
$stmt->execute([$project_id, $user_id]);
$project = $stmt->fetch();

if (!$project) {
    die("Project not found or access denied.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Project | SnapBroker Dashboard</title>
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

        .details-panel {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .label {
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--gray);
            margin-bottom: 0.25rem;
        }

        .value {
            font-size: 1.1rem;
            color: var(--dark);
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .badge-info {
            background: #e0f2fe;
            color: #0369a1;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-warning {
            background: #fef08a;
            color: #854d0e;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        input {
            width: 100%;
            padding: 0.85rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            outline: none;
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
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <a href="projects.php" style="color: var(--gray); text-decoration: none; font-weight: 600; display: block; margin-bottom: 0.5rem;">← Back to Projects</a>
                    <h1 style="font-size: 1.8rem; margin: 0;">Project <span class="text-gradient">Details</span></h1>
                </div>
            </header>

            <?php if ($message): ?>
                <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="details-panel">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1.5rem;">
                    <div>
                        <h2 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($project['client_name']); ?></h2>
                        <span class="<?php echo $project['status'] == 'Inquiry' ? 'badge-warning' : 'badge-info'; ?>">
                            <?php echo htmlspecialchars($project['status']); ?>
                        </span>
                    </div>

                    <?php if ($project['status'] == 'Inquiry'): ?>
                        <form method="POST">
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="btn btn-primary" style="background: #10b981; border-color: #10b981;">Accept Booking</button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="grid-2">
                    <div>
                        <div class="label">Event Date</div>
                        <div class="value"><?php echo date('F d, Y', strtotime($project['event_date'])); ?></div>

                        <div class="label">Location</div>
                        <div class="value"><?php echo htmlspecialchars($project['location'] ?? 'N/A'); ?></div>

                        <div class="label">Client Phone</div>
                        <div class="value"><?php echo htmlspecialchars($project['client_phone']); ?></div>
                    </div>
                    <div>
                        <div class="label">Total Deal Amount</div>
                        <div class="value">₹<?php echo number_format($project['total_deal_amount'], 2); ?></div>

                        <div class="label">Advance Paid</div>
                        <div class="value">₹<?php echo number_format($project['advance_paid'], 2); ?></div>
                        
                        <div class="label">Balance Due</div>
                        <div class="value" style="color: var(--danger);">₹<?php echo number_format($project['total_deal_amount'] - $project['advance_paid'], 2); ?></div>
                    </div>
                </div>
            </div>

            <div class="details-panel">
                <h3 style="margin-bottom: 1.5rem;">Deliverables & Links</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="update_links">
                    
                    <div class="form-group">
                        <label class="label">Proofing Link (e.g., Google Drive, Pixieset)</label>
                        <input type="url" name="proofing_link" placeholder="https://" value="<?php echo htmlspecialchars($project['proofing_link'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="label">High Resolution Final Delivery Link</label>
                        <input type="url" name="high_res_link" placeholder="https://" value="<?php echo htmlspecialchars($project['high_res_link'] ?? ''); ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Deliverables</button>
                </form>
            </div>
            
        </main>
    </div>
</body>
</html>
