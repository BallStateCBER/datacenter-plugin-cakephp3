<?php
    use Cake\Core\Configure;
?>

<nav>
    <?php
        $tabs = [];
        $tabs[] = [
            'Projects and<br />Publications',
            'http://projects.cberdata.org'
        ];
        $tabs[] = [
            'Economic<br />Indicators',
            'http://indicators.cberdata.org'
        ];
        $tabs[] = [
            'Weekly<br />Commentary',
            'http://commentaries.cberdata.org'
        ];
        $tabs[] = [
            'Community Readiness<br />Initiative',
            'http://cri.cberdata.org'
        ];
        $tabs[] = [
            'County<br />Profiles',
            'http://profiles.cberdata.org'
        ];
        $tabs[] = [
            'Community<br />Asset Inventory',
            'http://asset.cberdata.org'
        ];
        $tabs[] = [
            'Brownfield Grant<br />Writers\' Toolbox',
            'http://brownfield.cberdata.org'
        ];
        $tabs[] = [
            'Conexus Indiana<br />Report Card',
            'http://conexus.cberdata.org'
        ];
        $this_subsite_url = Configure::read('data_center_subsite_url');
        foreach ($tabs as $tab) {
            echo "<a href=\"$tab[1]\"";
            if ($tab[1] == $this_subsite_url) {
                echo ' class="selected"';
            }
            echo ">$tab[0]</a>";
        }
    ?>
</nav>
