<?php
$user = \msse661\controller\UserController::getCurrentUser();

$logger = \msse661\util\logger\LoggerManager::getLogger('html.body-header.tmpl.php');
$logger->debug('foo', ['user' => $user]);
?>

<!-- Header -->
<header id="header" class="<?php print $headerclass; ?>nav">
    <div class="container">

        <div class="navbar-header">
            <!-- Logo -->
            <div class="navbar-brand">
                <a class="logo" href="/">
                    <img src="./images/logo-alt.png" alt="logo">
                </a>
            </div>
            <!-- /Logo -->

            <!-- Mobile toggle -->
            <button class="navbar-toggle">
                <span></span>
            </button>
            <!-- /Mobile toggle -->
        </div>

        <!-- Navigation -->
        <nav id="nav transparent">
            <ul class="main-menu nav navbar-nav navbar-right">
                <li><a href="/">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Courses</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="contact.html">Contact</a></li>
                <?php if($user): $logger->debug('foo', ['user' => $user]);
                    ?>
                    <li>
                        <a href="/index.php?route=user/<?php print $user->getUuid(); ?>">
                            Welcome, <?php print $user->getFirstName(); ?>
                        </a>
                    </li>
                    <li><a href="/index.php?route=user/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/index.php?route=user/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- /Navigation -->

    </div>
</header>
