	<?php echo $this->element('layout-sidebar', array('currentPage'=>'#')); ?>
	<?php echo $this->element('layout-header'); ?>
	<div class="page-content clearfix">
		<?php echo $this->Session->flash(); ?>
		
		<div class="page-header">
			<h1>Confirm your bookings</h1>
		</div>
		<p>
			Carefully review the bookings below. These bookings were reviewed by the respective course leader, and they have accepted your booking request, and are happy for you to attend their course/event at the date/time specified. 
		</p>
		<p>Review each booking, pressing <strong>Accept Invitation</strong> if you are happy to attend the course/event, and <strong>Decline Inivitation</strong> if you no longer wish to attend the course/event and decline your booking.</p>
		<div id="page-content" class="">
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Course</th>
							<th>Event</th>
							<th>Event Details</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($bookings as $id => $booking){?>
							<tr>
								<td>
									<?php echo $bookings[$id]['BookingCourse']['name']; ?>
								</td>
								<td>
									<?php echo $bookings[$id]['Event']['name']; ?>
								</td>
								<td>
									<?php if($bookings[$id]['BookingCourse']['hide_details_from_user'] == false):?>
										<dlist class="dl-horizontal no-margin">
											<dt>Location:</dt>
											<dd><?php echo $bookings[$id]['Event']['location']; ?></dd>
											<dt>Start:</dt>
											<dd><?php echo $bookings[$id]['Event']['event_start']; ?></dd>
											<dt>Finish:</dt>
											<dd><?php echo $bookings[$id]['Event']['event_finish']; ?></dd>
										</dlist>
										<?php else:

										echo $this->element('course-hidden-event-details', array('text'=>'Please refer to '.$booking['BookingCourse']['name'].' invite/speak to course leader for more information.'));
										endif;
									?>
								</td>
								<td>
									<?php echo $this->Form->postLink(__('Accept Invitation'), array(
						                	'controller' 	=> 'bookings',
						                	'escape'		=> false,
											'action' 		=> 'acceptBooking', $bookings[$id]['Booking']['id']), array(
											'class' 		=> 'btn btn-success'), __(
											'Are you sure you want to accept your %s invitation?', $bookings[$id]['Event']['name']
											)
										);
						            ?>
									-or-
									<?php echo $this->Form->postLink(__('Decline Invitation'), array(
						                	'controller' 	=> 'bookings',
						                	'escape'		=> false,
											'action' 		=> 'rejectBooking', $bookings[$id]['Booking']['id']), array(
											'class' 		=> 'btn btn-danger'), __(
											'Are you sure you want to reject your %s invitation? Rejecting your invitation will withdraw you from attending the course/event.', $bookings[$id]['BookingCourse']['name']
											)
										);
						            ?>
								</td>
							</tr>
						<?php }

						if(empty($bookings)){
							
							echo "<tr><td colspan='4'>".$this->element('no-results-found', array('text'=>'There are no bookings for you to review. Please try again later.'))."</td></tr>";
						}
						?>

					</tbody>
				</table>
			</div>	
	</div><!-- /#page-content .span9 -->