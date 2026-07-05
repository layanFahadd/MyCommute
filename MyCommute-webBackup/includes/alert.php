<?php
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<?php if ($flash) { ?>
    <div class="alert <?php echo e($flash['type']); ?>" role="alert">
        <?php echo e($flash['message']); ?>
    </div>
<?php } ?>
