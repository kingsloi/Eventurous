<?php 
	$hideDetails 		= false;
	if($booking['BookingCourse']['hide_details_from_user'] == 0){
		
		$hideDetails 	= true;
	}
?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'#')); ?>
<?php echo $this->element('layout-header', array('currentPage'=>'#')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	
	<div class="page-header">
		<h1>View Your Booking</h1>
	</div>
	<p>View all the details relating to the course/event you are booked on. If there are any changes to your circumstances regarding attending the course/event, please contact the course/event leader using the details below. If you would like to cancel your attendance, then please consider <a href="/bookings/edit/<?php echo $booking['Booking']['id']?>">cancelling your booking</a>.</p>

	<div id="user-sidebar" class="col-md-3">
		<div class="sidebar-subnav" >
			<?php 	
				echo $this->element('user-edit-sidebar', array('item'=>array('id'=>$booking['Booking']['id'],'controller'=>'bookings','action'=>'edit','name'=>'Booking','fullAction'=>'Booking', 'categoryID'=>$booking['Booking']['event_id'], 'categoryURL'=>'/reports/bookings/view/event/')));
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
							<?php	echo $this->element('booking-has-related', array('relatedRecord'=> array('admin'=>false,'ID'=>$booking['Booking']['related'],'relatedRecordType'=>'booking')));?>
						</td>
					</tr>
				<?php } ?>
				<?php if(isset($isRelated)){?>
					<tr class="warning">
						<td colspan="2">
							<?php echo $this->element('booking-is-related', array('relatedRecord'=> array('admin'=>false,'ID'=>$isRelated['Booking']['id'],'relatedRecordType'=>'booking')));?>
						</td>
					</tr>

				<?php }?>
					<tr class="hidden">
						<td>Booking ID</td>
						<td>
							<?php 	
								echo $booking['Booking']['id']; 
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
		</form><!-- edit form-->
	</div><!-- /.table-responsive -->

<div class="record-revisions table-responsive">
	<h2 class="h3">Revision History:</h2>
	<?php if(isset($recordHistory)){?>

		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>Revision Type</th>
					<th>Made By</th>
					<th>Changes</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				<?php 

					foreach($recordHistory as $record): 
						$updateType 	= $record['HistoryLog']['type_action'];
						$madeBy 		= $record['HistoryLog']['made_by'];
						$revisions 		= $record['HistoryLog']['data']['Booking'];
				?>
					<tr>
						<td><?php echo ucwords($updateType);?></td>
						<td><?php echo $madeBy;?></td>
						<td>
							<dl>
								<?php 
									foreach ($revisions as $revisionKey => $revisionValue){
										if(!empty($revisionValue)){
											echo "<dt>".$revisionKey."</dt>";
											echo "<dd>".$revisionValue."</dd>";
										}
										
									}
								?>

							</dl>
						</td>
						<td>
							<?php echo $this->App->formatDatesPretty($record['HistoryLog']['created']);?></td>
					</tr>
				
				<?php endforeach; ?>
			</tbody>
		</table>
	
	<?php 
			echo $this->element('layout-pagination');
		}else{
			echo $this->element('no-results-found', array('text'=>'No revisions found for this item. Please try again later.'));
		}
	?>
</div><!-- /.record-revisions -->


</div><!-- /#page-content .span9 -->
</div><!-- /#page-container .row-fluid -->