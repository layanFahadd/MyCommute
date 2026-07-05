<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? e($page_title) . ' | MyCommute' : 'MyCommute'; ?></title>

    <link rel="stylesheet" href="<?php echo e($asset_prefix ?? ''); ?>assets/css/style.css">
</head>
<body>
