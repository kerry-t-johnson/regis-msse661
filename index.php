<?php require_once 'bootstrap.php';

\msse661\controller\UserController::startUserSession();
$routed_content = \msse661\controller\SiteController::route('/content') ?? '';
?>
<html>
<head>
    <title>MSSE 661</title>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel='stylesheet' href='/css/main.css'/>
    <link rel='stylesheet' href='/css/table.css'/>
    <link rel='stylesheet' href='/css/debug.css'/>
    <link rel='stylesheet' href='/css/content.css'/>
    <link rel='stylesheet' href='/css/user.css'/>
</head>
<body>
<h1><a href="/">MSSE 661</a></h1>
<div class="user-management">
    <?php print \msse661\view\ViewFactory::render('user', [], 'login'); ?>
</div>
<div class="container">
    <?php print $routed_content; ?>
    <div>
        <a href="/content/uploadForm">Submit new content...</a>
    </div>
    <div>
        <a href="/content/createTestData">Create test data...</a>
    </div>
</div>
</body>
</html>