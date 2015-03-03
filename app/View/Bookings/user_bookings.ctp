	<?php echo $this->element('layout-sidebar', array('currentPage'=>'#')); ?>
	<?php echo $this->element('layout-header'); ?>
	<div class="page-content clearfix">
		<?php echo $this->Session->flash(); ?>
		
		<div class="page-header">
			<h1><?php echo $pageTitle;?></h1>
		</div>
		<?php echo $pageDesc; ?>
		
		<div id="page-content" class="">
			<?php if (!empty($bookings)): ?>
				
				<div class="table-responsive">
					<table class="table table-bordered sub-table">
						<thead>
							<tr>
								<th><?php echo __('Course'); ?></th>
								<th><?php echo __('Event'); ?></th>
								<th><?php echo __('Status'); ?></th>
								<th><?php echo __('Event Location'); ?></th>
								<th><?php echo __('Event Start'); ?></th>
								<th><?php echo __('Event Finish'); ?></th>
								
								<th><?php echo __('Last Edited'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($bookings as $booking): ?>

									<tr class="clickable" data-url="/bookings/view/<?php echo $booking['Booking']['id']; ?>/">
										<td><?php echo $booking['BookingCourse']['name']; ?></td>
										<td><?php echo $booking['Event']['name']; ?></td>
										<td><?php echo $booking['BookingStatus']['name']; ?></td>
										<?php if($booking['BookingCourse']['hide_details_from_user'] == 0){?>
											<td>
												<?php echo $booking['Event']['location']; ?>
											</td>
											<td>
												<?php echo $this->App->formatDatesPretty($booking['Event']['event_start'], true); ?>
											</td>
											<td>
												<?php echo $this->App->formatDatesPretty($booking['Event']['event_finish'], true); ?>
											</td>
										<?php }else{ ?>
											<td colspan="3">
												<?php echo $this->element('course-hidden-event-details', array('text'=>'Please refer to '.$booking['BookingCourse']['name'].' invite/speak to course leader for more information.')); ?>
											</td>
										<?php } ?>
										<td>
											<?php echo $this->App->formatDatesPretty($booking['Booking']['modified']); ?>
										</td>
									</tr>
								<?php endforeach; ?>
						</tbody>
					</table><!-- /.table table-striped table-bordered -->
				</div><!-- /.table-responsive -->
			<?php else:?>
				<?php echo $this->element('no-results-found', array('text'=>'There are no bookings for you to view. Please try again later.'));?>
			<?php endif; ?>	
	</div><!-- /#page-content .span9 -->