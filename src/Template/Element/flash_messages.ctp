<?php
	/* This creates the hidden #flash_messages container and fills it with
	 * flash messages and displayed via a javascript animation if there are
	 * messages to display. Regardless, the container is put onto the page
	 * so that asyncronous activity can load messages into it as needed. */
	if (! empty($flashMessages)) {
	    $this->append('buffered');
		echo 'flashMessage.init();';
		$this->end();
	}
?>
<div id="flash_messages" style="display: none;">
	<div>
		<div>
			<div class="close"><a href="#" id="close_flash_msg">Close</a></div>
			<?php $this->append('buffered'); ?>
				$('#close_flash_msg').click(function(event) {
					event.preventDefault();
					flashMessage.hide();
				});
			<?php $this->end(); ?>
			<div class="messages_wrapper">
				<ul>
					<?php if (! empty($flashMessages)): ?>
						<?php foreach ($flashMessages as $msg): ?>
							<li class="<?= $msg['class'] ?>">
								<?= $msg['message'] ?>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
				<br class="clear" />
			</div>
		</div>
	</div>
</div>