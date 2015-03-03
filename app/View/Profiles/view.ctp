<?php echo $this->element('layout-header', array('currentPage'=>'my-profile')); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'#')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>

	<div class="page-header clearfix">
		<h1 class="col-md-9"><?php echo $pageTitle; ?></h1>
		<div class="col-md-3 ">
			<a class="btn btn-primary pull-right" href="/profile/edit">Edit</a>
		</div>
	</div>

	<p>Below is an overview of your details and future/past bookings + overview of any bookings that require review by you, or our out for review by the course/event leader, including any canclled bookings.<br/><br/>A user report from HRMS is imported once a week. If there are any changes to your location/job title/name, please ensure these are reflected in HRMS. If you haven't already, please add an email/phone number to your account to ensure the booking process is as smooth as possible.</p>
	<div class="row">
		<div class="col-md-5 col-lg-4">
			<div class="stats-container">
				<div class="col-sm-12">
					<div class="panel panel-danger">
						<div class="panel-heading"><h2>Require Review:</h2></div>
						<div class="panel-body">
							<ul class="stats">						
								<li class=' <?php echo ($userBookingsForReview == 0 ? 'condensed' : '' ) ;?>'>
									<a href="/bookings/review">
										<span class="big"><?php echo $userBookingsForReview;?></span>
										<span class="stat">Bookings need your attention</span>
									</a>
								</li>
								<li class=' <?php echo ($userBookingsOutForReview == 0 ? 'condensed' : '' ) ;?>'>
									<a href="/bookings/outforreview">
										<span class="big"><?php echo $userBookingsOutForReview;?></span>
										<span class="stat">Bookings out for review</span>
									</a>
								</li>
								<li class=' <?php echo ($userBookingsCancelled == 0 ? 'condensed' : '' ) ;?>'>
									<a href="/bookings/cancelled">
										<span class="big"><?php echo $userBookingsCancelled;?></span>
										<span class="stat">Cancelled Bookings</span>
									</a>
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
								<?php $totalBookingsThisMonth = count($userBookingsMonth);?>
								<li class=' <?php echo ($totalBookingsThisMonth == 0 ? 'condensed' : '' ) ;?>'>
									<a href="/bookings/thismonth">
										<span class="big">
											<?php echo $totalBookingsThisMonth;?></span>
											<span class="stat">Bookings this month</span>
										</a>
									</li>
									<li class=' <?php echo ($userBookingsFuture == 0 ? 'condensed' : '' ) ;?>'><a href="/bookings/future"><span class="big"><?php echo $userBookingsFuture;?></span><span class="stat">Future Bookings</span></a></li>	
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-heading"><h2>Previous:</h2></div>
							<div class="panel-body">
								<ul class="stats">
									<li class='<?php echo ($userBookingsPast == 0 ? 'condensed' : '' ) ;?>'><a href="/bookings/previous"><span class="big"><?php echo $userBookingsPast;?></span><span class="stat">Previous Bookings</span></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div><!-- stats-container-->
			</div>
			<div class="col-md-7 col-lg-8">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h2>Your Profile</h2>
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
					<div class="panel-heading"><h2>Upcoming events:</h2></div>
					<div class="panel-body">
						<?php if (!empty($userBookingsMonth)): ?>
						
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
									foreach ($userBookingsMonth as $booking): ?>
									<tr class="clickable" data-url="/bookings/view/<?php echo $booking['Booking']['id']; ?>/">
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
					<?php else:?>
					<?php echo $this->element('no-results-found', array('text'=>'You aren\'t currently booked to attend any courses/events this month. Get Booking!.'));?>
				<?php endif; ?>


				<div class="actions pull-right">
					<a href="categories" class="btn btn-lg btn-primary">Add Booking</a>			
				</div><!-- /.actions -->

			</div><!-- panel-body-->
		</div><!-- /panel-->
	</div>
</div>
</div><!-- /#page-container .row-fluid -->
