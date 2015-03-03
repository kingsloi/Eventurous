<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-courses')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header clearfix">
		<h1 class="col-sm-9">
			Edit Course
		</h1>
		<div class="col-sm-3 ">
			<?php echo $this->Form->postLink(__('Delete Course + Events + Bookings'), array(
                	'controller' 	=> 'bookingcourses',
                	'escape'		=> false,
					'action' 		=> 'delete', $this->request->data['BookingCourse']['id']), array(
					'class' 		=> 'btn btn-lg btn-delete pull-right'), __(
					'Are you sure you want to delete the %s course AND all associated events and bookings? [Action cannot be undone]', $this->request->data['BookingCourse']['name']
					)
				);
            ?>
		</div>
	</div>
	<p>Edit the course information below</p>
	<div class="helper">
		<span class="badge alert-info steps">1</span>
		<span class="helper-text">Select the course below</span>
	</div>
	<div class="booking-courses-add row">
		<?php echo $this->Form->create('BookingCourse', array('inputDefaults' => array('label' => false), 'url'=>$this->here,'class'=>'form-horizontal','role' => 'form')); 
		?>
			<?php echo $this->Form->input('id', array('type'=>'hidden')); ?>

			<fieldset>
				
				<?php echo $this->element('manage-courses', array('type'=>'edit'));?>

			</fieldset>
				
		<?php echo $this->element('manage-form-buttons', array('backText'=>'Cancel','submitText'=>'Update Course'));?>	

		<?php echo $this->Form->end(); ?>
	</div>
</div><!-- /page-content-->