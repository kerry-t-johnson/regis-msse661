<!DOCTYPE html>
<html lang="en">
<?php print \msse661\view\ViewFactory::render('html', ['title' => 'MSSE 661'], 'head'); ?>
<body>
<?php print \msse661\view\ViewFactory::render('html', ['headerclass' => 'transparent-'], 'body-header'); ?>

<div class="limiter">
    <div class="container-login100 bg-image bg-parallax overlay"
         style="background-image:url(/images/home-background.jpg)">
        <div class="wrap-login100 p-t-85 p-b-20">
            <form class="login100-form validate-form" action="user.php?route=user/login" method="post" enctype="multipart/form-data">
                <div class="wrap-input100 validate-input m-t-85 m-b-35" data-validate="Enter email address">
                    <input class="input100" type="text" name="email">
                    <span class="focus-input100" data-placeholder="email"></span>
                </div>

                <div class="wrap-input100 validate-input m-b-50" data-validate="Enter password">
                    <input class="input100" type="password" name="password">
                    <span class="focus-input100" data-placeholder="password"></span>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">Login</button>
                </div>

                <ul class="login-more p-t-190">
                    <li>
                        <span class="txt1">Don’t have an account?</span>
                        <a href="/index.php?route=user/register" class="txt2">Sign up</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>

<?php print \msse661\view\ViewFactory::render('html', [], 'scripts'); ?>
</body>
</html>