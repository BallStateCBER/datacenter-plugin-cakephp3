<?php
use Cake\Core\Configure;
?>
<script>

    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)
            [0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-XXXXXXXX-X', 'auto') ;

    ga('send', 'pageview');

</script>
<?php if (! empty($flashMessages)): ?>
    <?php foreach ($flashMessages as $msg): ?>
        <?php
        $message = str_replace('"', '\"', $msg['message']);
        $message = str_replace("\n", "\\n", $message);
        ?>
        <?php $this->append('buffered'); ?>
        flashMessage.insert("<?= $message ?>", "<?= $msg['class'] ?>");
        <?php $this->end(); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (Configure::read('google_analytics_id')): ?>
    <?php $this->append('buffered'); ?>
    ga('send', 'pageview', {
    'page': '<?= $this->request->here ?>',
    'title': '<?= (isset($titleForLayout) ? $titleForLayout : " ") ?>'
    });
    <?php $this->end(); ?>
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