<?php
require_once 'config/db.php';
$page_title = 'Home';
$back_url = isset($_SESSION['user_id']) ? 'dashboard.php' : 'login.php';
$back_label = isset($_SESSION['user_id']) ? 'Back to Dashboard' : 'Go to Login';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/alert.php';
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Move Smarter.<br>Travel Easier.</h1>
            <p>
                MyCommute is a smart transportation and parking platform
                designed to simplify daily commuting for university students.
            </p>

            <div class="hero-buttons">
                <a href="register.php" class="btn-primary">Get Started</a>
                <a href="login.php" class="btn-secondary">Login</a>
            </div>

            <?php include 'includes/back_button.php'; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
