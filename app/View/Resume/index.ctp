<h2>Submit Resume</h2>
<?php 
echo $this->Form->create('Resume', array('type'=>'file'));
echo $this->Form->input('message');
?>

<?php if (!empty($this->data['Resume']['filepath'])): ?>
	<div class="input">
		<label>Uploaded File</label>
		<?php
		echo $this->Form->input('filepath', array('type'=>'hidden'));
		echo $this->Html->link(basename($this->data['Resume']['filepath']), $this->data['Resume']['filepath']);
		?>
	</div>
<?php else: ?>
	<?php echo $this->Form->input('filename',array(
		'type' => 'file'
	)); ?>
<?php endif; ?>

<?php
echo $this->Form->end('Submit');
?>