<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'#')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	
	<div class="page-header">
		<h1 class="col-lg-12">Mass Edit Bookings for
			<span class="badge"><?php echo $selectedEvent['BookingCourse']['name']; ?></span>
			<span class="badge"><?php echo $selectedEvent['Event']['name']; ?></span>
			
		</h1>
	</div>
	<p>This page is designed for you to mass update the booking status for all the booking you would like to edit. To edit a status, simply find the booking you would like to edit, select the new status in the dropdown box, finish by pressing the <strong>Update Bookings</strong> button at the bottom of the page. Any bookings that <em>haven't</em> had their status changed, will not be saved.</p>
	<?php echo $this->Form->create('Booking');?>
	<div class="table-responsive">
		<table cellpadding="0" cellspacing="0" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Reason</th>
					<th>Status</th>
					<th>Booked By</th>
					<th>Added</th>
					<th class="actions">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(!empty($allUnconfirmedBookings)){

						foreach ($allUnconfirmedBookings as $id => $booking):?>

							<tr>
								<td>
									<?php echo $booking['Booking']['id']; ?>
									<?php 
									echo $this->Form->input("Booking.$id.id", array('value'=>$booking['Booking']['id'], 'type'=>'hidden'));

									echo $this->Form->input("Booking.$id.bookingEditType", array('value'=>'admin', 'type'=>'hidden'));
									?>
								</td>
								<td>
									<?php echo $this->Html->link($booking['Profile']['fullname'], array('controller' => 'profiles', 'action' => 'view', $booking['Profile']['id'])); ?>
								</td>
								<td>
									<?php echo $booking['BookingReason']['name']; ?>
								</td>
								<td>
									<?php 
									echo $this->Form->input("Booking.$id.booking_status_id", array('label'=>false, 'div'=>false, 'class'=>'form-control', 'selected'=>$booking['Booking']['booking_status_id']));
									?>
								</td>
								<td><?php echo $booking['Booking']['booked_by']; ?></td>
								
								<td><?php echo date("d-m-Y H:i", strtotime($booking['Booking']['created'])); ?></td>
								
								<td class="actions">
									<?php echo $this->Html->link(__('<span class="glyphicon glyphicon-eye-open"></span>'), array('controller'=>'bookings','action' => 'view', $booking['Booking']['id']), array('class' => 'btn btn-primary btn-small','escape'=>false)); ?>
								</td>
							</tr>
				<?php endforeach; 
			}else{
				echo '<tr><td colspan="9">'.$this->element('no-results-found', array('text'=>'There are no bookings for you to edit.'))."</td></tr>";
			}?>
			</tbody>
		</table>
	</div><!-- /.table-responsive -->
	<button type="submit" class="btn btn-primary btn-lg pull-right">Update Bookings</button>
	<?php echo $this->Form->end(); ?>
</div><!-- /.page-content -->