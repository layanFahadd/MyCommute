<?php
require_once 'config/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Your session expired. Please try again.');
    } else {
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $plain_password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? '';

        if ($full_name === '' || strlen($full_name) > 100) {
            set_flash('error', 'Please enter a valid full name.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
            set_flash('error', 'Please enter a valid email address.');
        } elseif (strlen($plain_password) < 8) {
            set_flash('error', 'Password must be at least 8 characters.');
        } elseif ($phone === '' || strlen($phone) > 20 || !preg_match('/^[0-9+() -]+$/', $phone)) {
            set_flash('error', 'Please enter a valid phone number.');
        } elseif (!in_array($role, ['student', 'driver'], true)) {
            set_flash('error', 'Please select a valid role.');
        } else {
            $check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $check->bind_param('s', $email);
            $check->execute();

            if ($check->get_result()->num_rows > 0) {
                set_flash('error', 'An account with this email already exists.');
            } else {
                $password = password_hash($plain_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare(
                    'INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->bind_param('sssss', $full_name, $email, $password, $phone, $role);

                if ($stmt->execute()) {
                    set_flash('success', 'Account created successfully. You can now log in.');
                    header('Location: login.php');
                    exit();
                }

                set_flash('error', 'Registration failed. Please try again.');
            }
        }
    }

    header('Location: register.php');
    exit();
}

$page_title = 'Register';
$back_url = 'index.php';
$back_label = 'Back to Home';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/alert.php';
?>

<main class="page-content">
    <div class="register-container">
        <h2>Create Account</h2>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">

            <label for="full_name">Full Name</label>
            <input id="full_name" type="text" name="full_name" placeholder="Full Name" maxlength="100" autocomplete="name" required>

            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="Email" maxlength="100" autocomplete="email" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Password" minlength="8" autocomplete="new-password" required>

            <label for="phone">Phone Number</label>
            <input id="phone" type="tel" name="phone" placeholder="Phone Number" maxlength="20" autocomplete="tel" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="driver">Driver</option>
            </select>

            <button type="submit" name="register">Register</button>
        </form>

        <?php include 'includes/back_button.php'; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
