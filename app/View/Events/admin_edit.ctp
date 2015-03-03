<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-events')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header clearfix">
		<h1 class="col-sm-9">Edit Event</h1>
		<div class="col-sm-3 ">
			<?php echo $this->Form->postLink(__('Delete Event + Bookings'), array(
                	'controller' 	=> 'events',
                	'escape'		=> false,
					'action' 		=> 'delete', $this->request->data['Event']['id']), array(
					'class' 		=> 'btn btn-lg btn-delete pull-right'), __(
					'Are you sure you want to delete the %s event AND all associated bookings? [Action cannot be undone]', $this->request->data['Event']['name']
					)
				);
            ?>
		</div>
	</div>
	<p>Edit the Event information below</p>
	<div class="events row">
		<?php echo $this->Form->create('Event', array('inputDefaults' => array('label' => false), 'class'=>'form-horizontal','role' => 'form')); ?>
			<?php echo $this->Form->input('id', array('type'=>'hidden')); ?>
			<fieldset>

				<?php echo $this->element('manage-events');?>

			</fieldset>
				
			<?php echo $this->element('manage-form-buttons', array('backText'=>'Cancel','submitText'=>'Update Event'));?>		

		<?php echo $this->Form->end(); ?>
	</div>
</div><!-- /page-content-->