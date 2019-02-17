<?php if(!empty($comment)): ?>
    <a class="comment-teaser teaser" href="comment/<?php print $comment->getUuid(); ?>">
        <?php print $comment->getTitle(); ?>
    </a>
<?php else: ?>
    Comment not found
<?php endif; ?>

