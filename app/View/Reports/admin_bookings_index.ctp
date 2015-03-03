<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'rpt-bookings')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Choose a report booking category</h1>
	</div>
	<p>Choose which category you'd like to see the bookings for. View bookings by&hellip;</p>
	<div class="helper">
		<span class="badge alert-info steps">1</span>
		<span class="helper-text">Select the course category below</span>
	</div>
	<div class="course-categories row">

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
			<a class="block" href="/admin/reports/bookingsIndex/course">
				<div class="equal-height-child btn btn-primary btn-block">
					<h2 class="name">Course</h2>
				</div>
			</a>
		</div>

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
			<a class="block" href="/admin/reports/bookingsIndex/event">
				<div class="equal-height-child btn btn-primary btn-block">
					<h2 class="name">Event</h2>
				</div>
			</a>
		</div>

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
			<a class="block" href="/admin/reports/bookingsIndex/region">
				<div class="equal-height-child btn btn-primary btn-block">
					<h2 class="name">Region</h2>
				</div>
			</a>
		</div>

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
			<a class="block" href="/admin/reports/bookingsIndex/store">
				<div class="equal-height-child btn btn-primary btn-block">
					<h2 class="name">Store</h2>
				</div>
			</a>
		</div>

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
			<a class="block" href="/admin/reports/bookingsIndex/user">
				<div class="equal-height-child btn btn-primary btn-block">
					<h2 class="name">User</h2>
				</div>
			</a>
		</div>		

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
			<a class="block" href="/admin/reports/bookingsIndex/booked_by">
				<div class="equal-height-child btn btn-primary btn-block">
					<h2 class="name">Nominator</h2>
				</div>
			</a>
		</div>

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
			<a class="block" href="/admin/reports/bookingsIndex/job-title">
				<div class="equal-height-child btn btn-primary btn-block">
					<h2 class="name">Job Title</h2>
				</div>
			</a>
		</div>


	</div>
</div><!-- /page-content-->