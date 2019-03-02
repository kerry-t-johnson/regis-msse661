<div class="portfolio-one">
    <?php
    /** @var \msse661\Content $c */
    foreach($content as $c): ?>
    <div class="col-sm-4 col-xs-12 portfolio-item transition metal " data-category="transition">
        <div class="single_portfolio_img">
            <a class="portfolio-img" href="images/portfolio/1.jpg">
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