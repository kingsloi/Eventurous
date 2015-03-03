<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-courses')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Add A Course</h1>
	</div>
	<p>Enter course information in the follow below, paying special attention to the checkbox options.</p>
	<div class="helper">
		<span class="badge alert-info steps">1</span>
		<span class="helper-text">Select the course below</span>
	</div>
	<div class="booking-courses-add row">
		<?php echo $this->Form->create('BookingCourse', array('inputDefaults' => array('label' => false), 'url'=>$this->here,'class'=>'form-horizontal','role' => 'form')); 
		?>
			
			<fieldset>
				
				<?php echo $this->element('manage-courses');?>

			</fieldset>
				
		<?php echo $this->element('manage-form-buttons', array('backText'=>'Cancel','submitText'=>'Add Course'));?>	

		<?php echo $this->Form->end(); ?>
	</div>
</div><!-- /page-content-->