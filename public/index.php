<?php
// Main Landing Page for SnapBroker Marketplace
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_start();
require_once '../includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapBroker | Book Verified Professional Photographers</title>
    <meta name="description"
        content="Centralized marketplace for photographers. Book talent by location, category, and real-time availability.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">

    <style>
        .search-container {
            width: 100%;
            max-width: 800px;
            margin-top: 3rem;
        }

        .search-grid {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 1rem;
            background: white;
            padding: 1rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .search-input {
            width: 100%;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
        }

        .navbar {
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="index.php" class="logo">SnapBroker.</a>
        <div>
            <?php if (isset($_SESSION['client_id'])): ?>
                <a href="../client/index.php" class="btn" style="margin-right: 0.5rem; color: var(--primary); font-weight: 600;">My Profile</a>
                <a href="../client/logout.php" class="btn btn-primary" style="background: var(--danger); border-color: var(--danger);">Logout</a>
            <?php elseif (isset($_SESSION['user_id'])): ?>
                <a href="../dashboard/index.php" class="btn" style="margin-right: 0.5rem; color: var(--primary); font-weight: 600;">Studio Dashboard</a>
                <a href="../dashboard/logout.php" class="btn btn-primary" style="background: var(--danger); border-color: var(--danger);">Logout</a>
            <?php else: ?>
                <a href="../client/login.php" class="btn" style="margin-right: 0.5rem; color: var(--primary); font-weight: 600;">Client Login</a>
                <a href="../dashboard/login.php" class="btn">Photographer Login</a>
                <a href="../dashboard/register.php" class="btn btn-primary">Join as Pro</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero-gradient">
        <div class="container" style="text-align: center; padding: 0 5%;">
            <h1>Find and Book the Perfect <span class="text-gradient">Photographer.</span></h1>
            <p style="font-size: 1.25rem; color: var(--gray); max-width: 600px; margin: 0 auto;"> Verified
                professionals, real-time availability, and secure delivery. All in one place.</p>

            <div class="search-container" style="margin: 3rem auto;">
                <form action="search.php" method="GET" class="search-grid">
                    <input type="text" name="city" placeholder="Which city?" class="search-input" required>
                    <select name="category" class="search-input">
                        <option value="">Any Category</option>
                        <option value="Wedding">Wedding</option>
                        <option value="Portraits">Portraits</option>
                        <option value="Events">Events</option>
                        <option value="Commercial">Commercial</option>
                        <option value="Maternity">Maternity</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Find Talent</button>
                </form>
            </div>
        </div>
    </header>

    <section style="padding: 5rem 5%;">
        <!-- Featured Photographers could go here -->
    </section>

</body>

</html>