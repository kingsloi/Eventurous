<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'rpt-all')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Choose a report category</h1>
	</div>
	<p>Choose the report category you'd like to run a report on</p>
	<div class="helper">
		<span class="badge alert-info steps">1</span>
		<span class="helper-text">Select the course category below</span>
	</div>
	<div class="course-categories row">

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
      		<a class="block" href="/admin/reports/users">
        		<div class="equal-height-child btn btn-primary btn-block">
         			<h2 class="name">Users</h2>
        		</div>
      		</a>
    	</div>

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
      		<a class="block" href="/admin/reports/regions">
        		<div class="equal-height-child btn btn-primary btn-block">
         			<h2 class="name">Regions</h2>
        		</div>
      		</a>
    	</div>

		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
      		<a class="block" href="/admin/reports/stores">
        		<div class="equal-height-child btn btn-primary btn-block">
         			<h2 class="name">Stores</h2>
        		</div>
      		</a>
    	</div>
    	
		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
      		<a class="block" href="/admin/reports/bookings">
        		<div class="equal-height-child btn btn-primary btn-block">
         			<h2 class="name">Bookings</h2>
        		</div>
      		</a>
    	</div>

	</div>
</div><!-- /page-content-->