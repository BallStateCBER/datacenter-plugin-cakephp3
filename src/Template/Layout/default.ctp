<?php
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <?= $this->Html->charset() ?>
    <link rel="dns-prefetch" href="//ajax.googleapis.com" />
    <title>
        <?php
        $title = Configure::read('data_center_subsite_title');
        if (isset($titleForLayout) && $titleForLayout) {
            $title = $titleForLayout.' - '.$title;
        }
        echo $title;
        ?>
    </title>
    <meta name="title" content="<?= $title; ?>" />
    <meta name="description" content="" />
    <meta name="author" content="Center for Business and Economic Research, Ball State University" />
    <meta name="language" content="en" />
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="imagetoolbar" content="false" />
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
    <link rel="shortcut icon" href="/data_center/img/favicon.ico" />
    <link href="//fonts.googleapis.com/css?family=Asap:400,400italic,700" rel="stylesheet" type="text/css">
    <?= $this->Html->css('style') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('scriptTop') ?>

    <!--[if lt IE 9]>
    <script src="/data_center/js/html5shiv-printshiv.js"></script>
    <![endif]-->
</head>
<body class="default-layout">
<?php /*
            Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
            chromium.org/developers/how-tos/chrome-frame-getting-started
        */ ?>
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

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
            <?php /*
                        These two col_stretcher divs ensure that both the sidebar and content
                        area have the appearance of having the same height.
                    */ ?>
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

<noscript>
    <div id="noscript" style="background-color: #FFCBAF; border: 1px solid #7F0000; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, .3); -moz-box-shadow: 2px 2px 2px rgba(0, 0, 0, .3); -webkit-box-shadow: 2px 2px 2px rgba(0, 0, 0, .3); color: #7F0000; font-weight: bold; left: 15px; padding: 20px; position: absolute; right: 15px; top: 15px; z-index: 10;">
        JavaScript is currently disabled in your browser.
        For full functionality of this website, JavaScript must be enabled.
        If you need assistance, <a href="http://www.enable-javascript.com/" target="_blank">Enable-JavaScript.com</a> provides instructions.
    </div>
</noscript>

<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/data_center/js/jquery-1.9.1.min.js"><\/script>')</script>

<?= $this->Html->script('/data_center/js/datacenter.js') ?>
<?= $this->element('DataCenter.analytics') ?>
<?= $this->fetch('scriptBottom') ?>
<?= $this->fetch('script') ?>
<script>
    $(document).ready(function () {
        <?= $this->fetch('buffered') ?>
    });
</script>

<script type="text/javascript">
    var $buoop = {vs:{i:9,f:20,o:11,s:5,n:9}}
    $buoop.ol = window.onload;
    window.onload=function(){
        try {if ($buoop.ol) $buoop.ol();}catch (e) {}
        var e = document.createElement("script");
        e.setAttribute("type", "text/javascript");
        e.setAttribute("src", "//browser-update.org/update.js");
        document.body.appendChild(e);
    }
</script>
</body>
</html>
