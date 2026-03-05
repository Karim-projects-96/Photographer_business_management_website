<?php
// public/search.php
require_once '../includes/db_connect.php';

$title = "Search Results | SnapBroker";
$city = $_GET['city'] ?? '';
$category = $_GET['category'] ?? '';

// Build Base Query
$sql = "SELECT * FROM users WHERE 1=1";
$params = [];

if ($city) {
    $sql .= " AND city LIKE ?";
    $params[] = "%$city%";
}
if ($category) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$photographers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>
        <?php echo $title; ?>
    </title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .search-header {
            background: var(--dark);
            color: white;
            padding: 4rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2.5rem;
            padding: 5rem 5%;
        }

        .pro-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid #e2e8f0;
            transition: transform 0.3s ease;
        }

        .pro-card:hover {
            transform: translateY(-8px);
        }

        .pro-img {
            height: 220px;
            background: #cbd5e1;
            background-size: cover;
            background-position: center;
        }

        .pro-body {
            padding: 1.5rem;
        }

        .pro-tag {
            background: #f1f5f9;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray);
            margin-right: 0.5rem;
        }
    </style>
</head>

<body style="background: #f8fafc;">

    <header class="search-header">
        <div>
            <h1 style="color: white; font-size: 2rem; margin-bottom: 0.5rem;">Results for <span class="text-gradient">
                    <?php echo htmlspecialchars($city ?: 'Everywhere'); ?>
                </span></h1>
            <p style="color: #94a3b8; font-size: 1.1rem;">
                <?php echo count($photographers); ?> Professionals matching your criteria found.
            </p>
        </div>
        <a href="index.php" class="btn btn-primary">Go Back</a>
    </header>

    <div class="results-grid">
        <?php foreach ($photographers as $pro): ?>
            <div class="pro-card">
                <div class="pro-img"
                    style="background-image: url('<?php echo $pro['profile_image_url'] ?: 'https://images.unsplash.com/photo-1542038784456-1ea8e935640e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'; ?>');">
                </div>
                <div class="pro-body">
                    <div style="display: flex; margin-bottom: 0.5rem;">
                        <span class="pro-tag">
                            <?php echo $pro['category']; ?>
                        </span>
                        <span class="pro-tag">
                            <?php echo $pro['city']; ?>
                        </span>
                    </div>
                    <h3 style="margin-bottom: 0.5rem; font-size: 1.25rem;">
                        <?php echo $pro['brand_name']; ?>
                    </h3>
                    <p
                        style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php echo $pro['bio'] ?: 'A professional photography studio specializing in high-quality visual storytelling.'; ?>
                    </p>
                    <a href="portfolio.php?slug=<?php echo $pro['slug']; ?>" class="btn btn-primary"
                        style="width: 100%; text-align: center;">View Portfolio</a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($photographers)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 0;">
                <h2 style="color: var(--gray);">No photographers found in this city yet.</h2>
                <p>Try searching for a different city or category.</p>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>