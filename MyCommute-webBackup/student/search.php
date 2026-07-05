<?php
require_once '../config/db.php';
require_login('../login.php');
require_role('student');

$page_title = 'Search Rides';
$asset_prefix = '../';
$base_prefix = '../';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/alert.php';
?>

<main class="page-content">
    <div class="container">
        <div class="card">
            <h2>Search Rides</h2>
            <div class="empty-state">
                <p>Ride search is not available yet. View all currently available rides instead.</p>
                <a class="btn-primary" href="available_rides.php">Available Rides</a>
            </div>
        </div>

        <?php include '../includes/back_button.php'; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
