<div class="content full">
    <div class="content-title">
        <?php print $content->getTitle(); ?>
    </div>
    <div class="content-description">
        <?php print $content->getDescription(); ?>
    </div>
    <div class="content-path">
        <a href="<?php print $content->getPath(); ?>"><?php print $content->getPath(); ?></a>
    </div>
    <div class="content-user">
        <?php print \msse661\view\ViewFactory::render('user', ['user' => $user], 'teaser'); ?>
    </div>
    <div class="content-hash">
        <?php print $content->getHash(); ?>
    </div>
    <?php if(filter_var($content->getPath(), FILTER_VALIDATE_URL)): ?>
        <?php if(strpos($content->getPath(), 'api')): ?>
            <div class="content-content">
                <?php print file_get_contents($content->getPath()); ?>
            </div>
        <?php else: ?>
            <div class="content-external-link">
                <a href="/<?php print $content->getPath(); ?>" target="_blank">
                    <?php print $content->getTitle(); ?>
                </a>
            </div>
        <?php endif; ?>
    <?php elseif(in_array($content->getImageType(), [IMAGETYPE_PNG, IMAGETYPE_JPEG])): ?>
        <div class="content-content">
            <img src="/<?php print $content->getPath(); ?>">
        </div>
    <?php else: ?>
        <div class="content-file-link">
            <a href="/<?php print $content->getPath(); ?>" type="<?php print $content->getMimeType(); ?>" target="_blank">
                <?php print $content->getTitle(); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
