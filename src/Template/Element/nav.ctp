<?php
    use Cake\Core\Configure;
    $tabs = [
        ['Projects and<br />Publications', 'http://projects.cberdata.org'],
        ['Economic<br />Indicators', 'http://indicators.cberdata.org'],
        ['Weekly<br />Commentary', 'http://commentaries.cberdata.org'],
        ['Community Readiness<br />Initiative', 'http://cri.cberdata.org'],
        ['County<br />Profiles', 'http://profiles.cberdata.org'],
        ['Community<br />Asset Inventory', 'http://asset.cberdata.org'],
        ['Brownfield Grant<br />Writers\' Toolbox', 'http://brownfield.cberdata.org'],
        ['Conexus Indiana<br />Report Card', 'http://conexus.cberdata.org']
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
