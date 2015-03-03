<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-events')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>

    <div class="booking-courses row">

	    <?php if(isset($categories)):?>
			<div class="page-header">
				<h1>Choose a Category</h1>
			</div>
			<p>Select which category you would like to add to/manage the events in</p>
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
				<h1>Choose a Course			
					<?php if(isset($categoryName)){echo "<span class='badge'>$categoryName</span>";}?>
				</h1>
			</div>
			<p>Select which course you would like to add to/manage the events in</p>
			<div class="helper">
				<span class="badge alert-info steps">1</span>
				<span class="helper-text">Select the course below</span>
			</div>
		    <div class="course-categories row">

				<?php foreach ($courses as $course):?>

					<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
						<a class="block" href="<?php echo $this->here.'/course/'.$course['BookingCourse']['id']?>">
							<div class="equal-height-child btn btn-warning btn-block">
								<h2 class="name"><?php echo $course['BookingCourse']['name'];?></h2>

							</div>
						</a>
					</div>

				<?php endforeach; ?>
			</div> <!--course-categories-->
		<?php endif; ?>

	    <?php if(isset($events)):?>

			<div class="page-header">
				<h1>Manage Events			
					<?php if(isset($courseName)){echo "<span class='badge'>$courseName</span>";}?>
				</h1>
			</div>
			<p>Choose whether you'd like to add an additional event, or edit an exisiting event</p>
			<div class="helper">
				<span class="badge alert-info steps">3</span>
				<span class="helper-text">Select the event below</span>
			</div>
		    <div class="events row">

		    	<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
					<a class="block" href="/admin/events/add/<?php echo $courseID;?>">
						<div class="equal-height-child btn btn-default btn-block">
							<h2 class="name">Add a new event&hellip;</h2>
						</div>
					</a>
				</div>

				<?php foreach ($events as $event):?>
					<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
						<a class="block" href="/admin/events/edit/<?php echo $event['Event']['id'];?>">
							<div class="equal-height-child btn btn-warning btn-block">
								<h2 class="name"><?php echo $event['Event']['name'];?></h2>

							</div>
						</a>
					</div>

				<?php endforeach; ?>
			</div> <!--course-categories-->
		<?php endif; ?>


    </div>
</div>
