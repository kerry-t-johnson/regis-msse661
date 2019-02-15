<?php require_once 'bootstrap.php'; ?>
<html>
<head>
    <title>MSSE 661</title>
    <link rel='stylesheet' href='css/table.css'/>
    <link rel='stylesheet' href='css/debug.css'/>
</head>
<body>
<h1>MSSE 661</h1>
<div class="container">
    <?php print \msse661\controller\SiteController::route($_SERVER); ?>
    <div>
        <a href="./file_upload.php">Submit new content...</a>
    </div>
<!--    --><?php //if($content): ?>
<!--    --><?php //else: ?>
<!--        <a href="./create_test_data.php">Create test data...</a>-->
<!--    --><?php //endif; ?>
</div>
<!--<div class="debug">-->
<!--    --><?php //print_r($request); ?>
<!--</div>-->
</body>
</html>