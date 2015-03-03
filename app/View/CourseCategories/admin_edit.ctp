<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-categories')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	
	<div class="page-header clearfix">
		<h1 class="col-sm-9">Edit Category</h1>
		<div class="col-sm-3 ">
			<?php echo $this->Form->postLink(__('Delete Category + Courses + Events + Bookings'), array(
                	'controller' 	=> 'coursecategories',
                	'escape'		=> false,
					'action' 		=> 'delete', $this->request->data['CourseCategory']['id']), array(
					'class' 		=> 'btn btn-lg btn-delete pull-right'), __(
					'Are you sure you want to delete the %s category AND all associated course, events, and bookings? [Action cannot be undone]', $this->request->data['CourseCategory']['name']
					)
				);
            ?>
		</div>
	</div>
	<p>Enter a name and a description for the new category.</p>
	<div class="course-categories row">
		<?php echo $this->Form->create('CourseCategory', array('inputDefaults' => array('label' => false), 'class'=>'form-horizontal','role' => 'form')); ?>				
		<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>

		<fieldset>
			
			<?php echo $this->element('manage-categories');?>

		</fieldset>
				
		<?php echo $this->element('manage-form-buttons', array('backText'=>'Cancel','submitText'=>'Update Category'));?>

		<?php echo $this->Form->end(); ?>
	</div>
</div><!-- /page-content-->