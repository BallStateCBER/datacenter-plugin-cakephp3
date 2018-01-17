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
    <?php $this->append('buffered'); ?>
        gtag('config', '<?= $googleAnalyticsId ?>', <?= json_encode($gaConfig) ?>);
        gtag('event', 'page_view');
    <?php $this->end(); ?>
<?php endif; ?>

<?php if (! empty($flashMessages)): ?>
    <?php foreach ($flashMessages as $msg): ?>
        <?php $this->append('buffered'); ?>
            flashMessage.insert(<?= json_encode($message) ?>, <?= json_encode($msg['class']) ?>);
        <?php $this->end(); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->fetch('content') ?>

<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/data_center/js/jquery-1.9.1.min.js"><\/script>')</script>
<script>
    $(document).ready(function () {
        <?= $this->fetch('buffered') ?>
    });
</script>
