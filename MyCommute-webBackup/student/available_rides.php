<?php
require_once '../config/db.php';
require_login('../login.php');
require_role('student');

$sql = "SELECT id, pickup_location, destination, ride_type, ride_date, ride_time
        FROM rides
        WHERE status = 'Available'
          AND user_id IS NULL
          AND TIMESTAMP(ride_date, ride_time) > NOW()
        ORDER BY ride_date, ride_time";
$result = $conn->query($sql);

$page_title = 'Available Rides';
$asset_prefix = '../';
$base_prefix = '../';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/alert.php';
?>

<main class="page-content">
    <div class="container">
        <div class="card">
            <h2>Available Rides</h2>

            <?php if ($result->num_rows === 0) { ?>
                <div class="empty-state">
                    <h3>No rides available</h3>
                    <p>Please check again later.</p>
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
                            <th scope="col">Action</th>
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
                                <td>
                                    <form class="inline-form" action="book_ride.php" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                                        <input type="hidden" name="id" value="<?php echo e($row['id']); ?>">
                                        <button class="btn-primary" type="submit">Book</button>
                                    </form>
                                </td>
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
