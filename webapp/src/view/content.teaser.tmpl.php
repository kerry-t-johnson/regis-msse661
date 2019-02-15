<?php if(!empty($content)): ?>
    <a class="content-teaser teaser" href="content/<?php print $content->getUuid(); ?>">
        <?php print $content->getTitle(); ?>
    </a>
<?php else: ?>
    Content not found
<?php endif; ?>

