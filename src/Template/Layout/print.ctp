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
    <link rel="shortcut icon" href="/favicon.ico" />
    <link href="https://fonts.googleapis.com/css?family=Asap:400,400italic,700" rel="stylesheet" type="text/css">
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
</head>
<body class="default-layout print">

    <?= $this->fetch('top-html') ?>

    <?= $this->fetch('content') ?>

    <?= $this->Html->script('/data_center/js/datacenter.js') ?>
    <?= $this->element('DataCenter.analytics') ?>
    <?= $this->fetch('scriptBottom') ?>
    <?= $this->fetch('script') ?>
    <script>
        $(document).ready(function () {
            <?= $this->fetch('buffered') ?>
        });
    </script>
</body>
</html>
