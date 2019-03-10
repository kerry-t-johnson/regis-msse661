<!DOCTYPE html>
<html lang="en">
<?php print \msse661\view\ViewFactory::render('html', ['title' => 'MSSE 661'], 'head'); ?>
<body>

<?php print \msse661\view\ViewFactory::render('html', ['headerclass' => 'transparent-'], 'body-header'); ?>

<!-- Home -->
<div id="home" class="hero-area">

    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(/images/home-background.jpg)"></div>
    <!-- /Backgound Image -->

    <div class="home-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="white-text">PIANo</h1>
                    <p class="lead white-text">Public Information Archive Nano-site.</p>
                    <a class="main-button icon-button" href="index.php?route=user/login">Get Started!</a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /Home -->


<!-- preloader -->
<div id='preloader'><div class='preloader'></div></div>
<!-- /preloader -->

<?php print \msse661\view\ViewFactory::render('html', [], 'scripts'); ?>
<!-- jQuery Plugins -->
</body>
</html>
