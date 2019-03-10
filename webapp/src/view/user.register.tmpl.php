<!DOCTYPE html>
<html lang="en">
<?php print \msse661\view\ViewFactory::render('html', ['title' => 'MSSE 661'], 'head'); ?>
<body>
<?php print \msse661\view\ViewFactory::render('html', ['headerclass' => 'transparent-'], 'body-header'); ?>

<div class="limiter">
    <div class="container-login100 bg-image bg-parallax overlay"
         style="background-image:url(/images/home-background.jpg)">
        <div class="wrap-login100 p-t-85 p-b-20">
            <form id="user-register-form" class="login100-form validate-form" action="/index.php?route=user/register" method="post" enctype="multipart/form-data">
                <div class="wrap-input100 m-b-50" >
                    <input class="input100" type="text" name="first_name" required placeholder="first name"/>
                </div>

                <div class="wrap-input100 m-b-50">
                    <input class="input100" type="text" name="last_name" required placeholder="last name"/>
                </div>

                <div class="wrap-input100 m-b-50">
                    <input class="input100" type="text" name="email" required placeholder="email"/>
                </div>

                <div class="wrap-input100 m-b-50">
                    <input class="input100" type="password" name="password" required placeholder="password"/>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php print \msse661\view\ViewFactory::render('html', [], 'scripts'); ?>
</body>
</html>
