<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'add-additional-info')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	
	<div class="page-header clearfix">
		<h1 class="col-lg-9">Add Addional <?php echo $bookingTypeName; ?> Information</h1>
		<?php echo $this->element('booking-progress'); ?>
	</div>

	<p>In order to nomiate an employee, this course requires you to add some additional information to support your nomination. Please read and answer each question.</p>
	<div class="col-md-3 well employee-details">
		<h2 class="h4">Employee Details:</h2>
		<div class="row">
			<div class="col-xs-4 detail-title">HRMS:</div>
			<div class="col-xs-8"><?php echo $selectedUser['User']['username'];?></div>
		</div>
		<div class="row">
			<div class="col-xs-4 detail-title">Name:</div>
			<div class="col-xs-8">
				<?php echo $selectedUser['Profile']['first_name']." ".$selectedUser['Profile']['surname'];?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 detail-title">Region:</div>
			<div class="col-xs-8"><?php echo $selectedUser['Region']['name'];?></div>
		</div>
		<div class="row">
			<div class="col-xs-4 detail-title">Store:</div>
			<div class="col-xs-8"><?php echo $selectedUser['Store']['name'];?></div>
		</div>
	</div>

	<div class="col-md-9 booking-details">
		<?php echo $this->Form->create('Booking', array('class' => 'form-vertical', 'role'=>'form'));?>
		<?php echo $this->Form->input('Booking.id'); ?>
		<div class="approval">
			<legend class="question-heading required">
				Does the nominee have their ARDs approval?
			</legend>
			<div class="btn-group" data-toggle="buttons">
				<?php 
				$radioAttributes = array('div'=>false,'before'=>'<label class="btn btn-default">','separator'=>'</label><label class="btn btn-default">','after'=>'</label>', 'type' => 'radio','legend'=>false,'fieldset'=>false,'label'=>false,'options' => array('Y'=>'Yes','N'=>'No'), 'required'=> 'true', 'hiddenField' => false);
				echo $this->Form->input('Approval', $radioAttributes);?>
			</div>
		</div>
		<div class="questions">
			<?php echo $this->element('booking-additional-information-form'); ?>
		</div>
		<div class="booking-notes">
			<?php
				echo $this->Form->input('Booking.booking_notes', array('class'=>'form-control','label'=>'Booking Notes', 'div'=>false, 'placeholder'=>'i.e. Reason for ARD refusal'));
			?>
		</div>
		<div class="form-buttons">
			<div class="form-group ">
				<div class="col-lg-3 ">
					<button type="submit" class="btn btn-danger" name="data[submitType]" value="cancel"><span class="glyphicon glyphicon-remove"></span> Cancel Booking</button>
				</div>
				<div class="col-lg-3 pull-right">
					<button type="submit" class="btn btn-success" name="data[submitType]" value="approve">Next <span class="glyphicon glyphicon-arrow-right"></span></button>
				</div>
			</div>
		</div><!--/form-buttons-->
		<?php echo $this->Form->end(); ?>
		<div id="empty-form-container">
		</div>
	</div>
</div>