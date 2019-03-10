<?php
    $tagIds = [];
    foreach($content->getTags() as $t) {
        $tagIds[] = $t->getUuid();
    }
?>

<div class="col-md-3 col-sm-6 col-xs-6 <?php print implode(' ', $tagIds); ?>" data-category="transition">
    <div class="course">
        <a class="course-img" href="/content.php?id=<? $content->getUuid(); ?>" data-content="<?php print $content->getUuid(); ?>">
            <img src="images/course01.jpg" alt=""/>
        </a>
        <a class="course-title" href="/content.php?id=<? $content->getUuid(); ?>"><?php print $content->getTitle(); ?></a>
        <?php print \msse661\view\ViewFactory::render('user', ['user' => $content->getUser() ], 'teaser'); ?>
        <div class="course-details">
        </div>
        <div class="tag-list">
            <?php foreach($content->getTags() as $t): ?>
            <div class="course-tag">
                <span class="course-tag-label">
                    <?php print $t->getName(); ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
