<table>
    <tbody>
    <?php foreach ($comments as $c): ?>
        <tr>
            <td>
                <?php print \msse661\view\ViewFactory::render('comment', ['comment' => $c], 'teaser'); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>