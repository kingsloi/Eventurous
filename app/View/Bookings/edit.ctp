<?php 
	$hideDetails 		= false;
	if($booking['BookingCourse']['hide_details_from_user'] == 0){
		$hideDetails 	= true;
	}
?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'#')); ?>
<?php echo $this->element('layout-header'); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>

	<div class="page-header">
		<h1>Cancel Your Booking</h1>
	</div>
	<p>To cancel your booking, first advise the course leader using the details below, then press the <strong>Cancel Your Booking</strong> button at the bottom of the page.</p>

	<div id="user-sidebar" class="col-md-3">
		<div class="sidebar-subnav" >
			<?php 	
			echo $this->element('user-edit-sidebar', array('item'=>array('id'=>$booking['Booking']['id'],'controller'=>'bookings','action'=>'view','name'=>'Booking','fullAction'=>'Event Booking', 'categoryID'=>$booking['Booking']['event_id'], 'categoryURL'=>'/reports/bookings/event/')));
			?>
		</div><!-- /sidebar-subnav-->
	</div><!-- /#sidebar .col-sm-3 -->
	<div id="page-content" class="col-md-9">
		<div class="table-responsive">
			<?php echo $this->Form->create('Booking'); ?>
				<table class="table table-striped table-bordered">
					<tbody>
						<?php if(isset($hasRelated)){?>
						<tr class="danger">
							<td colspan="2">
								<?php	echo $this->element('booking-has-related', array('relatedRecord'=> array('ID'=>$booking['Booking']['related'],'relatedRecordType'=>'booking')));?>
							</td>
						</tr>
						<?php } ?>
						<?php if(isset($isRelated)){?>
						<tr class="warning">
							<td colspan="2">
								<?php echo $this->element('booking-is-related', array('relatedRecord'=> array('ID'=>$isRelated['Booking']['id'],'relatedRecordType'=>'booking')));?>
							</td>
						</tr>

						<?php }?>
						<tr class="hidden">
							<td>Booking ID</td>
							<td>
								<?php 	
								echo $booking['Booking']['id']; 
								echo $this->Form->input("id", array('type'=>'hidden', 'value'=>$booking['Booking']['id']));
								?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<strong>Course Information:</strong>
							</td>
						</tr>
						<tr>
							<td>Course</td>
							<td>
								<?php echo $booking['BookingCourse']['name']; ?>
							</td>
						</tr>
						<tr>
							<td>Contact Email</td>
							<td>
								<?php echo $booking['BookingCourse']['contact_name']; ?>
							</td>
						</tr>
						<tr>
							<td>Contact Email</td>
							<td>
								<?php echo $booking['BookingCourse']['contact_email']; ?>
							</td>
						</tr>
						<tr>
							<td>Contact Number</td>
							<td>
								<?php echo $booking['BookingCourse']['contact_number']; ?>
							</td>
						</tr>
						<tr>
							<td>Course Description</td>

							<td>
								<?php echo Markdown($booking['BookingCourse']['desc']); ?>
							</td>
						</tr>
						<tr>
							<td>Course Criteria</td>

							<td>
								<?php echo Markdown($booking['BookingCourse']['criteria_text']); ?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<strong>Event Information:</strong>
							</td>
						</tr>
						<tr>
							<td>Event</td>

							<td>
								<?php echo $booking['Event']['name']; ?>
							</td>
						</tr>
						<tr>
							<td>Event Start</td>
							<?php if($hideDetails) {?>
							<td>
								<?php echo $this->App->formatDatesPretty($booking['Event']['event_start'], true); ?>
							</td>
							<?php }else{?>
							<td rowspan='3'>
								<?php echo $this->element('course-hidden-event-details', array('text'=>'Please refer to '.$booking['BookingCourse']['name'].' invite/speak to course leader for more information.')); ?>
							</td>
							<?php } ?>
						</tr>
						<tr>
							<td>Event Finish</td>
							<?php if($hideDetails) {?>
							<td>
								<?php echo $this->App->formatDatesPretty($booking['Event']['event_finish'], true); ?>
							</td>
							<?php }?>
						</tr>
						<tr>
							<td>Event Location</td>
							<?php if($hideDetails) {?>
							<td>
								<?php echo $booking['Event']['location']; ?>
							</td>
							<?php }?>
						</tr>
						<tr>
							<td colspan="2">
								<strong>Your status for this booking is:</strong>
							</td>
						</tr>
						<tr>
							<td>Status</td>
							<td>
								<?php echo $this->App->formatStatus($booking['BookingStatus']['id'], $booking['BookingStatus']['name'], false); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<strong>Booking Details:</strong>
							</td>
						</tr>
						<tr>
							<td>Booked By</td>

							<td><?php echo $booking['Booking']['booked_by']; ?></td>
						</tr>

						<tr>
							<td>First Created</td>

							<td><?php echo $this->App->formatDatesPretty($booking['Booking']['created'], true);?></td>
						</tr>
						<tr>
							<td>Last Modified</td>

							<td>
								<?php echo $this->App->formatDatesPretty($booking['Booking']['modified'], true);?>
							</td>
						</tr>
					</tbody>
				</table><!-- /.table table-striped table-bordered -->
				<button type="submit" id="cancel-booking" class="btn btn-danger btn-lg pull-right">Cancel Your Booking</button>
			</form><!-- edit form-->
		</div><!-- /.table-responsive -->
	</div><!-- /#page-content .span9 -->
</div><!-- /#page-container .row-fluid -->