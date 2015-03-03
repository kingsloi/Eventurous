	<?php echo $this->element('layout-sidebar', array('currentPage'=>'#')); ?>
	<?php echo $this->element('layout-header'); ?>
	<div class="page-content clearfix">
		<?php echo $this->Session->flash(); ?>
		
		<div class="page-header">
			<h1>Edit Booking</h1>
		</div>
		<p>Edit an invididual booking by changing the overall status of the booking, or by moving the booking to another event in the course. Be sure to save the changes using the <strong>Update Booking</strong> button at the bottom of the page.</p>
		<div id="admin-sidebar" class="col-md-3">
			<div class="sidebar-subnav">
				<?php 	
					
					echo $this->element('admin-edit-sidebar', array('item'=>array('id'=>$booking['Booking']['id'],'controller'=>'bookings','action'=>'view','name'=>'Booking','fullAction'=>'Event Booking', 'categoryID'=>$booking['Booking']['event_id'], 'categoryURL'=>'/admin/reports/bookings/event/')));
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
								<?php 	
									echo $booking['Booking']['id']; 
									echo $this->Form->input("id", array('type'=>'hidden', 'value'=>$booking['Booking']['id']));
								?>
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
								<?php //echo $booking['Event']['name']; ?>
								<?php 
									echo $this->Form->input("Booking.event_id", array('label'=>false, 'div'=>false, 'class'=>'form-control', 'selected'=>$booking['Booking']['event_id']));
								?>
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
								<?php 
									echo $this->Form->input("Booking.booking_status_id", array('label'=>false, 'div'=>false, 'class'=>'form-control', 'selected'=>$booking['Booking']['booking_status_id']));
								?>
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

							<td>
								<?php 
									echo $this->Form->input("Booking.booking_notes", array('label'=>false, 'div'=>false, 'class'=>'form-control','value'=>$booking['Booking']['booking_notes']));
								?>
							</td>
						</tr>
						<tr>
							<td>Booked By</td>

							<td><?php echo $booking['Booking']['booked_by']; ?></td>
						</tr>
						<tr>
							<td>Created</td>

							<td><?php echo date("d-m-Y H:i", strtotime($booking['Booking']['created']));?></td>
						</tr>

						<tr>
							<td>Last Modified</td>

							<td><?php echo date("d-m-Y H:i", strtotime($booking['Booking']['modified']));?></td>
						</tr>
					</tbody>
				</table><!-- /.table table-striped table-bordered -->
				<button type="submit" class="btn btn-primary btn-lg pull-right">Update Booking</button>
			</form><!-- edit form-->
		</div><!-- /.table-responsive -->
	</div><!-- /#page-content .span9 -->
</div><!-- /#page-container .row-fluid -->