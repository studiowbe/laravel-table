<?php
/**
 * @var  $table \Studiow\Laravel\Table\Table
 *
 */
?>
<?php if ($table->needsForm()): ?>
<form method="get">
    <?php endif;?>
    <?php if ($table->hasActions() || $table->hasFilters()): ?>
    <header>
        <?php if ($table->hasActions()): ?>
        <div class="actions">
            <?= $table->selectAction(); ?>
            <button type="submit" name="btn" value="exec"><?= __('Apply'); ?></button>
        </div>
        <?php endif; ?>
        <?php if ($table->hasFilters()): ?>
        <div class="filters">
            <?php foreach($table->getFilters() as $filter): ?>
            <span class="filter"><?= $filter; ?></span>
            <?php endforeach; ?>
            <button type="submit"><?= __('Filter'); ?></button>
        </div>

        <?php endif; ?>
    </header>
    <?php endif; ?>
    @include('table::inner', ['table'=>$table])
    <?php if ($table->hasPages()): ?>
    <footer>
        <?= $table->links(); ?>
    </footer>
    <?php endif; ?>
    <?php if ($table->needsForm()): ?>

</form>
<?php endif;?>
