<div class="filters-button-group">
    <button class="button is-checked" data-filter="*">All</button>
    <?php foreach($tag as $t): ?>
        <button class="button " data-filter=".<?php print $t->getUuid(); ?>">
            <?php print $t->getName(); ?>
        </button>
    <?php endforeach; ?>
</div>
