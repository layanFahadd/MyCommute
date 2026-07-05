<?php
require_once '../config/db.php';
require_login('../login.php');
require_role('student');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Invalid request.');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Your session expired. Please try again.');
    header('Location: available_rides.php');
    exit();
}

$ride_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$student_id = (int) $_SESSION['user_id'];

if (!$ride_id) {
    set_flash('error', 'Invalid ride selection.');
    header('Location: available_rides.php');
    exit();
}

$stmt = $conn->prepare(
    "UPDATE rides
     SET user_id = ?, status = 'Booked'
     WHERE id = ?
       AND status = 'Available'
       AND user_id IS NULL
       AND TIMESTAMP(ride_date, ride_time) > NOW()"
);
$stmt->bind_param('ii', $student_id, $ride_id);
$stmt->execute();

if ($stmt->affected_rows === 1) {
    set_flash('success', 'Ride booked successfully.');
    header('Location: my_bookings.php');
} else {
    set_flash('error', 'This ride is no longer available.');
    header('Location: available_rides.php');
}
exit();
