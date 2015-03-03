<div class="form-group">
	<label for="CourseCategoryName" class="col-md-3 control-label">
		Category Name
		<span class="additional-option-info">What is this category called?</span>
	</label>
	<?php echo $this->Form->input('name', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryDesc" class="col-md-3 control-label">
		Category Description
		<span class="additional-option-info">The category description should given an overview of the category. Please see 'Formatting Help' for help with the formatting i.e. how stuff appears</span>
	</label>
	<?php echo $this->Form->input('desc', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Order in lists
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