<nav class="navbar">

    <div class="logo">
        <a href="<?php echo e($base_prefix ?? ''); ?><?php echo isset($_SESSION['user_id']) ? 'dashboard.php' : 'index.php'; ?>">MyCommute</a>
    </div>

    <ul class="nav-links">

        <?php if (isset($_SESSION['user_id'])) { ?>
            <li><a href="<?php echo e($base_prefix ?? ''); ?>dashboard.php">Dashboard</a></li>
            <li>
                <form class="logout-form" action="<?php echo e($base_prefix ?? ''); ?>logout.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                    <button class="nav-link-button" type="submit">Logout</button>
                </form>
            </li>
        <?php } else { ?>
            <li><a href="<?php echo e($base_prefix ?? ''); ?>login.php">Login</a></li>
            <li><a href="<?php echo e($base_prefix ?? ''); ?>register.php">Register</a></li>
        <?php } ?>

    </ul>

</nav>
