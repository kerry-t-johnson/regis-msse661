<?php
require_once 'bootstrap.php';

$contentDao = new \msse661\dao\mysql\ContentMysqlDao();
$userDao    = new \msse661\dao\mysql\UserMysqlDao();
$commentDao = new \msse661\dao\mysql\CommentMysqlDao();

$content    = $contentDao->getAll();
?>
<html>
<head>
    <title>MSSE 661</title>
    <link rel='stylesheet' href='css/table.css'/>
</head>
<body>
<h1>MSSE 661</h1>
<div class="container">
    <?php if($content): ?>
        <table class="responsive-table">
            <caption>Test Content</caption>
            <thead>
            <tr>
                <th scope="col">Content Title</th>
                <th scope="col">User</th>
                <th scope="col">Comments</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($content as $c): ?>
                <?php
                    $user       = $userDao->getByUuid($c->getUserUuid());
                    $comments   = $commentDao->getByContent($c->getUuid());
                ?>
                <tr>
                    <td data-title="Content Title">
                         <?php print $c->getTitle(); ?>
                    </td>
                    <td data-title="User">
                        <a href="mailto:<?php print $user->getEmail(); ?>?Subject=Hello" target="_top"><?php print $user->getFullName(); ?></td>
                    <td data-title="Comments">
                        <table>
                            <tbody>
                            <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td>
                                        <?php print $comment->getTitle(); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <a href="./create_test_data">Create test data...</a>
    <?php endif; ?>
</div>
</body>
</html>