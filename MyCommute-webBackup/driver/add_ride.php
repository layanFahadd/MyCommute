<?php
require_once '../config/db.php';
require_login('../login.php');
require_role('driver');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Your session expired. Please try again.');
    } else {
        $driver_id = (int) $_SESSION['user_id'];
        $pickup = trim($_POST['pickup_location'] ?? '');
        $destination = trim($_POST['destination'] ?? '');
        $ride_type = $_POST['ride_type'] ?? '';
        $ride_date = $_POST['ride_date'] ?? '';
        $ride_time = $_POST['ride_time'] ?? '';
        $ride_datetime = DateTime::createFromFormat('Y-m-d H:i', "{$ride_date} {$ride_time}");
        $date_errors = DateTime::getLastErrors();
        $valid_datetime = $ride_datetime
            && ($date_errors === false || ($date_errors['warning_count'] === 0 && $date_errors['error_count'] === 0))
            && $ride_datetime > new DateTime();

        if ($pickup === '' || strlen($pickup) > 255 || $destination === '' || strlen($destination) > 255) {
            set_flash('error', 'Please enter valid pickup and destination locations.');
        } elseif (!in_array($ride_type, ['One Way', 'Round Trip'], true)) {
            set_flash('error', 'Please select a valid ride type.');
        } elseif (!$valid_datetime) {
            set_flash('error', 'Ride date and time must be valid and in the future.');
        } else {
            $status = 'Available';
            $stmt = $conn->prepare(
                'INSERT INTO rides
                (user_id, driver_id, pickup_location, destination, ride_type, ride_date, ride_time, status)
                VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param(
                'issssss',
                $driver_id,
                $pickup,
                $destination,
                $ride_type,
                $ride_date,
                $ride_time,
                $status
            );

            if ($stmt->execute()) {
                set_flash('success', 'Ride added successfully.');
            } else {
                set_flash('error', 'Unable to add ride.');
            }
        }
    }

    header('Location: add_ride.php');
    exit();
}

$page_title = 'Add Ride';
$asset_prefix = '../';
$base_prefix = '../';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/alert.php';
?>

<main class="page-content">
    <div class="container">
        <div class="card">
            <h2>Add New Ride</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">

                <label for="pickup_location">Pickup Location</label>
                <input id="pickup_location" type="text" name="pickup_location" maxlength="255" required>

                <label for="destination">Destination</label>
                <input id="destination" type="text" name="destination" maxlength="255" required>

                <label for="ride_type">Ride Type</label>
                <select id="ride_type" name="ride_type" required>
                    <option value="">Select Ride Type</option>
                    <option value="One Way">One Way</option>
                    <option value="Round Trip">Round Trip</option>
                </select>

                <label for="ride_date">Date</label>
                <input id="ride_date" type="date" name="ride_date" min="<?php echo e(date('Y-m-d')); ?>" required>

                <label for="ride_time">Time</label>
                <input id="ride_time" type="time" name="ride_time" required>

                <button type="submit" name="addRide">Add Ride</button>
            </form>
        </div>

        <?php include '../includes/back_button.php'; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
