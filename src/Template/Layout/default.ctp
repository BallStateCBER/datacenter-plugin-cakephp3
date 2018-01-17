<?php
    use Cake\Core\Configure;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <link rel="dns-prefetch" href="https://ajax.googleapis.com" />
    <title>
        <?php
            $title = Configure::read('data_center_subsite_title');
            if (isset($titleForLayout) && $titleForLayout) {
                $title = $titleForLayout . ' - '.$title;
            }
            echo $title;
        ?>
    </title>
    <meta name="title" content="<?= $title; ?>" />
    <meta name="author" content="Center for Business and Economic Research, Ball State University" />
    <meta name="language" content="en" />
    <meta name="viewport" content="width=device-width" />
    <?= $this->fetch('meta') ?>
    <?php /*
        Optional tags for <head>

        Facebook Open Graph Data
        <meta property="og:title" content="" />
        <meta property="og:description" content="" />
        <meta property="og:image" content="" />

        <link rel="author" href="humans.txt" />

        More useful tag suggestions at http://html5boilerplate.com/docs/head-Tips/
        Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons
    */ ?>
    <link rel="apple-touch-icon" sizes="180x180" href="/data_center/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/data_center/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/data_center/favicon-16x16.png" />
    <link rel="manifest" href="/data_center/manifest.json" />
    <link rel="mask-icon" href="/data_center/safari-pinned-tab.svg" color="#5bbad5" />
    <meta name="theme-color" content="#cc0022" />
    <link href="https://fonts.googleapis.com/css?family=Asap:400,400italic,700" rel="stylesheet" type="text/css" />
    <?= $this->Html->css('style') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('scriptTop') ?>

    <!--[if lt IE 9]>
    <script src="/data_center/js/html5shiv-printshiv.js"></script>
    <![endif]-->

    <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/data_center/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="/data_center/js/flash.js"></script>

    <?= $this->Html->script('/data_center/js/datacenter.js') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="default-layout">

    <?= $this->fetch('top-html') ?>

    <header id="header_top">
        <div class="max_width">
            <h1>
                <a href="http://bsu.edu/cber">
                    Center for Business and Economic Research
                </a>
                -
                <a href="http://bsu.edu">
                    Ball State University
                </a>
            </h1>
            <br class="clear" />
            <a href="http://cberdata.org/" id="data_center_nameplate">
                CBER Data Center
            </a>
            <?= $this->element('DataCenter.nav') ?>
            <br class="clear" />
        </div>
    </header>

    <?php if ($this->fetch('subsite_title')): ?>
        <?= $this->fetch('subsite_title') ?>
    <?php else: ?>
        <h1 id="subsite_title" class="max_width_padded">
            <?= Configure::read('data_center_subsite_title') ?>
        </h1>
    <?php endif; ?>

    <div id="content_wrapper" class="max_width">
        <?php if ($this->fetch('sidebar')): ?>
            <div id="two_col_wrapper">
                <?php
                    /*
                     * These two col_stretcher divs ensure that both the sidebar and content area have the appearance
                     * of having the same height.
                     */
                ?>
                <div id="menu_col_stretcher" class="col_stretcher"></div>
                <div id="content_col_stretcher" class="col_stretcher"></div>
                <div id="menu_column" class="col">
                    <?= $this->fetch('sidebar') ?>
                </div>
                <main id="content_column" class="col">
                    <?= $this->fetch('content') ?>
                    <br class="clear" />
                </main>
            </div>
        <?php else: ?>
            <main>
                <?= $this->fetch('content') ?>
            </main>
            <br class="clear" />
        <?php endif; ?>
    </div>

    <?= $this->element('DataCenter.footer') ?>

    <noscript id="noscript" class="alert alert-warning">
        <div>
            JavaScript is currently disabled in your browser. For full functionality of this website, JavaScript must
            be enabled. If you need assistance,
            <a href="http://www.enable-javascript.com/" target="_blank">Enable-JavaScript.com</a> provides instructions.
        </div>
    </noscript>

    <?= $this->element('DataCenter.analytics') ?>
    <?= $this->fetch('scriptBottom') ?>
    <script>
        $(document).ready(function () {
            <?= $this->fetch('buffered') ?>
        });
    </script>
</body>
</html>
