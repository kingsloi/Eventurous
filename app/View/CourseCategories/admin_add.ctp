<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-categories')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Add a new category</h1>
	</div>
	<p>Enter a name and a description for the new category.</p>
	<div class="course-categories row">
		<?php echo $this->Form->create('CourseCategory', array('inputDefaults' => array('label' => false), 'class'=>'form-horizontal','role' => 'form')); ?>
		<fieldset>
			
			<?php echo $this->element('manage-categories');?>

		</fieldset>
				
		<?php echo $this->element('manage-form-buttons', array('backText'=>'Cancel','submitText'=>'Add Category'));?>	
			
		<?php echo $this->Form->end(); ?>
	</div>
</div><!-- /page-content-->