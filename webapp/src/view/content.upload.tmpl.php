<?php
$tagDao     = new \msse661\dao\mysql\TagMysqlDao();
$all_tags   = $tagDao->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<?php print \msse661\view\ViewFactory::render('html', ['title' => 'MSSE 661'], 'head'); ?>
<body>

<?php print \msse661\view\ViewFactory::render('html', [], 'body-header'); ?>

<div class="section">
    <div class="section-header text-center">
        <h2>Publish content</h2>
    </div>
    <div class="container">
        <form action="/content/upload" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" name="userUuid" value="<?php print $user->getUuid(); ?>"/>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="title" type="text">
                            <label for="title">Title</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="description" class="materialize-textarea"></textarea>
                            <label for="description">Description</label>
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "file-field input-field">
                            <div class="btn blue-grey darken-2">
                                <span>Browse</span>
                                <input type = "file" />
                            </div>

                            <div class = "file-path-wrapper">
                                <input class = "file-path validate" type = "text"
                                       placeholder = "Upload file" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h3>Interests</h3>
                    <?php foreach($all_tags as $tag): ?>
                        <p>
                            <label>
                                <input
                                        type="checkbox"
                                        class="filled-in checkbox-blue-gray"
                                        value="<?php print $tag->getUuid(); ?>"
                                />
                                <span><?php print $tag->getName(); ?></span>
                            </label>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light blue-grey darken-2 center-align" type="submit" name="action">Submit
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </form>
    </div>
</div>

<?php print \msse661\view\ViewFactory::render('html', [], 'body-footer'); ?>

<?php print \msse661\view\ViewFactory::render('html', [], 'scripts'); ?>
<!-- jQuery Plugins -->
</body>
</html>



