<?php
require_once 'bootstrap.php';

$userDao    = new \msse661\dao\mysql\UserMysqlDao();
$users      = $userDao->getAll();

?>
<html>
<head>
    <title>MSSE 661 - File upload</title>
    <link rel='stylesheet' href='css/misc.css'/>
</head>
<body>
<div class="form-background">
    <form action="file_upload_action" method="post" enctype="multipart/form-data">
        <div class="form-box">
            <div class="input select-input">
                <label for="user">Select user</label>
                <select name="user">
                    <?php foreach($users as $user): ?>
                        <option value="<?php print $user->getUuid(); ?>">
                            <?php print $user->getFullName(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input file-upload-input">
                <label for=""fileToUpload">Select file to upload:</label>
                <input type="file" name="fileToUpload" id="fileToUpload"/>
            </div>
            <div class="input title-input">
                <label for="title">Title</label>
                <input type="text" name="title" />
            </div>
            <div class="input description-input">
                <label for="description">Description</label>
                <textarea name="description" ></textarea>
            </div>
            <div>
                <input id="submit" type="submit" value="Upload" name="submit" style="margin-top: 50px;"/>
            </div>
        </div>
    </form>
</div>
</body>
</html>