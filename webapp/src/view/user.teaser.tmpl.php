<?php if(!empty($user)): ?>
    <a class="user-link" href="mailto:<?php print $user->getEmail(); ?>?Subject=Hello" target="_top">
        <?php print $user->getFullName(); ?>
    </a>
<?php else: ?>
    User not found
<?php endif; ?>

