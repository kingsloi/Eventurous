<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'admin-dashboards')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	
	<div class="page-header">
		<h1>View Booking</h1>
	</div>
	<p>The table below lists all information related to the booking and the person it was booked for. Including the course, the event it's booked on, who booked it, any questions which were answered, when the booking was made, when it was last edited, its current status, and any revisions that have been made.</p>

		<div id="admin-sidebar" class="col-md-3">
			<div class="sidebar-subnav">
			
				<?php 	
					echo $this->element('admin-edit-sidebar', array('item'=>array('id'=>$booking['Booking']['id'],'controller'=>'bookings','action'=>'edit','name'=>'Booking','fullAction'=>'Event Booking', 'categoryID'=>$booking['Booking']['event_id'], 'categoryURL'=>'/admin/reports/bookings/event/')));
				?>
			</div><!-- /sidebar-subnav-->
		</div><!-- /#sidebar .col-sm-3 -->
	<div id="page-content" class="col-md-9">

	<div class="table-responsive">
		<table class="table table-striped table-bordered">
			<tbody>
				<?php if(isset($hasRelated)){?>
					<tr class="danger">
						<td colspan="2">
							<?php	echo $this->element('booking-has-related', array('relatedRecord'=> array('admin'=>true,'ID'=>$booking['Booking']['related'],'relatedRecordType'=>'booking')));?>
						</td>
					</tr>
				<?php } ?>
				<?php if(isset($isRelated)){?>
					<tr class="warning">
						<td colspan="2">
							<?php echo $this->element('booking-is-related', array('relatedRecord'=> array('admin'=>true,'ID'=>$isRelated['Booking']['id'],'relatedRecordType'=>'booking')));?>
						</td>
					</tr>

				<?php }?>
				<tr>
					<td>Booking ID</td>
					<td>
						<?php echo $booking['Booking']['id']; ?>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<strong>User Information:</strong>
					</td>
				</tr>
				<tr>
					<td>Name</td>
					<td>
						<?php echo $this->Html->link($booking['Profile']['fullname'], array('controller' => 'profiles', 'action' => 'view', $booking['Profile']['id']), array('class' => '')); ?>
					</td>
				</tr>
				<tr>
					<td>Job Title</td>
					<td>
						<?php echo $this->App->formatJobTitle($booking['JobTitle']['id'], $booking['JobTitle']['title'], false);?>
					</td>
				</tr>
				<tr>
					<td>Region</td>
					<td>
						<?php echo $booking['Region']['name']; ?>
					</td>
				</tr>
				<tr>
					<td>Store/Department</td>
					<td>
						<?php echo $booking['Store']['name']; ?>
					</td>
				</tr>
				<tr>
					<td>User Notes</td>
					<td>
						<?php echo $booking['Note']['note']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<strong>Course / Event Information:</strong>
					</td>
				</tr>
				<tr>
					<td>Course</td>
					<td>
						<?php echo $booking['BookingCourse']['name']; ?>
					</td>
				</tr>
				<tr>
					<td>Event</td>

					<td>
						<?php echo $booking['Event']['name']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<strong>Booking Information:</strong>
					</td>
				</tr>
				<tr>
					<td>Reason</td>

					<td>
						<?php echo $booking['BookingReason']['name']; ?>
					</td>
				</tr>
				<tr>
					<td>Status</td>
					<td>
						<?php echo $this->App->formatStatus($booking['BookingStatus']['id'], $booking['BookingStatus']['name'], false); ?>
					</td>
				</tr>
				<tr>
					<td>Booking Questions</td>
					<td>
						<?php if(!empty($booking['Booking']['booking_criteria'])){?>
							<table class="table table-condensed small-table left-column-header">
								<?php 
									foreach($booking['Booking']['booking_criteria'] as $id => $questionAnswer){
										echo "<tr><td>". $questionAnswer['question']."</td><td>". $questionAnswer['answer']."</td></tr>";
									}
								?>
							</table>
						<?php }else{
							echo "N/A";
						} ?>
					</td>
				</tr>
				<tr>
					<td>Booking Notes</td>
					<td><?php echo $booking['Booking']['booking_notes'];?></td>
				</tr>
				<tr>
					<td>Booked By</td>
					<td><?php echo $booking['Booking']['booked_by']; ?></td>
				</tr>
				<tr>
					<td>Created</td>
					<td><?php echo $this->App->formatDatesPretty($booking['Booking']['created']);?></td>
				</tr>
				<tr>
					<td>Last Modified</td>
					<td><?php echo $this->App->formatDatesPretty($booking['Booking']['modified']);?></td>
				</tr>
			</tbody>
		</table><!-- /.table table-striped table-bordered -->
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
				echo $this->element('no-results-found', array('text'=> 'No revisions found for this item. Please try again later.'));
			}
		?>
	</div><!-- /.record-revisions -->
</div><!-- /#page-content .span9 -->