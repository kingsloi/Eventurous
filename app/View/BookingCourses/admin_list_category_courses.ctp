<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'admin-dashboards')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Select a course			
			<?php if(isset($categoryName)){echo "<span class='badge'>$categoryName</span>";}?>
		</h1>
	</div>
	<p>Please select the course you would like to view.</p>
	<div class="helper">
		<span class="badge alert-info steps">1</span>
		<span class="helper-text">Select the course category below</span>
	</div>
	<?php if(!empty($courses)){?>
		<div class="course-categories row">
			<?php foreach ($courses as $course){?>
			<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
				<a class="block" href="/admin/dashboard/course/<?php echo $course['BookingCourse']['id']?>">
					<div class="equal-height-child btn btn-primary btn-block">
						<h2 class="name"><?php echo $course['BookingCourse']['name'];?></h2>
					</div>
				</a>
			</div>
			<?php } ?>
		</div>
	<?php }else{?>
		<?php echo $this->element(
		'no-results-found', array(
			'text'=>"There are no courses in this category. <a href='/admin/bookingcourses/add/$categoryID'>Add an course</a>."
			)
		);?>
	<?php } ?>
</div><!-- /page-content-->
