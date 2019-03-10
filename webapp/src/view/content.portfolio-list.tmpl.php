<div class="courses-wrapper">
    <div class="row">
    <?php
        /** @var \msse661\Content $c */
        foreach($content as $c) {
            print \msse661\view\ViewFactory::render('content', ['content' => $c], 'portfolio');
        }
        ?>
    </div>
</div>