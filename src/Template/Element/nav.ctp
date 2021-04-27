<?php
    use Cake\Core\Configure;
    $tabs = [
        ['Projects and Publications', 'https://projects.cberdata.org'],
        ['Economic Indicators', 'https://indicators.cberdata.org'],
        ['Weekly Commentary', 'https://commentaries.cberdata.org'],
        ['Community Asset Inventory', 'https://cair.cberdata.org'],
        ['Manufacturing Scorecard', 'https://mfgscorecard.cberdata.org/']
    ];
    $this_subsite_url = Configure::read('data_center_subsite_url');
?>

<nav>
    <?php foreach ($tabs as $tab): ?>
        <?php if ($tab[1] == $this_subsite_url): ?>
            <a href="<?= $tab[1] ?>" class="selected">
                <?= $tab[0] ?>
            </a>
        <?php else: ?>
            <a href="<?= $tab[1] ?>">
                <?= $tab[0] ?>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
