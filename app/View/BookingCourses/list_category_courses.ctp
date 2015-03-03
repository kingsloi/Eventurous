<?php 
$eventCount   = 0;
$tabHeadings  = "";
$tabContent   = "";
$courseTitle  = "";
if(!empty($courses)){

	foreach($courses as $id => $course):
		
		$courseID         = $course['BookingCourse']['id'];
		$courseName       = $course['BookingCourse']['name'];    
		$activeClass      = ($eventCount == 0 ? 'active' : '');
		$tabHeadings     .= "
		<li class='$activeClass '>
		<a href='#course$courseID' data-toggle='tab'>$courseName</a>
		</li>";

		$tabContent     .=  "<div class='tab-pane $activeClass' id='course$courseID'>";
		$tabContent     .=  $this->element('courses-tabbed-events', array('courseInfo'=>$course)
			);

		$tabContent     .=  "</div><!-- /course$courseID-->";
		$eventCount++;

	endforeach; 
}
?>
<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'view-courses')); ?>
<div class="course-list page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Choose a course and an event</h1>
	</div>
	<p>Please choose the course and event you would like to attend.</p>
	<div class="helper">
		<span class="badge alert-info steps">2</span>
		<span class="helper-text">Select the course below</span>
	</div>
	<?php if(!empty($tabHeadings)){?>

		<div class="tabbed-content tabbable tabs-left">
			<div class="mobile-headings col-xs-12 visible-xs">&nbsp;</div>
			<ul class="nav nav-tabs tab-heading col-sm-3 col-md-2 hidden-xs">
				<?php echo $tabHeadings;?>
			</ul>
			<div class="tab-content tab-body col-sm-9 col-md-10">
				<?php echo $tabContent; ?>
			</div><!-- /.tab-content-->
		</div><!-- /.tabbed-content-->

		<?php } else {?>

			<div class="col-sm-12">
			<?php echo $this->element('no-results-found', array('text'=>'Unfortunately there are no courses in this category. Please try again later.'));?>
			</div>
		
		<?php } ?>
</div><!-- /page-content-->
