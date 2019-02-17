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
        <tr>
            <td data-title="Content Title">
                <?php print \msse661\view\ViewFactory::render('content', ['content' => $c], 'teaser'); ?>
            </td>
            <td data-title="User">
                <?php print \msse661\view\ViewFactory::render('user', ['user' => $users[$c->getUuid()] ?? null], 'teaser'); ?>
            </td>
            <td data-title="Comments">
                <?php print \msse661\view\ViewFactory::render('comment', ['comments' => $comments[$c->getUuid()] ?? []], 'teaser-list'); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
