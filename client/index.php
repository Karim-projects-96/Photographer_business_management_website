<?php
// client/index.php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db_connect.php';

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];
$client_phone = $_SESSION['client_phone'];

// Fetch projects/bookings for this client based on their registered phone number
$stmt = $pdo->prepare("
    SELECT p.*, u.brand_name, u.slug, u.profile_image_url 
    FROM projects p
    JOIN users u ON p.photographer_id = u.id
    WHERE p.client_phone = ? 
    ORDER BY p.event_date DESC
");
$stmt->execute([$client_phone]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Bookings | Client Dashboard</title>
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

        .booking-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .booking-info h3 {
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .booking-info p {
            color: var(--gray);
            font-size: 0.95rem;
            margin-bottom: 0.2rem;
        }

        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pending {
            background: #fef08a;
            color: #854d0e;
        }

        .badge-booked {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-completed {
            background: #dcfce7;
            color: #166534;
        }

        .booking-action {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <h2 style="color: var(--primary); margin-bottom: 2rem;">SnapBroker.</h2>
            <nav>
                <a href="index.php" class="nav-link active">My Bookings</a>
                <a href="profile.php" class="nav-link">Edit Profile</a>
                <a href="../public/index.php" class="nav-link" style="margin-top: 5rem; color: #38bdf8;">← Back to Home screen</a>
                <a href="logout.php" class="nav-link" style="color: var(--danger);">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header style="margin-bottom: 3rem;">
                <h1 style="font-size: 1.8rem; margin: 0;">Welcome, <span class="text-gradient"><?php echo htmlspecialchars($client_name); ?></span></h1>
                <p style="color: var(--gray);">Here is the list of your photography bookings and their current status.</p>
            </header>

            <div class="bookings-list">
                <?php foreach ($bookings as $b): ?>
                    <div class="booking-card">
                        <div class="booking-info">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                                <h3><?php echo htmlspecialchars($b['brand_name']); ?></h3>
                                <span class="badge 
                                    <?php echo strtolower($b['status']) == 'completed' ? 'badge-completed' : (strtolower($b['status']) == 'booked' ? 'badge-booked' : 'badge-pending'); ?>">
                                    <?php echo htmlspecialchars($b['status']); ?>
                                </span>
                            </div>
                            <p><strong>Event Date:</strong> <?php echo date('M d, Y', strtotime($b['event_date'])); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($b['location'] ?? 'N/A'); ?></p>
                            <p style="margin-top: 0.5rem;"><strong>Total Deal Amount:</strong> ₹<?php echo number_format($b['total_deal_amount'], 2); ?></p>
                            <p><strong>Advance Paid:</strong> ₹<?php echo number_format($b['advance_paid'], 2); ?></p>
                            
                            <?php if ($b['proofing_link']): ?>
                                <p style="margin-top: 1rem;">
                                    <strong>Proofing Link:</strong> 
                                    <a href="<?php echo htmlspecialchars($b['proofing_link']); ?>" target="_blank" style="color: var(--primary); text-decoration: none;">View Proofs</a>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($b['high_res_link']): ?>
                                <p style="margin-top: 0.5rem;">
                                    <strong>Final Deliverables:</strong> 
                                    <a href="<?php echo htmlspecialchars($b['high_res_link']); ?>" target="_blank" style="color: #10b981; text-decoration: none; font-weight: 600;">Download High Res Photos</a>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="booking-action">
                            <a href="../public/portfolio.php?slug=<?php echo htmlspecialchars($b['slug']); ?>" class="btn btn-secondary" style="margin-bottom: 0.5rem; display: block; border: 1px solid var(--primary); color: var(--primary);">View Photographer</a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($bookings)): ?>
                    <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486776.png" style="width: 80px; opacity: 0.5; margin-bottom: 1rem;" alt="No Bookings">
                        <h2 style="color: var(--gray); font-size: 1.5rem; margin-bottom: 0.5rem;">No bookings found</h2>
                        <p style="color: #94a3b8;">It looks like you haven't booked any photographers yet, or the phone number provided does not match any current bookings.</p>
                        <a href="../public/index.php" class="btn btn-primary" style="margin-top: 1.5rem; display: inline-block;">Find a Photographer</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
