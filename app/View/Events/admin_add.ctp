<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-events')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Add an Event</h1>
	</div>
	<p>Add a new Event</p>
	<div class="events row">
		<?php echo $this->Form->create('Event', array('inputDefaults' => array('label' => false), 'class'=>'form-horizontal','role' => 'form')); ?>

			<fieldset>

				<?php echo $this->element('manage-events');?>

			</fieldset>
				
			<?php echo $this->element('manage-form-buttons', array('backText'=>'Cancel','submitText'=>'Update Event'));?>		

		<?php echo $this->Form->end(); ?>
	</div>
</div><!-- /page-content-->