<div class="portfolio-one">
    <?php
        /** @var \msse661\Content $c */
        foreach($content as $c):
            $tagIds = [];
            foreach($c->getTags() as $t) {
                $tagIds[] = $t->getUuid();
            }
        ?>
            <div class="col-sm-4 col-xs-12 portfolio-item <?php print implode(' ', $tagIds); ?>" data-category="transition">
                <div class="single_portfolio_img">
                    <a class="portfolio-img content-full-link" href="#" data-content="<?php print $c->getUuid(); ?>">
                        <img src="images/portfolio/1.jpg" alt=""/>
                    </a>
                    <h2><?php print $c->getTitle(); ?></h2>
                    <span><?php print $c->getUser()->getFullName(); ?></span>
                    <div class="tag-list">
                        <?php foreach($c->getTags() as $t): ?>
                            <div class="tag">
                                <?php print $t->getName(); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
    <?php endforeach; ?>
</div>