<?php
    use Cake\Core\Configure;
?>
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