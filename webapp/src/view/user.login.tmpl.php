<!DOCTYPE html>
<html lang="en">
<?php print \msse661\view\ViewFactory::render('html', ['title' => 'MSSE 661'], 'head'); ?>
<body>
<?php print \msse661\view\ViewFactory::render('html', ['headerclass' => 'transparent-'], 'body-header'); ?>

<div class="limiter">
    <div class="container-login100 bg-image bg-parallax overlay"
         style="background-image:url(/images/home-background.jpg)">
        <div class="wrap-login100 p-t-85 p-b-20">
            <?php if($error): ?>
                <div class="red-text">
                    <h3 class="red-text">Invalid login</h3>
                </div>
            <?php endif; ?>
            <form id="user-login-form" class="login100-form validate-form" action="user.php?route=user/login" method="post" enctype="multipart/form-data">
                <div class="wrap-input100 m-t-85 m-b-35">
                    <input class="input100" type="text" name="email" placeholder="email" required/>
                </div>

                <div class="wrap-input100 m-b-50">
                    <input class="input100" type="password" name="password" placeholder="password" required/>
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