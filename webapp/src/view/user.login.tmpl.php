<?php if(strpos(\msse661\controller\SiteController::currentUri(), 'user/register') === false): ?>
    <div class="user-management">
        <?php if(\msse661\controller\UserController::getCurrentUser()): ?>
            <form name="logoutform" action="/user/logout" method="post" enctype="multipart/form-data">
                <input id="submit" type="submit" value="Logout" name="logout" />
            </form>
        <?php else: ?>
            <form name="loginform" action="/user/login" method="post" enctype="multipart/form-data">
                <label>Email address</label>
                <input type="text" name="email" placeholder="email address">
                <label>Password</label>
                <input type="password" name="password" placeholder="password">
                <input id="submit" type="submit" value="Login" name="login" />
                <a href="/user/register">Register...</a>
            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>
