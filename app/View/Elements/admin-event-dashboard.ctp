<?php if(!empty($eventInfo)){
	$emptyStatuses 	= "";
	$eventID  		= $eventInfo['Bookings']['eventID'];
?>
<div class="row">
	<div class="col-lg-6">
		<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title inline">
						<span>
							<?php echo $eventInfo['Bookings']['eventName']; ?> Information
						</span>
						<?php echo $this->Html->link('Update', array('controller' => 'events', 'action' => 'edit', $eventID),array('class'=>'btn-xs btn btn-default pull-right')); ?>
					</h3>
				</div>
				<div class="panel-body course-details">
					<div class="clearfix">
						<div class="col-1-2">
							<p class="heading">Start:</p>
							<p class="info"><?php echo $this->App->formatDatesPretty($eventInfo['Bookings']['eventStart']); ?></p>
						</div>
						<div class="col-1-2">
							<p class="heading">Finish:</p>
							<p class="info"><?php echo $this->App->formatDatesPretty($eventInfo['Bookings']['eventFinish']); ?>
							</p>
						</div>
					</div>
					<p class="heading">Location:</p>
					<p class="info"><?php echo $eventInfo['Bookings']['eventLocation']; ?></p>
					
					<div class="clearfix">
						<div class="col-1-2">
							<p class="heading">All/Multiple Day Event?:</p>
							<p class="info">
								<?php echo (($eventInfo['Bookings']['allDayEvent'] == 0) ? 'No' : 'Yes'); ?>
							</p>
						</div>
						<div class="col-1-2">
							<p class="heading">Limit:</p>
							<p class="info">
								<?php 
								$eventCurrentTotal 	= $eventInfo['Bookings']['total'];
								$eventLimit 		= $eventInfo['Bookings']['eventLimit'];
								echo (($eventLimit == 0) ? 'No Limit' : $eventCurrentTotal.'/'.$eventLimit); ?>
							</p>
						</div>
					</div>

					<div class="clearfix">
						<div class="col-1-2">
							<p class="heading">Allow multiple bookings?</p>
							<p class="info"><?php echo (($courseDetails['BookingCourse']['allow_multiple_bookings'] == 1) ? 'True' : 'False'); ?></p>
						</div>
						<div class="col-1-2">
							<p class="heading">Event details hidden from user?</p>
							<p class="info"><?php echo (($courseDetails['BookingCourse']['hide_details_from_user'] == 1) ? 'True' : 'False'); ?>
							</p>
						</div>
					</div>



			</div>
		</div>
		<div class="panel panel-info">
			<div class="panel-heading">Edit all bookings with status&hellip;</div>
			<div class="panel-body">
				<p>Change the status of all bookings, or all bookings with a particular booking status.</p>
				<?php
					echo $this->Form->create('bookings', array('controller'=>'bookings','action'=>'massEditForm','class'=>'form-horizontal'));
 					echo $this->Form->input('eventID', array('type'=>'hidden','value'=>$eventID));
					echo $this->Form->input('bookingStatus', array('label'=>false,'class'=>'form-control mass-edit-course-select',
						'options' => array('all'=>'All Bookings',$bookingStatuses), 
						'empty' => '----- Mass Edit/Update Bookings -----',
						'div'=>false)
					);
				?>
				<button type="submit" class="mass-edit-status-bookings btn btn-primary btn btn-block"><span class="glyphicon glyphicon-edit spaced"></span>Mass Edit Booking Statuses</button>
				<?php echo $this->Form->end();?>
			</div>
		</div><!-- /.mass-edit-bookins-->

		<div class="panel panel-info">
			<div class="panel-heading">Export Event Bookings</div>
			<div class="panel-body">
				<p>Export the all the bookings for this event in a .csv file for additional editing in Micrsoft Excel.</p>
			<?php 
				echo $this->Form->create('bookings', array('controller'=>'bookings','action'=>'downloadBookingsByEventID','class'=>'form-horizontal'));
				echo $this->Form->input('event_id', array('type'=>'hidden', 'value'=>$eventInfo['Bookings']['eventID'])); 
			?>
				<button type="submit" class="btn btn-primary btn-block"><span class='glyphicon glyphicon-cloud-download spaced'></span>Export <?php echo $eventInfo['Bookings']['eventName']; ?> Bookings
				</button>
			<?php echo $this->Form->end();?>
			</div>
		</div><!-- /.export-events-->
	</div><!-- /.left column-->
	<div class="col-lg-6">
			<div class="panel panel-info">
			<div class="panel-heading">Bookings by Status</div>
			<div class="panel-body">
				<div class="booking-statuses-table table-responsive">
					<table class="table table-hover">
						<tbody>
							<tr class="clickable" data-url="/admin/reports/bookings/event/<?php echo $eventID; ?>/">
								<th>Total</th>
								<th><?php echo $eventInfo['Bookings']['total']; ?></th>
							</tr>
							<?php 
								$statusesClass = $this->App->getBookingStatusColour($eventInfo['Bookings']['byStatus']);
								foreach ($eventInfo['Bookings']['byStatus'] as $id => $statusInfo){
									
									$statusClass = $statusesClass[$id];
									if($statusInfo['total'] > 0 ){?>
									
										<tr class="alert-<?php echo $statusClass; ?> clickable" data-url="/admin/reports/bookings/event/<?php echo $eventID; ?>/status/<?php echo $id; ?>">
											<td>
												<?php echo $statusInfo['name'] ?>
											</td>
											<td>
												<?php echo $statusInfo['total'] ?>
											</td>
										</tr>

									<?php }else{

										$emptyStatuses .= '<tr class="condensed"><td>'.$statusInfo['name'].'</td><td>0</td></tr>';
									}
								}

								if($emptyStatuses !== ""){

									echo $emptyStatuses;
								} 
							?>
							</tbody>
						</table>
					</div><!-- /.booking-statuses-table-->
			</div>
		</div>
	</div><!-- /.rightcolumn -->
</div> <!-- /.row -->
	<?php } else {
		echo $this->element('no-results-found', array('text'=>'There are no bookings for you to view. Please try again later.'));
	}
?>
