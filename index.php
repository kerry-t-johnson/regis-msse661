<?php require_once 'bootstrap.php';

\msse661\controller\UserController::startUserSession();
?>
<html>
<head>
    <title>MSSE 661</title>
    <link rel='stylesheet' href='/css/table.css'/>
    <link rel='stylesheet' href='/css/debug.css'/>
    <link rel='stylesheet' href='/css/user.css'/>
</head>
<body>
<h1><a href="/">MSSE 661</a></h1>
<div class="user-management">
    <?php print \msse661\view\ViewFactory::render('user', [], 'login'); ?>
</div>
<div class="container">
    <?php print \msse661\controller\SiteController::route($_SERVER) ?? ''; ?>
    <div>
        <a href="/content/uploadForm">Submit new content...</a>
    </div>
    <div>
        <a href="./create_test_data.php">Create test data...</a>
    </div>
</div>
</body>
</html>