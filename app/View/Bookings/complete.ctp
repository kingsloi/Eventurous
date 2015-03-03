<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'booking-complete')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header clearfix">
		<h1 class="col-lg-9">Booking Complete</h1>
		<?php echo $this->element('booking-progress'); ?>
	</div>
	<p>Your booking request has been submitted. Further correspondence will follow by email, or if no email is assigned to your account, (<a href="/profile/edit/">why not add one?</a>) please log in periodically to check the status of your booking(s).</p>
	<a class="btn btn-primary btn-lg" href="/nominate-booking">Start Over</a>
</div>