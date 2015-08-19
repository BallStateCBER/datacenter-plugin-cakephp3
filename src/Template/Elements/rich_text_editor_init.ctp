<?php 
	/* This element should be included in any view where textarea fields are intended
	 * to be turned into rich text editors. When the rich text editor gets updated or
	 * replaced, the only changes necessary will be within the DataCenter plugin. 
	 * 
	 * Include this element in views like so: 
	 * <?= $this->element('rich_text_editor_init', [], ['plugin' => 'DataCenter']) ?>
	 * 
	 * To customize: http://docs.ckeditor.com
	 */	

	$this->Html->script('/DataCenter/ckeditor/ckeditor.js', ['block' => 'scriptBottom']);
	$this->Html->script('/DataCenter/ckeditor/adapters/jquery.js', ['block' => 'scriptBottom']);
	if (! isset($customConfig)) {
		$customConfig = '';
	}
?>
<?php $this->append('buffered'); ?>
	$('textarea').ckeditor({
		toolbar: 'Basic',
		customConfig: '$customConfig'
	});
<?php $this->end(); ?>