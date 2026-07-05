<?php
require_once '../config/db.php';
require_login('../login.php');
require_role('driver');

$driver_id = (int) $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM rides WHERE driver_id = ? ORDER BY ride_date, ride_time');
$stmt->bind_param('i', $driver_id);
$stmt->execute();
$result = $stmt->get_result();

$page_title = 'My Rides';
$asset_prefix = '../';
$base_prefix = '../';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/alert.php';
?>

<main class="page-content">
    <div class="container">
        <div class="card">
            <h2>My Rides</h2>

            <?php if ($result->num_rows === 0) { ?>
                <div class="empty-state">
                    <h3>No rides found</h3>
                    <p>Add a ride to see it here.</p>
                </div>
            <?php } else { ?>
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Pickup</th>
                            <th scope="col">Destination</th>
                            <th scope="col">Ride Type</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo e($row['pickup_location']); ?></td>
                                <td><?php echo e($row['destination']); ?></td>
                                <td><?php echo e($row['ride_type']); ?></td>
                                <td><?php echo e($row['ride_date']); ?></td>
                                <td><?php echo e($row['ride_time']); ?></td>
                                <td><span class="<?php echo e(strtolower($row['status'])); ?>"><?php echo e($row['status']); ?></span></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>

        <?php include '../includes/back_button.php'; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
