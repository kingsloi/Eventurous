<div class="form-group">
	
	<label for="EventName" class="col-md-3 control-label">
		Event Name
		<span class="additional-option-info">What's the Event called? (What's it known as? Q1 2014, Phase 1? October? Monday? etc.)</span>
	</label>
	<?php echo $this->Form->input('name', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Course
		<span class="additional-option-info">What course does this event belong to?</span>
	</label>
	<?php 
		$courseID = (isset($courseID) ? $courseID : $this->data['Event']['booking_course_id']);

		echo $this->Form->input('booking_course_id', array('selected'=>$courseID,'div'=>'col-md-9','class' => 'form-control')); 
	?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="EventDesc" class="col-md-3 control-label">
		Event Description
		<span class="additional-option-info">Does this event have a particular description? Room location, what to bring, where to arrive/sign in, etc.</span>
	</label>
	<?php echo $this->Form->input('desc', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Start Date/Time
		<span class="additional-option-info">What time does this event start? Leave both the start AND finish time as 12:00:00am for all day events OR events that span over a range of dates</span>
	</label>
	<div class="col-md-9 md-centered-text">
		<?php echo $this->Form->input('event_start', array('div'=>false,'class' => 'form-control inline-select','separator' => ' / ','type'=>'date','dateFormat' => 'DMY','minYear' => date('Y') - 1, 'maxYear' => date('Y') + 5)); ?>
		<div class="hidden-md hidden-lg col-sm-12">&nbsp;</div>@<div class="hidden-md hidden-lg col-sm-12">&nbsp;</div> <?php echo $this->Form->input('event_start', array('div'=>false,'class' => 'form-control inline-select','separator' => ':','type'=>'time','interval' => 15)); ?>
	</div>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Finish Date/Time
		<span class="additional-option-info">What time does this event finish? Leave both the start AND finish time as 12:00:00am for all day events OR events that span over a range of dates</span>
	</label>
	<div class="col-md-9 md-centered-text">
		<?php echo $this->Form->input('event_finish', array('div'=>false,'class' => 'form-control inline-select','separator' => ' / ','type'=>'date','dateFormat' => 'DMY','minYear' => date('Y') - 1, 'maxYear' => date('Y') + 5)); ?>
		<div class="hidden-md hidden-lg col-sm-12">&nbsp;</div>@<div class="hidden-md hidden-lg col-sm-12">&nbsp;</div> <?php echo $this->Form->input('event_finish', array('div'=>false,'class' => 'form-control inline-select','separator' => ':','type'=>'time','interval' => 15)); ?>
	</div>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		All Day Event?
		<span class="additional-option-info">Does this event span over multiple days? or is it an all day event with no specified start/end time?</span>
	</label>
	<div class="col-md-9">
		<div class="btn-group" data-toggle="buttons">
			<?php 
			$allDayEvent = array('div'=>false,'before'=>'<label class="btn btn-default">','separator'=>'</label><label class="btn btn-default">','after'=>'</label>', 'type' => 'radio','legend'=>false,'fieldset'=>false,'label'=>false,'options' => array('1'=>'Yes','0'=>'No'), 'required'=> true, 'hiddenField' => false);
			echo $this->Form->input('all_day_event', $allDayEvent);?>
		</div>
	</div>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Event Location
		<span class="additional-option-info">Where is this event located? Room Number, Building, Remote, etc.</span>
	</label>
	<?php echo $this->Form->input('location', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Event Limit
		<span class="additional-option-info">Does this event have a particular person limit? Once limit has been reached, people will not be able to book onto this event. <br/> Enter 0 for no limit</span>
	</label>
	<?php echo $this->Form->input('limit', array('div'=>'col-md-9','class' => 'form-control')); ?>
</div><!-- .form-group -->	

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Allow Bookings?
		<span class="additional-option-info">Should this event allow bookings? An event which has Allow Bookings set to true will allow people to be booked onto the course. If it's set to false, then the event will be visable, but the user will not be able to book on to the event.</span>
	</label>
	<div class="col-md-9">
		<div class="btn-group" data-toggle="buttons">
			<?php 
			$allowBooking = array('div'=>false,'before'=>'<label class="btn btn-default">','separator'=>'</label><label class="btn btn-default">','after'=>'</label>', 'type' => 'radio','legend'=>false,'fieldset'=>false,'label'=>false,'options' => array('1'=>'Yes','0'=>'No'), 'required'=> true, 'hiddenField' => false);
			echo $this->Form->input('allow_bookings', $allowBooking);?>
		</div>
	</div>
</div><!-- .form-group -->

<div class="form-group">
	<label for="CourseCategoryorder" class="col-md-3 control-label">
		Event Closed?
		<span class="additional-option-info">Is this event closed? If the event is closed, it is not visable to the user.</span>
	</label>
	<div class="col-md-9">
		<div class="btn-group" data-toggle="buttons">
			<?php 
			$eventClosed = array('div'=>false,'before'=>'<label class="btn btn-default">','separator'=>'</label><label class="btn btn-default">','after'=>'</label>', 'type' => 'radio','legend'=>false,'fieldset'=>false,'label'=>false,'options' => array('1'=>'Yes','0'=>'No'), 'required'=> true, 'hiddenField' => false);
			echo $this->Form->input('closed', $eventClosed);?>
		</div>
	</div>
</div><!-- .form-group -->