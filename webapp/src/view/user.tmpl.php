<?php
$tagDao = new \msse661\dao\mysql\TagMysqlDao();
$all_tags = $tagDao->fetch();
$user_tags = $user->getTags(true);
?>

<!DOCTYPE html>
<html lang="en">
<?php print \msse661\view\ViewFactory::render('html', ['title' => 'MSSE 661'], 'head'); ?>
<body>

<?php print \msse661\view\ViewFactory::render('html', [], 'body-header'); ?>

<div id="user" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 user full">
                <div class="section-header">
                    <h2><?php print $user->getFullName(); ?></h2>
                </div>
                <div class="user-email">
                    <?php print $user->getEmail(); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="user-tags col-md-4">
                <h3>My Interests</h3>
                <form id="user-tags">
                    <?php foreach ($all_tags as $tag): ?>
                        <p>
                            <label>
                                <input
                                        type="checkbox"
                                        class="filled-in checkbox-blue-gray"
                                        value="<?php print $tag->getUuid(); ?>"
                                    <?php if (array_key_exists($tag->getUuid(), $user_tags)) {
                                        print "checked";
                                    } ?>
                                />
                                <span><?php print $tag->getName(); ?></span>
                            </label>
                        </p>
                    <?php endforeach; ?>
                </form>
            </div>
            <div class="col-md-8">
                <div id="user-tagged-content" class="row"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="user-content-wrapper" class="section-header">
                    <h3>My Published Content</h3>
                    <div id="user-content-list"></div>
                </div>
                <a class="btn modal-trigger blue-grey darken-2" href="#upload-form-wrapper">Publish</a>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="upload-form-wrapper" class="modal bottom-sheet">
        <div class="section-header text-center">
            <h2>Publish content</h2>
        </div>
        <div class="container">
            <form id="upload-form" action="/index.php?route=content/upload" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="content-upload-user-uuid" value="<?php print $user->getUuid(); ?>"/>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="title" type="text" name="title">
                                <label for="title">Title</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="description" class="materialize-textarea" name="description"></textarea>
                                <label for="description">Description</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="file-field input-field">
                                <div class="btn blue-grey darken-2">
                                    <span>Browse</span>
                                    <input type="file" name="file-to-upload"
                                           accept="application/pdf, text/html, text/plain"/>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Upload file"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h3>Interests</h3>
                        <?php foreach ($all_tags as $tag): ?>
                            <p>
                                <label>
                                    <input
                                            type="checkbox"
                                            class="filled-in checkbox-blue-gray"
                                            name="content-tags[]"
                                            value="<?php print $tag->getUuid(); ?>"
                                        <?php if (array_key_exists($tag->getUuid(), $user_tags)) {
                                            print "checked";
                                        } ?> />
                                    <span><?php print $tag->getName(); ?></span>
                                </label>
                            </p>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-md-2">
                        <button id="upload-form-submit"
                                class="btn waves-effect waves-light blue-grey darken-2 center-align" type="submit"
                                name="action">Submit
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="delete-form-wrapper" class="modal bottom-sheet">
        <div class="section-header text-center">
            <h2>Delete </h2>
        </div>
        <div class="container">
            <form id="delete-form">
                <div class="row">
                    <div class="text-center">
                        <button id="delete-form-submit"
                                class="btn waves-effect waves-light blue-grey darken-2 center-align" type="submit"
                                name="action">Delete
                            <i class="material-icons right">delete</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<?php print \msse661\view\ViewFactory::render('html', [], 'body-footer'); ?>

<?php print \msse661\view\ViewFactory::render('html', [], 'scripts'); ?>
<!-- jQuery Plugins -->
</body>
</html>
