<div class="form-background">
    <form action="/content/upload" method="post" enctype="multipart/form-data">
        <div class="form-box">
            <div class="input select-input">
                <label for="user">User</label>
                <input type="text" name="user" disabled="true" value="<?php print $user->getFullName(); ?>"/>
                <input type="hidden" name="userUuid" value="<?php print $user->getUuid(); ?>"/>
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