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
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $conn->prepare('SELECT id, full_name, password, role FROM users WHERE email = ? LIMIT 1');

        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                set_flash('success', 'Login successful.');
                header('Location: dashboard.php');
                exit();
            }
        }

        set_flash('error', 'Invalid email or password.');
    }

    header('Location: login.php');
    exit();
}

$page_title = 'Login';
$back_url = 'index.php';
$back_label = 'Back to Home';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/alert.php';
?>

<main class="page-content">
    <div class="register-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">

            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="Email" autocomplete="email" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Password" autocomplete="current-password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <?php include 'includes/back_button.php'; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
