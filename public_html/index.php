<?php

require_once 'multidimensional-associative-array.php';

global $questions;

$q1 = $questions['question-id-1'];
$q1_answers = get_answers('question-id-1');
$c1 = get_content('content-id-1');

?>

<html>
<head>
    <title><?php print $c1['title']; ?></title>
    <link rel='stylesheet' href='css/table.css'/>
</head>
<body>
<h1><?php print $c1['title']; ?></h1>
<?php print $c1['markup']; ?>
<div class="container">
    <table class="responsive-table">
        <caption><?php print $q1['text']; ?></caption>
        <thead>
        <tr>
            <th scope="col">Answer ID</th>
            <th scope="col">Answer</th>
            <th scope="col">User</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($q1_answers as $answer_id => $answer): ?>
            <?php $user = get_user($answer['user_id']); ?>
            <tr>
                <td data-title="Answer ID"><?php print $answer_id; ?></td>
                <td data-title="Answer"><?php print $answer['text']; ?></td>
                <td data-title="User"><?php print $user !== false ? $user['last_name'] : ''; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>