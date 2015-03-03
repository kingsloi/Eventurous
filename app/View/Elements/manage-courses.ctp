<h3>Course Information</h3>
<div class="form-group">

	<label for="CourseCategoryName" class="col-md-3 control-label">
		Course Name
		<span class="additional-option-info">What is this course called?</span>
	</label>
	<?php echo $this->Form->input('name', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryDesc" class="col-md-3 control-label">
		Course Type
		<span class="additional-option-info">What type of course is this? Nomination = people are nominated for an event (i.e. MDP), Self Booking = people can openly book themselves on (i.e. Develop workshops)</span>
	</label>
	<div class="col-md-9">
		<div class="alert-inline alert-danger centered">
		<p>
			Course type is currently disabled.
		</p>
			<?php
			$courseTypeOptions = array('class' => 'form-control');
				// if(isset($type) && $type =='edit'){

				// 	$courseTypeOptions['readonly'] = 'readonly';
				// }else{

				// 	//$courseTypeOptions['readonly'] = 'readonly';
				// 	$courseTypeOptions['selected'] = 2;
				// }

				echo $this->Form->input('course_type_id', $courseTypeOptions); 
			?>
			<p>
				If you'd like to add a Nomination-based course, please contact <?php echo  Configure::read('APP_ADMIN_NAME');?>
			</p>
		</div>
	</div>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Course Category
		<span class="additional-option-info">Which category does this course belong to?</span>
	</label>
	<?php 
		$categoryID = (isset($categoryID) ? $categoryID : $this->data['BookingCourse']['course_category_id']);
		echo $this->Form->input('course_category_id', array('selected'=>$categoryID,'div'=>'col-md-9','class' => 'form-control')); 
	?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Course Description
		<span class="additional-option-info">The course description should given an overview of the course. Please see 'Formatting Help' for help with the formatting i.e. how stuff appears</span>
	</label>
	<div class="col-md-9">
		<?php echo $this->Form->input('desc', array('div'=>false,'class' => 'form-control')); ?>
		<?php echo $this->element('layout-markdown-help');?>
	</div>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Course Criteria
		<span class="additional-option-info">Does this course have any prerequisite, or any criteria that the person booking needs to adhere to?</span>
	</label>
	<div class="col-md-9">
		<?php echo $this->Form->input('criteria_text', array('div'=>false,'class' => 'form-control')); ?>
		<?php echo $this->element('layout-markdown-help');?>
	</div>

</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Hide event details from user? 
		<span class="additional-option-info">(i.e. for MDP courses with additional sub-events)</span>
	</label>
	<div class="col-md-9">
		<div class="btn-group" data-toggle="buttons">
			<?php 
			$hideDetailsFromUser = array('div'=>false,'before'=>'<label class="btn btn-default">','separator'=>'</label><label class="btn btn-default">','after'=>'</label>', 'type' => 'radio','legend'=>false,'fieldset'=>false,'label'=>false,'options' => array('1'=>'Yes','0'=>'No'), 'required'=> true, 'hiddenField' => false);
			echo $this->Form->input('hide_details_from_user', $hideDetailsFromUser);?>
		</div>
	</div>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Allow multiple bookings for this course?
		<span class="additional-option-info">Can an employee attend multiple events? Or can an employee only attend one event within this course?</span>
	</label>
	<div class="col-md-9">
		<div class="btn-group" data-toggle="buttons">
			<?php 
			$allowMultipleBookings = array('div'=>false,'before'=>'<label class="btn btn-default">','separator'=>'</label><label class="btn btn-default">','after'=>'</label>', 'type' => 'radio','legend'=>false,'fieldset'=>false,'label'=>false,'options' => array('1'=>'Yes','0'=>'No'), 'required'=> true, 'hiddenField' => false);
			echo $this->Form->input('allow_multiple_bookings',$allowMultipleBookings);?>
		</div>
	</div>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryOrder" class="col-md-3 control-label">
		Course Order
		<span class="additional-option-info">What order does this course appear in the category?</span>
	</label>
	<?php echo $this->Form->input('order', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Is active/visible?
		<span class="additional-option-info">If set to 'No', category will not be visible to users.</span>
	</label>
	<div class="col-md-9">
		<div class="btn-group" data-toggle="buttons">
			<?php 
			$isActive = array('div'=>false,'before'=>'<label class="btn btn-default">','separator'=>'</label><label class="btn btn-default">','after'=>'</label>', 'type' => 'radio','legend'=>false,'fieldset'=>false,'label'=>false,'options' => array('1'=>'Yes','0'=>'No'), 'required'=> true, 'hiddenField' => false);
			echo $this->Form->input('is_active', $isActive);?>
		</div>
	</div>
</div><!-- .form-group -->


<h3>Contact Info</h3>
<div class="form-group">
	<label for="CourseCategoryContactName" class="col-md-3 control-label">
		Course Contact Name
		<span class="additional-option-info">Who is the 'Go To' person in regads to this couse? Is there a course administrator?</span>
	</label>
	<?php echo $this->Form->input('contact_name', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryContactEmail" class="col-md-3 control-label">
		Course Contact Email
		<span class="additional-option-info">What's the email address address for the above person?</span>
	</label>
	<?php echo $this->Form->input('contact_email', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryContactNumber" class="col-md-3 control-label">
		Course Contact Number
		<span class="additional-option-info">What's the phone number for the above person?</span>
	</label>
	<?php echo $this->Form->input('contact_number', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->