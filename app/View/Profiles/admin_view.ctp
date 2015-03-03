<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'rpt-users')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>

	<div class="page-header clearfix">
		<h1 class="col-md-9"><?php echo $pageTitle; ?></h1>
		<div class="col-md-3 ">
			<a class="btn btn-primary pull-right" href="/profile/edit">Add Contact Details</a>
		</div>
	</div>

	<p>See employee details, what events they've booked on, and the status of those bookings. If an employee's details are incorrect, a weekly user report from HRMS is imported every week, so please ensure any changes to an employee's name/location/job title are made to HRMS, which will then be reflected in the system once an import has taken place.</p>
	<div class="row">
		<div class="col-md-5 col-lg-4">
			<div class="stats-container">
				<div class="col-sm-12">
					<div class="panel panel-danger">
						<div class="panel-heading"><h2>Require Review:</h2></div>
						<div class="panel-body">
							<ul class="stats">						
								<li class=' <?php echo ($userBookingsForReview == 0 ? 'condensed' : '' ) ;?>'>
									<span class="big"><?php echo $this->App->formatBigNumbers($userBookingsForReview);?></span>
									<span class="stat">Bookings need your attention</span>

								</li>
								<li class=' <?php echo ($userBookingsOutForReview == 0 ? 'condensed' : '' ) ;?>'>
									<span class="big"><?php echo $this->App->formatBigNumbers($userBookingsOutForReview);?></span>
									<span class="stat">Bookings out for review</span>
								</li>
								<li class=' <?php echo ($userBookingsCancelled == 0 ? 'condensed' : '' ) ;?>'>
									<span class="big"><?php echo $this->App->formatBigNumbers($userBookingsCancelled);?></span>
									<span class="stat">Cancelled Bookings</span>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="panel panel-success">
						<div class="panel-heading"><h2>Currently booked on:</h2></div>
						<div class="panel-body">
							<ul class="stats">
								<li class=' <?php echo ($userBookingsMonth == 0 ? 'condensed' : '' ) ;?>'>
										<span class="big">
											<?php echo $this->App->formatBigNumbers($userBookingsMonth);?></span>
										<span class="stat">Future Bookings</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-heading"><h2>Previous:</h2></div>
							<div class="panel-body">
								<ul class="stats">
									<li class='<?php echo ($userBookingsPast == 0 ? 'condensed' : '' ) ;?>'>
										<span class="big"><?php echo $this->App->formatBigNumbers($userBookingsPast);?></span>
										<span class="stat">Previous Bookings</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div><!-- stats-container-->
			</div>
			<div class="col-md-7 col-lg-8">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h2><?php echo $profile['Profile']['first_name']?> Details</h2>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="bio-row">
								<p>
									<span>HRMS:</span><?php echo $profile['User']['username']; ?>
								</p>
							</div>
							<div class="bio-row">
								<p>
									&nbsp;
								</p>
							</div>
							<div class="bio-row">
								<p>
									<span>First name:</span><?php echo $profile['Profile']['first_name']; ?>
								</p>
							</div>
							<div class="bio-row">
								<p>
									<span>Surname:</span><?php echo $profile['Profile']['surname']; ?>
								</p>
							</div>
							<div class="bio-row">
								<p>
									<span>Region:</span><?php echo $profile['Region']['name']; ?>
								</p>
							</div>
							<div class="bio-row">
								<p>
									<span>Store:</span><?php echo $profile['Store']['name']; ?>
								</p>
							</div>
							<div class="bio-row">
								<p>
									<span>Job Title:</span><?php echo $profile['JobTitle']['title']; ?>
								</p>
							</div>
							<div class="bio-row">
								<p>
									<span>Email:</span><?php echo $profile['Profile']['email']; ?>
								</p>
							</div>
							<div class="bio-row">
								<p>
									<span>Phone:</span><?php echo $profile['Profile']['phonenumber']; ?>
								</p>
							</div>
							<div class="bio-row">
								<p>
									<span>Last Modified:</span><?php echo $this->App->formatDatesPretty($profile['Profile']['modified']);?>
								</p>
							</div>
						</div><!--/.panel-body-->
					</div><!--/.panel-->
				</div><!-- /.row -->
			</div><!-- /right-column-->

		</div><!-- row-->
		<div class="row">
			<div class="col-lg-12 large-top-margin">
				<div class="panel panel-info">
					<div class="panel-heading"><h2><?php echo $profile['Profile']['first_name']?>'s Future Bookings</h2></div>
					<div class="panel-body">
						<?php if (!empty($allUserBookings)): ?>
						
						<div class="table-responsive">
							<table class="table table-bordered sub-table">
								<thead>
									<tr>
							            <th><?php echo $this->Paginator->sort('BookingCourse.name','Couse'); ?></th>
							            <th><?php echo $this->Paginator->sort('Event.name','Event'); ?></th>
							            <th><?php echo $this->Paginator->sort('BookingStatus.name','Status'); ?></th>
							            <th><?php echo $this->Paginator->sort('Event.event_start','Event Start'); ?></th>
							            <th><?php echo $this->Paginator->sort('Event.event_finish','Event Finish'); ?></th>
							            <th><?php echo $this->Paginator->sort('Booking.modified','Last Edited'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($allUserBookings as $booking): ?>

									<tr class="clickable" data-url="/admin/bookings/view/<?php echo $booking['Booking']['id']; ?>/">
										<td>
											<?php echo $booking['BookingCourse']['name']; ?>
										</td>
										<td>
											<?php echo $booking['Event']['name']; ?>
										</td>
										<td>
											<?php echo $booking['BookingStatus']['name']; ?>
										</td>
										
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
									<?php 
									endforeach; 
									?>
								</tbody>
							</table><!-- /.table table-striped table-bordered -->
						</div><!-- /.table-responsive -->
					
					<?php else: ?>

					<?php echo $this->element('no-results-found', array('text'=>'User has not attended / is not attending any courses/events.'));?>
				<?php endif; ?>
<?php echo $this->element('layout-pagination'); ?>
			</div><!-- panel-body-->
		</div><!-- /panel-->

	</div>
</div>
</div><!-- /#page-container .row-fluid -->
