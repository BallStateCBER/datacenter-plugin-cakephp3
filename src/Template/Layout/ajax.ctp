<?php if (! empty($flash_messages)): ?>
	<?php foreach ($flash_messages as $msg): ?>
		<?php
            $message = str_replace('"', '\"', $msg['message']);
            $message = str_replace("\n", "\\n", $message);
        ?>
		<?php $this->append('buffered'); ?>
            flashMessage.insert("<?= $message ?>", "<?= $msg['class'] ?>");
		<?php $this->end(); ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php
	// Only invoke Google Analytics if an ID is found and the page is not being served from the development server
	$google_analytics_id = Configure::read('google_analytics_id');
	$not_localhost = isset($_SERVER['SERVER_NAME']) && stripos($_SERVER['SERVER_NAME'], 'localhost') === false;
?>

<?php if ($google_analytics_id && $not_localhost): ?>
	<?php $this->append('buffered'); ?>
		ga('send', 'pageview', {
			'page': '<?= $this->request->here ?>',
			'title': '<?= (isset($titleForLayout) ? $titleForLayout : "''") ?>'
		});
    <?php $this->end(); ?>
<?php endif; ?>

<?= $this->fetch('content') ?>

<script>
	$(document).ready(function () {
        <?= $this->fetch('buffered') ?>
    });
</script>