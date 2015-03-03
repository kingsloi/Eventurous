<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-courses')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>

	    <?php if(isset($categories)):?>
			<div class="page-header">
				<h1>Choose a course category</h1>
			</div>
			<p>Select the course category you wish to manage</p>
			<div class="helper">
				<span class="badge alert-info steps">1</span>
				<span class="helper-text">Select the course below</span>
			</div>
		    <div class="booking-courses row">

				<?php foreach ($categories as $category):?>

					<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
						<a class="block" href="<?php echo $this->here.'/category/'.$category['CourseCategory']['id']?>">
							<div class="equal-height-child btn btn-warning btn-block">
								<h2 class="name"><?php echo $category['CourseCategory']['name'];?></h2>

							</div>
						</a>
					</div>

				<?php endforeach; ?>
			</div><!--booking-courses row-->
		<?php endif; ?>

	    <?php if(isset($courses)):?>

			<div class="page-header">
				<h1>Manage Courses			
					<?php if(isset($categoryName)){echo "<span class='badge'>$categoryName</span>";}?>
				</h1>
			</div>
			<p>Select the course you wish to manage</p>
			<div class="helper">
				<span class="badge alert-info steps">1</span>
				<span class="helper-text">Select the course below</span>
			</div>
		    <div class="course-categories row">
		    	<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
					<a class="block" href="/admin/bookingcourses/add/<?php echo $categoryID;?>">
						<div class="equal-height-child btn btn-default btn-block">
							<h2 class="name">Add a new course&hellip;</h2>
						</div>
					</a>
				</div>
				<?php foreach ($courses as $course):?>

					<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
						<a class="block" href="/admin/bookingcourses/edit/<?php echo $course['BookingCourse']['id']?>">
							<div class="equal-height-child btn btn-warning btn-block">
								<h2 class="name"><?php echo $course['BookingCourse']['name'];?></h2>

							</div>
						</a>
					</div>

				<?php endforeach; ?>
			</div> <!--course-categories-->
		<?php endif; ?>

	</div>
</div><!-- /page-content-->