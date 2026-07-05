<?php
require_once 'config/db.php';
require_login('login.php');

$name = $_SESSION['name'];
$role = $_SESSION['role'];

if (!in_array($role, ['student', 'driver', 'admin'], true)) {
    http_response_code(403);
    exit('Access Denied');
}

$page_title = 'Dashboard';
$back_url = 'index.php';
$back_label = 'Back to Home';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/alert.php';
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome, <?php echo e($name); ?> &#128075;</h1>
            <p>You are logged in as <strong><?php echo e(ucfirst($role)); ?></strong></p>

            <?php if ($role === 'driver') { ?>
                <div class="hero-buttons">
                    <a href="driver/add_ride.php" class="btn-primary">Add Ride</a>
                    <a href="driver/my_rides.php" class="btn-secondary">My Rides</a>
                </div>
            <?php } elseif ($role === 'student') { ?>
                <div class="hero-buttons">
                    <a href="student/available_rides.php" class="btn-primary">Available Rides</a>
                    <a href="student/my_bookings.php" class="btn-secondary">My Bookings</a>
                </div>
            <?php } else { ?>
                <div class="hero-buttons">
                    <a href="admin/users.php" class="btn-primary">Manage Users</a>
                    <a href="admin/rides.php" class="btn-secondary">Manage Rides</a>
                </div>
            <?php } ?>

            <?php include 'includes/back_button.php'; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
