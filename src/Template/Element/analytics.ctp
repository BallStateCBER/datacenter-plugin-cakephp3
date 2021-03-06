<?php
    /** @var \Cake\View\View $this */
    use Cake\Core\Configure;

    $googleAnalyticsId = Configure::read('google_analytics_id');
    $debug = Configure::read('debug');
    $gaConfig = [
        'page_location' => $this->request->getUri()->__toString(),
        'page_path' => $this->request->getUri()->getPath()
    ];
    if (isset($titleForLayout) && $titleForLayout) {
        $gaConfig['page_title'] = $titleForLayout;
    }
?>
<?php if ($googleAnalyticsId && !$debug): ?>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $googleAnalyticsId ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= $googleAnalyticsId ?>', <?= json_encode($gaConfig) ?>);
    </script>
<?php endif; ?>
