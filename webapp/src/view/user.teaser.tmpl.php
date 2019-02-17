<?php if(!empty($user)): ?>
    <a class="user-link" href="/user/<?php print $user->getUuid(); ?>">
        <?php print $user->getFullName(); ?>
    </a>
<?php else: ?>
    User not found
<?php endif; ?>

