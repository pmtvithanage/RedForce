<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<h1>Users</h1>

<?php foreach($data['users'] as $user): ?>
    <p><?php echo $user->full_name; ?> - <?php echo $user->email; ?> - <?php echo $user->role; ?></p>
    <?php endforeach; ?> 




<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>