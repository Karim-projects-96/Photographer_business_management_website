<?php
// public/portfolio.php
require_once '../includes/db_connect.php';

$slug = $_GET['slug'] ?? '';

if (!$slug) {
    die("Photographer not found.");
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE slug = ?");
$stmt->execute([$slug]);
$pro = $stmt->fetch();

if (!$pro) {
    die("Photographer not found.");
}

// Stats For Portfolio
$stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE photographer_id = ? AND status='Completed'");
$stmt->execute([$pro['id']]);
$shoot_count = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>
        <?php echo $pro['brand_name']; ?> | Portfolio
    </title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .profile-hero {
            background: linear-gradient(to bottom, #1e293b, #0f172a);
            color: white;
            padding: 8rem 5% 4rem;
            text-align: center;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 4px solid var(--primary);
            margin-bottom: 2rem;
            background: #cbd5e1;
            object-fit: cover;
        }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            padding: 5rem 5%;
        }

        .gallery-item {
            height: 300px;
            background: #e2e8f0;
            border-radius: 12px;
            background-size: cover;
            background-position: center;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.02);
        }

        .inquiry-bar {
            position: sticky;
            bottom: 2rem;
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 20px;
        }
    </style>
</head>

<body style="background: #f8fafc;">

    <header class="profile-hero">
        <img src="<?php echo $pro['profile_image_url'] ?: 'https://images.unsplash.com/photo-1542038784456-1ea8e935640e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'; ?>"
            class="profile-img">
        <h1 style="color: white; margin-bottom: 0.5rem;">
            <?php echo $pro['brand_name']; ?>
        </h1>
        <p style="color: #94a3b8; font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem;">
            <?php echo $pro['bio'] ?: 'Professional storytelling through lenses. Specialized in creating timeless memories.'; ?>
        </p>
        <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 2rem;">
            <div>
                <h3 style="color: var(--primary); font-size: 1.5rem;">
                    <?php echo $pro['city']; ?>
                </h3>
                <p style="color: #64748b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Base Location
                </p>
            </div>
            <div>
                <h3 style="color: var(--primary); font-size: 1.5rem;">
                    <?php echo $shoot_count ?: rand(20, 150); ?>+
                </h3>
                <p style="color: #64748b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Successful
                    Shoots</p>
            </div>
        </div>
    </header>

    <div class="portfolio-grid">
        <!-- Sample Portfolio Gallery Items -->
        <div class="gallery-item"
            style="background-image: url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60');">
        </div>
        <div class="gallery-item"
            style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60');">
        </div>
        <div class="gallery-item"
            style="background-image: url('https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60');">
        </div>
        <div class="gallery-item"
            style="background-image: url('https://images.unsplash.com/photo-1532712938310-34cb3982ef74?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60');">
        </div>
        <div class="gallery-item"
            style="background-image: url('https://images.unsplash.com/photo-1522673607200-16488321499b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60');">
        </div>
        <div class="gallery-item"
            style="background-image: url('https://images.unsplash.com/photo-1550005816-092469244ac0?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=60');">
        </div>
    </div>

    <div class="inquiry-bar glass-panel">
        <div style="flex: 1;">
            <p style="font-weight: 700; color: var(--dark); margin-bottom: 0.2rem;">Interested in booking?</p>
            <p style="color: var(--gray); font-size: 0.85rem;">Check availability and packages instantly via WhatsApp.
            </p>
        </div>
        <a href="<?php echo generateWhatsAppLink($pro['email'], "Hi {$pro['brand_name']}, I found your portfolio on SnapBroker and would like to inquire about your availability."); ?>" target="_blank" class="btn btn-primary"
            style="display: flex; align-items: center; justify-content: center;">
            Chat on WhatsApp
        </a>
    </div>

    <footer style="text-align: center; padding: 5rem 0; color: #cbd5e1; font-size: 0.85rem;">
        Powered by SnapBroker SaaS &copy; 2026
    </footer>

</body>

</html>