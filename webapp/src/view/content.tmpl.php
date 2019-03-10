<?php
?>

<!DOCTYPE html>
<html lang="en">
<?php print \msse661\view\ViewFactory::render('html', ['title' => 'MSSE 661'], 'head'); ?>
<body>

<?php print \msse661\view\ViewFactory::render('html', [], 'body-header'); ?>


<div id="content" class="section">
    <div class="container">
        <h1><?php print $content->getTitle(); ?></h1>
        <div class="row">
            <div class="col-md-8">
                <div id="content-wrapper" data-content-uuid="<?php print $content->getUuid(); ?>"></div>
            </div>
            <div class="col-md-4">
                <div id="comments-wrapper" data-content-uuid="<?php print $content->getUuid(); ?>"></div>
            </div>
        </div>
    </div>
</div>
<?php print \msse661\view\ViewFactory::render('html', [], 'body-footer'); ?>

<?php print \msse661\view\ViewFactory::render('html', [], 'scripts'); ?>
<!-- jQuery Plugins -->
</body>
</html>
